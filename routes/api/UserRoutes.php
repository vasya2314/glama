<?php

use App\Http\Controllers\v1\Api\InvoiceDepositController;
use App\Http\Controllers\v1\Api\PaymentDepositController;
use App\Http\Controllers\v1\Api\UserController;
use App\Http\Controllers\v1\Api\YandexDirectPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::patch('/user/update-user', [UserController::class, 'update']);
    Route::patch('/user/change-password', [UserController::class, 'changePassword']);
    Route::get('/user/detail', [UserController::class, 'detail']);
    Route::post('/user/deposit/payment', [PaymentDepositController::class, 'deposit']);
    Route::middleware('throttle:4,0.16')->post('/user/deposit/invoice', [InvoiceDepositController::class, 'deposit']);
    Route::post('/user/yandex-direct/deposit', [YandexDirectPaymentController::class, 'deposit']);
    Route::get('/user/balance/{balanceType}', [UserController::class, 'getBalance']); // NEW
    Route::post('/user/withdrawal-money', [UserController::class, 'withdrawalMoney']); // ВЫВОД СРЕДСТВ // NEW

    Route::post('/user/closing-acts/pdf', [UserController::class, 'generateClosingActPdf']);
});

Route::middleware(['auth:sanctum', 'verified', 'agencyUser'])->group(function () {
    Route::get('/agency/users/child-users', [UserController::class, 'allUsers']); // NEW
    Route::post('/agency/users/create-user', [UserController::class, 'createUser']); // NEW
    Route::get('/agency/users/{user}/attach-user', [UserController::class, 'attachUser']); // NEW
    Route::post('/agency/user/login', [UserController::class, 'agencyLogin']); // NEW
});

Route::get('/users/confirm-attach-to-agency/{user}/{agencyUser}', [UserController::class, 'confirmAttachToAgency'])->name('confirm-attach-to-agency'); // NEW
