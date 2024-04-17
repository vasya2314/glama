<?php

use App\Http\Controllers\v1\api\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/change-status', [TicketController::class, 'changeStatus']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'delete']);
});
