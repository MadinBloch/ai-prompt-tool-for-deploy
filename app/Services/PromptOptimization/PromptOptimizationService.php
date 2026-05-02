<?php

namespace App\Services\PromptOptimization;

use App\Ai\Agents\PromptContextAnalyzerAgent;
use App\Ai\Agents\PromptOptimizer;
use App\Data\PromptOptimizationResult;
use App\Models\PromptOptimization;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PromptOptimizationService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        private readonly PromptProviderManager $providerManager,
    ) {}

    /**
     * Create a prompt optimization record.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): PromptOptimization
    {
        $sourcePrompt = trim((string) $attributes['prompt']);
        $preferredProvider = $attributes['provider'] ?? null;
        $enhanceContext = (bool) ($attributes['enhance_context'] ?? true);
        $cacheKey = $this->cacheKey($sourcePrompt, $preferredProvider, $enhanceContext);

        return PromptOptimization::create([
            'fingerprint' => hash('sha256', $sourcePrompt),
            'requested_provider' => $preferredProvider,
            'provider_chain' => $this->providerManager->chain($preferredProvider),
            'source_prompt' => $sourcePrompt,
            'estimated_tokens_before' => $this->estimateTokens($sourcePrompt),
            'cache_key' => $cacheKey,
            'enhance_context' => $enhanceContext,
            'status' => $attributes['status'] ?? PromptOptimization::STATUS_PENDING,
            'queued_at' => ($attributes['status'] ?? null) === PromptOptimization::STATUS_QUEUED ? now() : null,
        ]);
    }

    /**
     * Process the optimization request.
     */
    public function process(PromptOptimization $optimization): PromptOptimizationResult
    {
        $optimization->forceFill([
            'status' => PromptOptimization::STATUS_PROCESSING,
        ])->save();

        /** @var array<string, mixed>|null $cachedPayload */
        $cachedPayload = Cache::get($optimization->cache_key);

        if (is_array($cachedPayload)) {
            $result = new PromptOptimizationResult(
                optimizedPrompt: $cachedPayload['optimized_prompt'],
                contextSummary: $cachedPayload['context_summary'],
                improvements: $cachedPayload['improvements'],
                estimatedTokensBefore: $cachedPayload['estimated_tokens_before'],
                estimatedTokensAfter: $cachedPayload['estimated_tokens_after'],
                usage: $cachedPayload['usage'],
                provider: $cachedPayload['provider'],
                model: $cachedPayload['model'],
                cached: true,
                meta: $cachedPayload['meta'] ?? [],
            );

            $optimization->forceFill($result->toPersistenceArray())->save();

            return $result;
        }

        $providerChain = $this->providerManager->chain($optimization->requested_provider);

        $analysis = (new PromptContextAnalyzerAgent)->prompt(
            $optimization->source_prompt,
            provider: $providerChain,
        );

        $optimizationPrompt = $this->buildOptimizationPrompt(
            sourcePrompt: $optimization->source_prompt,
            analysis: $analysis->toArray(),
            estimatedTokensBefore: $optimization->estimated_tokens_before,
            enhanceContext: $optimization->enhance_context,
        );

        $rewrite = (new PromptOptimizer)->prompt(
            $optimizationPrompt,
            provider: $providerChain,
        );

        $estimatedTokensAfter = (int) ($rewrite['token_estimate_after'] ?? $this->estimateTokens($rewrite['optimized_prompt']));

        $result = new PromptOptimizationResult(
            optimizedPrompt: $rewrite['optimized_prompt'],
            contextSummary: $rewrite['context_summary'],
            improvements: $rewrite['improvements'],
            estimatedTokensBefore: (int) ($rewrite['token_estimate_before'] ?? $optimization->estimated_tokens_before),
            estimatedTokensAfter: $estimatedTokensAfter,
            usage: $analysis->usage->add($rewrite->usage)->toArray(),
            provider: $rewrite->meta->provider ?? $analysis->meta->provider ?? 'unknown',
            model: $rewrite->meta->model ?? $analysis->meta->model ?? 'unknown',
            cached: false,
            meta: [
                'analysis' => $analysis->toArray(),
                'analysis_provider' => $analysis->meta->provider,
                'analysis_model' => $analysis->meta->model,
                'provider_chain' => $providerChain,
            ],
        );

        Cache::put($optimization->cache_key, $result->toArray(), now()->addHour());

        $optimization->forceFill($result->toPersistenceArray())->save();

        return $result;
    }

    /**
     * Mark an optimization as failed.
     */
    public function markFailed(PromptOptimization $optimization, string $message): void
    {
        $optimization->forceFill([
            'status' => PromptOptimization::STATUS_FAILED,
            'error_message' => Str::limit($message, 1000),
            'processed_at' => now(),
        ])->save();
    }

    /**
     * Build the rewrite prompt for the optimizer agent.
     *
     * @param  array<string, mixed>  $analysis
     */
    private function buildOptimizationPrompt(
        string $sourcePrompt,
        array $analysis,
        int $estimatedTokensBefore,
        bool $enhanceContext,
    ): string {
        $contextBlock = $enhanceContext
            ? $analysis['context_summary']
            : 'Do not add inferred context unless it is absolutely necessary.';

        return <<<TEXT
            Optimize the following prompt for production AI usage.

            Source prompt:
            {$sourcePrompt}

            Analyzer summary:
            - Intent: {$analysis['intent']}
            - Audience: {$analysis['audience']}
            - Deliverable: {$analysis['deliverable']}
            - Constraints: {$this->implodeLines($analysis['constraints'])}
            - Missing context: {$this->implodeLines($analysis['missing_context'])}
            - Context enhancement policy: {$contextBlock}

            Output goals:
            - Reduce token usage without losing the user's real intent.
            - Improve clarity, structure, and execution reliability.
            - Keep the optimized prompt concise and production-ready.
            - Estimated original tokens: {$estimatedTokensBefore}
            TEXT;
    }

    /**
     * Build a stable cache key for the request.
     */
    private function cacheKey(string $prompt, ?string $provider, bool $enhanceContext): string
    {
        return 'po:'.hash('sha256', implode('|', [
            Str::squish($prompt),
            $provider ?: 'default',
            $enhanceContext ? '1' : '0',
        ]));
    }

    /**
     * Estimate prompt tokens using a lightweight heuristic.
     */
    private function estimateTokens(string $content): int
    {
        return max(1, (int) ceil(mb_strlen($content) / 4));
    }

    /**
     * Convert a list of strings into a compact line.
     *
     * @param  array<int, string>  $items
     */
    private function implodeLines(array $items): string
    {
        return empty($items) ? 'None' : implode('; ', $items);
    }
}
