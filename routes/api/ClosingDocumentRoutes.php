<?php

use App\Http\Controllers\v1\Api\ClosingDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/closing-documents', [ClosingDocumentController::class, 'index']); // NEW

    Route::post('/user/closing-act/get', [ClosingDocumentController::class, 'getClosingAct']); // NEW
    Route::post('/user/closing-invoice/get', [ClosingDocumentController::class, 'getClosingInvoice']); // NEW
});
