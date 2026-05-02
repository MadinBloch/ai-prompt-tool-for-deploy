<?php

namespace App\Data;

use App\Models\PromptOptimization;

class PromptOptimizationResult
{
    /**
     * Create a new data instance.
     */
    public function __construct(
        public readonly string $optimizedPrompt,
        public readonly string $contextSummary,
        public readonly array $improvements,
        public readonly int $estimatedTokensBefore,
        public readonly int $estimatedTokensAfter,
        public readonly array $usage,
        public readonly string $provider,
        public readonly string $model,
        public readonly bool $cached,
        public readonly array $meta = [],
    ) {}

    /**
     * Convert the result to a cache-safe array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'optimized_prompt' => $this->optimizedPrompt,
            'context_summary' => $this->contextSummary,
            'improvements' => $this->improvements,
            'estimated_tokens_before' => $this->estimatedTokensBefore,
            'estimated_tokens_after' => $this->estimatedTokensAfter,
            'usage' => $this->usage,
            'provider' => $this->provider,
            'model' => $this->model,
            'cached' => $this->cached,
            'meta' => $this->meta,
        ];
    }

    /**
     * Convert the result to database attributes.
     *
     * @return array<string, mixed>
     */
    public function toPersistenceArray(): array
    {
        return [
            'status' => PromptOptimization::STATUS_COMPLETED,
            'optimized_prompt' => $this->optimizedPrompt,
            'context_summary' => $this->contextSummary,
            'improvements' => $this->improvements,
            'estimated_tokens_before' => $this->estimatedTokensBefore,
            'estimated_tokens_after' => $this->estimatedTokensAfter,
            'prompt_tokens' => $this->usage['prompt_tokens'] ?? 0,
            'completion_tokens' => $this->usage['completion_tokens'] ?? 0,
            'cache_write_input_tokens' => $this->usage['cache_write_input_tokens'] ?? 0,
            'cache_read_input_tokens' => $this->usage['cache_read_input_tokens'] ?? 0,
            'reasoning_tokens' => $this->usage['reasoning_tokens'] ?? 0,
            'provider_used' => $this->provider,
            'model_used' => $this->model,
            'cached_response' => $this->cached,
            'meta' => $this->meta,
            'processed_at' => now(),
        ];
    }
}
