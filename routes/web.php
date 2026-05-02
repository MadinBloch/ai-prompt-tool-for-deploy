<?php

use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AiController::class, 'index'])->name('prompt-optimizer.index');
Route::post('/optimize', [AiController::class, 'optimize'])->name('prompt-optimizer.optimize');
Route::get('/upload-file', [AiController::class, 'uploadFile'])->name('prompt-optimizer.upload-file');
Route::post('/upload', [AiController::class, 'upload'])->name('upload.file');
