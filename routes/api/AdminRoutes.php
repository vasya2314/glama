<?php

use App\Http\Controllers\v1\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::patch('/admin/tickets/{ticket}/assigned-to', [AdminController::class, 'assignedTo']); // NEW
    Route::get('/admin/users/{user}/transactions/{transaction}/confirm-transaction', [AdminController::class, 'confirmTransaction']); // NEW
    Route::get('/admin/users/{user}/transactions/{transaction}/reject-transaction', [AdminController::class, 'rejectTransaction']); // NEW
    Route::post('/admin/users/agency-user/create', [AdminController::class, 'createAgencyUser']); // NEW
});
