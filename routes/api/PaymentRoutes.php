<?php

use App\Http\Controllers\v1\Api\PaymentDepositController;
use Illuminate\Support\Facades\Route;

Route::post('/tinkoff/notify', [PaymentDepositController::class, 'notify']);
