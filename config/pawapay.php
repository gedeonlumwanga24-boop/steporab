<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PawaPay API Configuration
    |--------------------------------------------------------------------------
    | Documentation officielle : https://docs.pawapay.io/v2/
    */

    // URL de base de l'API (sandbox pour tests, production pour live)
    'base_url' => env('PAWAPAY_BASE_URL', 'https://api.sandbox.pawapay.io'),

    // JWT Token Bearer généré depuis le dashboard PawaPay
    'api_token' => env('PAWAPAY_API_TOKEN', ''),

    // Devise utilisée pour toutes les transactions (CDF = Franc Congolais)
    'currency' => env('PAWAPAY_CURRENCY', 'CDF'),

    // Pourcentage des frais de transaction INCLUS dans le prix affiché
    // (frais cachés au client, absorbés dans le total)
    'fee_percentage' => env('PAWAPAY_FEE_PERCENTAGE', 2.0),

    // Opérateurs Mobile Money actifs pour la RDC
    'providers' => [
        'VODACOM_MPESA_COD' => [
            'label'  => 'M-Pesa (Vodacom)',
            'prefix' => ['081', '082', '083', '084', '085'],
            'color'  => '#e31e24',
            'logo'   => 'mpesa',
        ],
        'AIRTEL_COD' => [
            'label'  => 'Airtel Money',
            'prefix' => ['097', '098', '099'],
            'color'  => '#ed1c24',
            'logo'   => 'airtel',
        ],
        'ORANGE_COD' => [
            'label'  => 'Orange Money',
            'prefix' => ['089', '090', '084'],
            'color'  => '#ff6600',
            'logo'   => 'orange',
        ],
    ],

    // URL vers laquelle PawaPay enverra le callback (webhook) après paiement
    // Cette URL doit être publiquement accessible (utiliser ngrok en local)
    'callback_url' => env('PAWAPAY_CALLBACK_URL', env('APP_URL') . '/webhooks/pawapay/callback'),
];
