<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptimizePromptRequest;
use App\Jobs\ProcessPromptOptimization;
use App\Models\PromptOptimization;
use App\Services\PromptOptimization\PromptOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Throwable;

class AiController extends Controller
{
    /**
     * Show the prompt optimizer page.
     */
    public function index(): View
    {
        $latestOptimizations = collect();

        try {
            if (Schema::hasTable('prompt_optimizations')) {
                $latestOptimizations = PromptOptimization::query()
                    ->latest()
                    ->limit(5)
                    ->get();
            }
        } catch (Throwable) {
            $latestOptimizations = collect();
        }

        return view('welcome', [
            'latestOptimizations' => $latestOptimizations,
        ]);
    }

    /**
     * Optimize a prompt from the web UI.
     */
    public function optimize(
        OptimizePromptRequest $request,
        PromptOptimizationService $service,
    ): RedirectResponse {
        $optimization = $service->create([
            ...$request->validated(),
            'status' => $request->boolean('async')
                ? PromptOptimization::STATUS_QUEUED
                : PromptOptimization::STATUS_PENDING,
        ]);

        if ($request->boolean('async')) {
            ProcessPromptOptimization::dispatch($optimization);

            return back()->with('optimization_status', [
                'id' => $optimization->id,
                'status' => $optimization->status,
                'message' => 'Prompt optimization queued.',
            ]);
        }

        $result = $service->process($optimization);

        return back()->with('optimization_result', [
            'id' => $optimization->id,
            ...$result->toArray(),
        ]);
    }

    /**
     * Optimize a prompt over the API.
     */
    public function optimizeApi(
        OptimizePromptRequest $request,
        PromptOptimizationService $service,
    ): JsonResponse {
        $optimization = $service->create([
            ...$request->validated(),
            'status' => $request->boolean('async')
                ? PromptOptimization::STATUS_QUEUED
                : PromptOptimization::STATUS_PENDING,
        ]);

        if ($request->boolean('async')) {
            ProcessPromptOptimization::dispatch($optimization);

            return response()->json([
                'data' => [
                    'id' => $optimization->id,
                    'status' => $optimization->status,
                ],
            ], 202);
        }

        $result = $service->process($optimization);

        return response()->json([
            'data' => [
                'id' => $optimization->id,
                'status' => PromptOptimization::STATUS_COMPLETED,
                ...$result->toArray(),
            ],
        ]);
    }

    /**
     * Show a prompt optimization result.
     */
    public function show(PromptOptimization $promptOptimization): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $promptOptimization->id,
                'status' => $promptOptimization->status,
                'requested_provider' => $promptOptimization->requested_provider,
                'provider_used' => $promptOptimization->provider_used,
                'model_used' => $promptOptimization->model_used,
                'source_prompt' => $promptOptimization->source_prompt,
                'optimized_prompt' => $promptOptimization->optimized_prompt,
                'context_summary' => $promptOptimization->context_summary,
                'improvements' => $promptOptimization->improvements,
                'estimated_tokens_before' => $promptOptimization->estimated_tokens_before,
                'estimated_tokens_after' => $promptOptimization->estimated_tokens_after,
                'cached_response' => $promptOptimization->cached_response,
                'meta' => $promptOptimization->meta,
                'error_message' => $promptOptimization->error_message,
                'queued_at' => $promptOptimization->queued_at,
                'processed_at' => $promptOptimization->processed_at,
            ],
        ]);
    }

    public function uploadFile(){
        return view('upload-file');
    }



    public function upload(Request $request)
    {
        
        $file = $request->file('file');
    

        // store in S3
        $path = Storage::disk('s3')->put('uploads', $request->file('file'));

        // get URL
        $url = Storage::disk('s3')->url($path);

        return back()->with('url', $url);
    }
}
