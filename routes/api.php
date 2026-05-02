<?php

use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;

Route::post('/prompt-optimizations', [AiController::class, 'optimizeApi'])
    ->name('api.prompt-optimizations.store');

Route::get('/prompt-optimizations/{promptOptimization}', [AiController::class, 'show'])
    ->name('api.prompt-optimizations.show');
