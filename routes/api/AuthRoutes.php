<?php

use App\Http\Controllers\v1\Api\Auth\EmailVerificationController;
use App\Http\Controllers\v1\Api\Auth\LoginController;
use App\Http\Controllers\v1\Api\Auth\PasswordController;
use App\Http\Controllers\v1\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/forgot-password', [PasswordController::class, 'sendEmail'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/email/verification-notification', [EmailVerificationController::class, 'resend']);
    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
});
