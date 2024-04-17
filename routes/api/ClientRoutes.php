<?php

use App\Http\Controllers\v1\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/clients', [ClientController::class, 'store']);
    Route::post('/clients/all', [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);
});
