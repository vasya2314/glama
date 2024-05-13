<?php

use App\Http\Controllers\v1\Api\PaymentController;
use App\Http\Controllers\v1\Api\UserController;
use App\Http\Controllers\v1\Api\YandexDirectPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::patch('/user/update-user', [UserController::class, 'update']);
    Route::patch('/user/change-password', [UserController::class, 'changePassword']);
    Route::get('/user/detail', [UserController::class, 'detail']);
    Route::post('/user/deposit', [PaymentController::class, 'deposit']);

    Route::post('/user/yandex-direct/deposit', [YandexDirectPaymentController::class, 'deposit']);
});
