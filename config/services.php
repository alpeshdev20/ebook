<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/login/google/callback',
    ],

    // 'paytm-wallet' => [
    //     'env' => 'production', // values : (local | production)
    //     'merchant_id' => env('PAYTM_MERCHANT_ID'),
    //     'merchant_key' => env('PAYTM_MERCHANT_KEY'),
    //     'merchant_website' => env('PAYTM_MERCHANT_WEBSITE'),
    //     'channel' => env('PAYTM_CHANNEL'),
    //     'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    // ],

    'paytm-wallet' => [
        'env' => 'production', // values : (local | production)
        'merchant_id' => env('YOUR_MERCHANT_ID'),
        'merchant_key' => env('YOUR_MERCHANT_KEY'),
        'merchant_website' => env('YOUR_WEBSITE'),
        'channel' => env('YOUR_CHANNEL'),
        'industry_type' => env('YOUR_INDUSTRY_TYPE'),
    ],



];
