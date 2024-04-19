<?php

use App\Http\Controllers\v1\Api\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/tickets/{ticket}/messages', [MessageController::class, 'index']);
    Route::post('/tickets/{ticket}/messages', [MessageController::class, 'store']);
    Route::patch('/messages/{message}', [MessageController::class, 'update']);
    Route::delete('/messages/{message}', [MessageController::class, 'delete']);
});
