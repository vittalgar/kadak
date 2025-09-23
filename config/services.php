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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'airtel' => [
        'username' => env('AIRTEL_API_USERNAME'),
        'password' => env('AIRTEL_API_PASSWORD'),
        'customer_id' => env('AIRTEL_CUSTOMER_ID'),
        'dlt_template_id' => env('AIRTEL_DLT_TEMPLATE_ID'),
        'entity_id' => env('AIRTEL_ENTITY_ID'),
        'message_type' => env('AIRTEL_MESSAGE_TYPE'),
        'sender_id' => env('AIRTEL_SENDER_ID'),
    ],

];
