<?php

namespace Database\Factories;

use App\Models\PromptOptimization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromptOptimization>
 */
class PromptOptimizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fingerprint' => hash('sha256', $this->faker->unique()->sentence()),
            'requested_provider' => 'gemini',
            'provider_used' => 'gemini',
            'model_used' => 'fake-model',
            'provider_chain' => ['gemini', 'openai', 'groq'],
            'source_prompt' => $this->faker->paragraph(),
            'optimized_prompt' => $this->faker->paragraph(),
            'context_summary' => $this->faker->sentence(),
            'improvements' => [$this->faker->sentence(), $this->faker->sentence()],
            'estimated_tokens_before' => 120,
            'estimated_tokens_after' => 80,
            'prompt_tokens' => 40,
            'completion_tokens' => 35,
            'cache_write_input_tokens' => 0,
            'cache_read_input_tokens' => 0,
            'reasoning_tokens' => 0,
            'cache_key' => 'prompt-optimization:'.$this->faker->unique()->sha1(),
            'cached_response' => false,
            'enhance_context' => true,
            'status' => PromptOptimization::STATUS_COMPLETED,
            'meta' => [],
            'queued_at' => now(),
            'processed_at' => now(),
        ];
    }
}
