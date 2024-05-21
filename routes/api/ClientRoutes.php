<?php

use App\Http\Controllers\v1\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);

    Route::get('/clients/{client}/update/campaigns-qty', [ClientController::class, 'updateCampaignsQty']); // NEW
});
