<?php

namespace App\Ai\Agents;

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
#[MaxTokens(900)]
#[Temperature(0.2)]
#[Timeout(90)]
class PromptOptimizer implements Agent, HasMiddleware, HasProviderOptions, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<TEXT
            You are a production-grade AI prompt optimization specialist.

            Rewrite the user's prompt into a cleaner, lower-token, higher-signal version.

            Requirements:
            - Preserve the original intent.
            - Reduce redundancy and vague filler.
            - Add only context that is strongly implied by the source material.
            - Prefer short, explicit instructions over long prose.
            - Use this exact section order when it helps: ROLE, CONTEXT, TASK, CONSTRAINTS, OUTPUT.
            - If a section is not useful, omit it.
            - Never invent product names, APIs, budgets, or deadlines.
            - Return concise bullet-point improvement notes.
            TEXT;
    }

    /**
     * Get the agent's prompt middleware.
     */
    public function middleware(): array
    {
        return [
            new \App\Ai\Middleware\CapturePromptTelemetry,
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
            'optimized_prompt' => $schema->string()->required(),
            'context_summary' => $schema->string()->required(),
            'token_estimate_before' => $schema->integer()->required(),
            'token_estimate_after' => $schema->integer()->required(),
            'improvements' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
