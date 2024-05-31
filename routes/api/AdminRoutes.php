<?php

use App\Http\Controllers\v1\api\TicketController;
use App\Http\Controllers\v1\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::patch('/tickets/{ticket}/assigned-to', [TicketController::class, 'assignedTo']);

    Route::get('/users/{user}/transactions/{transaction}/confirm-transaction', [UserController::class, 'confirmTransaction']);
    Route::get('/users/{user}/transactions/{transaction}/reject-transaction', [UserController::class, 'rejectTransaction']);
});
