<?php

return [
    'twitter' => [
        'consumerKey' => function_exists('env') ? env('TWITTER_CONSUMER_KEY', '') : '',
        'consumerSecret' => function_exists('env') ? env('TWITTER_CONSUMER_SECRET', '') : '',
        'accessToken' => function_exists('env') ? env('TWITTER_ACCESS_TOKEN', '') : '',
        'accessTokenSecret' => function_exists('env') ? env('TWITTER_ACCESS_TOKEN_SECRET', '') : ''
    ]
];