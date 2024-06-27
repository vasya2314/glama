<?php

use App\Http\Controllers\v1\Api\ContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::get('/contracts/{contract}', [ContractController::class, 'show']);
    Route::patch('/contracts/{contract}', [ContractController::class, 'update']);
//    Route::delete('/contracts/{contract}', [ContractController::class, 'delete']);
    Route::get('/contracts/{contract}/pdf', [ContractController::class, 'generatePdf']);
});
