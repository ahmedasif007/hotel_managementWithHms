<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Hotel Management System'),
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', false),
        'url' => env('APP_URL', 'http://localhost'),
        'timezone' => env('APP_TIMEZONE', 'UTC'),
    ],
    'database' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'hotel_hms'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ],
    ],
    'cache' => [
        'default' => env('CACHE_DRIVER', 'file'),
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('framework/cache/data'),
            ],
        ],
    ],
    'mail' => [
        'driver' => env('MAIL_DRIVER', 'log'),
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@hotel-hms.local'),
            'name' => env('MAIL_FROM_NAME', 'Hotel Management System'),
        ],
    ],
];
