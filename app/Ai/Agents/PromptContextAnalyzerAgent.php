<?php

namespace App\Ai\Agents;

use App\Ai\Middleware\CapturePromptTelemetry;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasMiddleware;
use Laravel\Ai\Contracts\HasProviderOptions;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[UseCheapestModel]
#[MaxTokens(500)]
#[Temperature(0.1)]
#[Timeout(60)]
class PromptContextAnalyzerAgent implements Agent, HasMiddleware, HasProviderOptions, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<TEXT
            You analyze messy user prompts before optimization.

            Extract only the high-value context needed to improve the prompt:
            - core intent
            - expected deliverable
            - likely audience
            - hard constraints
            - missing context that should be surfaced carefully

            Keep every field compact and practical. Do not rewrite the final prompt.
            TEXT;
    }

    /**
     * Get the agent's prompt middleware.
     */
    public function middleware(): array
    {
        return [
            new CapturePromptTelemetry,
        ];
    }

    /**
     * Get provider-specific generation options.
     */
    public function providerOptions(Lab|string $provider): array
    {
        return match ($provider) {
            Lab::OpenAI => [
                'reasoning' => ['effort' => 'low'],
            ],
            default => [],
        };
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'intent' => $schema->string()->required(),
            'audience' => $schema->string()->required(),
            'deliverable' => $schema->string()->required(),
            'constraints' => $schema->array()->items($schema->string())->required(),
            'missing_context' => $schema->array()->items($schema->string())->required(),
            'context_summary' => $schema->string()->required(),
        ];
    }
}
