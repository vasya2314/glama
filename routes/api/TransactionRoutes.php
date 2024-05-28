<?php

use App\Http\Controllers\v1\Api\InvoiceDepositController;
use App\Http\Controllers\v1\Api\PaymentDepositController;
use App\Http\Controllers\v1\Api\TransactionController;
use App\Http\Controllers\v1\Api\UserController;
use App\Http\Controllers\v1\Api\YandexDirectPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index']);
});
