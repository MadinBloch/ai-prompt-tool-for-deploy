<?php

namespace App\Jobs;

use App\Models\PromptOptimization;
use App\Services\PromptOptimization\PromptOptimizationService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessPromptOptimization implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public int $uniqueFor = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PromptOptimization $promptOptimization,
    ) {}

    /**
     * Get the unique job ID.
     */
    public function uniqueId(): string
    {
        return (string) $this->promptOptimization->getKey();
    }

    /**
     * Determine the backoff strategy for the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [5, 15, 30];
    }

    /**
     * Execute the job.
     */
    public function handle(PromptOptimizationService $service): void
    {
        $service->process($this->promptOptimization->fresh());
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        resolve(PromptOptimizationService::class)->markFailed(
            $this->promptOptimization->fresh(),
            $exception?->getMessage() ?? 'The prompt optimization job failed.',
        );
    }
}
