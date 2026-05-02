<?php

use App\Ai\Agents\PromptContextAnalyzerAgent;
use App\Ai\Agents\PromptOptimizer;
use App\Jobs\ProcessPromptOptimization;
use App\Models\PromptOptimization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

if (extension_loaded('pdo_sqlite')) {
    uses(RefreshDatabase::class);
}

beforeEach(function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The pdo_sqlite extension is not installed in this environment.');
    }
});

test('it optimizes a prompt synchronously through the api', function () {
    PromptContextAnalyzerAgent::fake([
        [
            'intent' => 'Design a production-ready AI prompt optimization system.',
            'audience' => 'Senior Laravel engineer',
            'deliverable' => 'Architecture and implementation plan',
            'constraints' => ['Minimize token usage', 'Keep it production-ready'],
            'missing_context' => ['No deployment target provided'],
            'context_summary' => 'The user wants a scalable Laravel 13 prompt optimization pipeline.',
        ],
    ]);

    PromptOptimizer::fake([
        [
            'optimized_prompt' => '[ROLE] Senior AI architect [TASK] Build a Laravel 13 prompt optimization platform.',
            'context_summary' => 'Converted the request into a concise production-ready build brief.',
            'token_estimate_before' => 180,
            'token_estimate_after' => 96,
            'improvements' => ['Removed filler', 'Added clear deliverables', 'Kept provider support explicit'],
        ],
    ]);

    $response = $this->postJson('/api/prompt-optimizations', [
        'prompt' => 'Please build me a really good prompt optimizer system in Laravel with a nice architecture and make it scalable.',
        'provider' => 'gemini',
        'enhance_context' => true,
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.status', PromptOptimization::STATUS_COMPLETED)
        ->assertJsonPath('data.provider', 'gemini')
        ->assertJsonPath('data.estimated_tokens_after', 96);

    $optimization = PromptOptimization::query()->first();

    expect($optimization)
        ->not->toBeNull()
        ->and($optimization->status)->toBe(PromptOptimization::STATUS_COMPLETED)
        ->and($optimization->optimized_prompt)->toContain('[ROLE]');
});

test('it serves repeated requests from cache without prompting providers again', function () {
    PromptContextAnalyzerAgent::fake([
        [
            'intent' => 'Improve a prompt',
            'audience' => 'Developer',
            'deliverable' => 'Optimized prompt',
            'constraints' => ['Be concise'],
            'missing_context' => [],
            'context_summary' => 'A short optimization request.',
        ],
    ]);

    PromptOptimizer::fake([
        [
            'optimized_prompt' => 'Optimized prompt output',
            'context_summary' => 'Short summary',
            'token_estimate_before' => 60,
            'token_estimate_after' => 30,
            'improvements' => ['Shortened wording'],
        ],
    ]);

    $payload = [
        'prompt' => 'Improve this prompt and make it cleaner for production use.',
        'provider' => 'openai',
    ];

    $this->postJson('/api/prompt-optimizations', $payload)->assertOk();

    PromptContextAnalyzerAgent::fake()->preventStrayPrompts();
    PromptOptimizer::fake()->preventStrayPrompts();

    $this->postJson('/api/prompt-optimizations', $payload)
        ->assertOk()
        ->assertJsonPath('data.cached', true);

    expect(PromptOptimization::query()->latest()->first()?->cached_response)->toBeTrue();
});

test('it queues prompt optimization requests when async mode is enabled', function () {
    Queue::fake();

    $response = $this->postJson('/api/prompt-optimizations', [
        'prompt' => 'Queue this optimization request and process it later with provider failover.',
        'async' => true,
    ]);

    $response
        ->assertStatus(202)
        ->assertJsonPath('data.status', PromptOptimization::STATUS_QUEUED);

    Queue::assertPushed(ProcessPromptOptimization::class);
});
