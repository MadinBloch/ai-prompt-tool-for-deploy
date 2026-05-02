<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptOptimization extends Model
{
    /** @use HasFactory<\Database\Factories\PromptOptimizationFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'fingerprint',
        'requested_provider',
        'provider_used',
        'model_used',
        'provider_chain',
        'source_prompt',
        'optimized_prompt',
        'context_summary',
        'improvements',
        'estimated_tokens_before',
        'estimated_tokens_after',
        'prompt_tokens',
        'completion_tokens',
        'cache_write_input_tokens',
        'cache_read_input_tokens',
        'reasoning_tokens',
        'cache_key',
        'cached_response',
        'enhance_context',
        'status',
        'error_message',
        'meta',
        'queued_at',
        'processed_at',
    ];

    /**
     * Get the model's attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'provider_chain' => 'array',
            'improvements' => 'array',
            'meta' => 'array',
            'cached_response' => 'boolean',
            'enhance_context' => 'boolean',
            'queued_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    /**
     * Determine if the optimization has been completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Determine if the optimization has failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Get the owning user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
