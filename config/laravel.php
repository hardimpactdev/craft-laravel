<?php

// config for hardimpactdev/craft-laravel
return [

    /*
    |--------------------------------------------------------------------------
    | Strict Defaults
    |--------------------------------------------------------------------------
    |
    | These defaults enforce stricter runtime behaviour across your application.
    | Each toggle can be disabled individually if you have a reason to opt out.
    |
    */

    'defaults' => [
        'strict_models' => true,
        'auto_eager_load' => true,
        'immutable_dates' => true,
        'force_https' => true,
        'prohibit_destructive_commands' => true,
        'aggressive_prefetching' => true,
        'prevent_stray_requests' => true,
        'fake_sleep' => true,
        'default_password_rules' => true,
    ],

];
