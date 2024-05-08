<?php

use App\Http\Controllers\v1\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/tinkoff/notify', [PaymentController::class, 'notify']);
