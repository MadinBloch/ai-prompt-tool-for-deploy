<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prompt_optimizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('fingerprint', 64)->index();
            $table->string('requested_provider')->nullable();
            $table->string('provider_used')->nullable();
            $table->string('model_used')->nullable();
            $table->json('provider_chain');
            $table->longText('source_prompt');
            $table->longText('optimized_prompt')->nullable();
            $table->text('context_summary')->nullable();
            $table->json('improvements')->nullable();
            $table->unsignedInteger('estimated_tokens_before')->default(0);
            $table->unsignedInteger('estimated_tokens_after')->nullable();
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('cache_write_input_tokens')->default(0);
            $table->unsignedInteger('cache_read_input_tokens')->default(0);
            $table->unsignedInteger('reasoning_tokens')->default(0);
            $table->string('cache_key', 80)->index();
            $table->boolean('cached_response')->default(false);
            $table->boolean('enhance_context')->default(true);
            $table->string('status', 20)->index();
            $table->text('error_message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_optimizations');
    }
};
