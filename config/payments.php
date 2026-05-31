<?php

return [

    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    'gateways' => [
        'stripe' => [
            'driver' => \App\Payments\Drivers\StripeDriver::class,
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
        ],
        'paypal' => [
            'driver' => \App\Payments\Drivers\PayPalDriver::class,
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
        'orange_money' => [
            'driver' => \App\Payments\Drivers\OrangeMoneyDriver::class,
            'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID'),
            'api_key' => env('ORANGE_MONEY_API_KEY'),
        ],
    ],

];
