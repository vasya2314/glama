<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-payment-qr')->everyMinute()->withoutOverlapping();
Schedule::command('app:check-payment-invoices')->hourly()->withoutOverlapping();
