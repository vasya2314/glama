<?php

return [
    'api_url' => env('TINKOFF_SECURE_PAY', ''),
    'terminal' => env('TINKOFF_TERMINAL', ''),
    'secret_key' => env('TINKOFF_SECRET_KEY', ''),
    'token' => env('TINKOFF_TOKEN', ''),
];
