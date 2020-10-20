<?php

use CoreProc\PayMaya\PayMayaClient;

return [

    'environment' => env('PAYMAYA_ENVIRONMENT', PayMayaClient::ENVIRONMENT_SANDBOX),

    'key' => env('PAYMAYA_KEY'),

    'secret' => env('PAYMAYA_SECRET'),

    'webhooks' => [

        'checkout_success' => env('PAYMAYA_WEBHOOKS_CHECKOUT_SUCCESS'),

        'checkout_failure' => env('PAYMAYA_WEBHOOKS_CHECKOUT_FAILURE'),

        'checkout_dropout' => env('PAYMAYA_WEBHOOKS_CHECKOUT_DROPOUT'),

        'payment_success' => env('PAYMAYA_WEBHOOKS_PAYMENT_SUCCESS'),

        'payment_failed' => env('PAYMAYA_WEBHOOKS_PAYMENT_FAILED'),

        'payment_expired' => env('PAYMAYA_WEBHOOKS_PAYMENT_EXPIRED'),

    ],

];
