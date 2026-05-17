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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),
        'snap_bi' => [
            'client_id' => env('MIDTRANS_SNAP_BI_CLIENT_ID'),
            'private_key' => env('MIDTRANS_SNAP_BI_PRIVATE_KEY'),
            'client_secret' => env('MIDTRANS_SNAP_BI_CLIENT_SECRET'),
            'partner_id' => env('MIDTRANS_SNAP_BI_PARTNER_ID') ?: env('MIDTRANS_SNAP_BI_CLIENT_ID'),
            'channel_id' => env('MIDTRANS_SNAP_BI_CHANNEL_ID') ?: '12345',
            'merchant_id' => env('MIDTRANS_SNAP_BI_MERCHANT_ID', env('MIDTRANS_MERCHANT_ID')),
            'public_key' => env('MIDTRANS_SNAP_BI_PUBLIC_KEY'),
        ],
    ],

];
