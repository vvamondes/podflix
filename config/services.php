<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'google' => [
        'client_id' => env('LOGIN_GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('LOGIN_GOOGLE_CLIENT_SECRET', ''),
        'redirect' => env('LOGIN_GOOGLE_REDIRECT', ''),
    ],

    'facebook' => [
        'client_id' => env('LOGIN_FACEBOOK_CLIENT_ID', ''),
        'client_secret' => env('LOGIN_FACEBOOK_CLIENT_SECRET', ''),
        'redirect' => env('LOGIN_FACEBOOK_REDIRECT', ''),
    ],

    'twitter' => [
        'client_id' => env('LOGIN_TWITTER_CLIENT_ID', ''),
        'client_secret' => env('LOGIN_TWITTER_CLIENT_SECRET', ''),
        'redirect' => env('LOGIN_TWITTER_REDIRECT', ''),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
