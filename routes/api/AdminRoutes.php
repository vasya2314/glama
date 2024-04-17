<?php

use App\Http\Controllers\v1\api\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::patch('/tickets/{ticket}/assigned-to', [TicketController::class, 'assignedTo']);
});
