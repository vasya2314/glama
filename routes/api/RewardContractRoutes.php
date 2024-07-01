<?php

use App\Http\Controllers\v1\Api\ContractController;
use App\Http\Controllers\v1\Api\RewardContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/reward-contracts', [RewardContractController::class, 'index']);
    Route::post('/reward-contracts', [RewardContractController::class, 'store']);
    Route::get('/reward-contracts/{rewardContract}', [RewardContractController::class, 'show']);
    Route::patch('/reward-contracts/{rewardContract}', [RewardContractController::class, 'update']);
//    Route::delete('/reward-contracts/{rewardContract}', [RewardContractController::class, 'delete']);
    Route::get('/reward-contracts/{rewardContract}/pdf', [RewardContractController::class, 'generatePdf']);
});
