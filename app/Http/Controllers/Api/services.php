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

    'moonshot' => [
        'api_key' => env('MOONSHOT_API_KEY'),
        'model'   => env('MOONSHOT_MODEL', 'moonshot-v1-8k'),
    ],

    'openai' => [
        'api_key'      => env('OPENAI_API_KEY', ''),
        'vision_model' => env('OPENAI_VISION_MODEL', 'gpt-4o'),
    ],

    'stripe' => [
        'key'            => env('STRIPE_PUBLISHABLE_KEY', ''),
        'secret'         => env('STRIPE_SECRET_KEY', ''),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
    ],

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID', ''),
        'api_key'    => env('FIREBASE_API_KEY', ''),
    ],

];
