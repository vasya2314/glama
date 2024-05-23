<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-payment-qr')->everyMinute()->withoutOverlapping(); // Проверка оплаты по QR-коду
Schedule::command('app:check-payment-invoices')->hourly()->withoutOverlapping(); // Проверка оплаты по счету

Schedule::command('app:check-client-campaigns-qty')->daily()->withoutOverlapping(); // Обновление количества компаний у агенстких клиентов
Schedule::command('app:check-shared-yandex-direct-account')->everyFiveMinutes()->withoutOverlapping(); // Проверка подключен ли общий счет
