<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ЮKassa Configuration
    |--------------------------------------------------------------------------
    |
    | Конфигурация для интеграции с платежной системой ЮKassa
    |
    */

    // Shop ID (идентификатор магазина)
    'shop_id' => env('YOOKASSA_SHOP_ID', ''),

    // Secret Key (секретный ключ)
    'secret_key' => env('YOOKASSA_SECRET_KEY', ''),

    // Режим работы (test/production)
    'mode' => env('YOOKASSA_MODE', 'test'),

    // Валюта по умолчанию
    'currency' => env('YOOKASSA_CURRENCY', 'RUB'),

    // Настройки уведомлений
    'notifications' => [
        'webhook_url' => env('YOOKASSA_WEBHOOK_URL', '/api/yookassa/webhook'),
        'enabled' => env('YOOKASSA_WEBHOOK_ENABLED', true),
    ],

    // Настройки возвратов
    'refunds' => [
        'enabled' => env('YOOKASSA_REFUNDS_ENABLED', true),
    ],

    // Настройки для тестирования
    'test' => [
        'shop_id' => env('YOOKASSA_TEST_SHOP_ID', ''),
        'secret_key' => env('YOOKASSA_TEST_SECRET_KEY', ''),
    ],
]; 