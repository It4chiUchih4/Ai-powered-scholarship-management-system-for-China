<?php

return [
    'sslcommerz' => [
        'sandbox' => env('SSL_COMMERZ_SANDBOX', true),
        'store_id' => env('SSL_COMMERZ_STORE_ID'),
        'store_password' => env('SSL_COMMERZ_STORE_PASSWORD'),
        'api_url' => env('SSL_COMMERZ_API_URL', 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'),
        'gateway_url' => env('SSL_COMMERZ_GATEWAY_URL', 'https://sandbox.sslcommerz.com/gwprocess/v4/gw.php'),
        'success_url' => env('SSL_COMMERZ_SUCCESS_URL'),
        'fail_url' => env('SSL_COMMERZ_FAIL_URL'),
        'cancel_url' => env('SSL_COMMERZ_CANCEL_URL'),
        'currency' => env('SSL_COMMERZ_CURRENCY', 'BDT'),
        'verify_ssl' => env('SSL_COMMERZ_VERIFY_SSL', true),
        'include_ipn' => env('SSL_COMMERZ_INCLUDE_IPN', true),
    ],
];
