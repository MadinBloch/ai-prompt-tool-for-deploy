<?php

namespace App\Ai\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Ai\Prompts\AgentPrompt;
use Laravel\Ai\Responses\AgentResponse;

class CapturePromptTelemetry
{
    /**
     * Handle the incoming prompt.
     */
    public function handle(AgentPrompt $prompt, Closure $next)
    {
        return $next($prompt)->then(function (AgentResponse $response) use ($prompt) {
            Log::debug('AI prompt processed', [
                'agent' => class_basename($prompt->agent),
                'provider' => $response->meta->provider,
                'model' => $response->meta->model,
                'prompt_preview' => Str::limit($prompt->prompt, 120),
                'prompt_length' => mb_strlen($prompt->prompt),
                'usage' => $response->usage->toArray(),
            ]);
        });
    }
}
