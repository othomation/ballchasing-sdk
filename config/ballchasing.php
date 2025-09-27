<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ballchasing API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Ballchasing.com API integration.
    | You will need to register your application on Ballchasing.com to get
    | your API key.
    |
    */

    'api_key' => env('BALLCHASING_API_KEY'),

    'base_url' => env('BALLCHASING_BASE_URL', 'https://ballchasing.com/api'),

    'timeout' => env('BALLCHASING_TIMEOUT', 30),

    'retry_attempts' => env('BALLCHASING_RETRY_ATTEMPTS', 3),

    'retry_delay' => env('BALLCHASING_RETRY_DELAY', 500),
];
