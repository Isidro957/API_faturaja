<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Guard
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users', // landlord
    ],

    'tenant' => [
        'driver' => 'session',
        'provider' => 'tenant_users', // tenant users
    ],


    'sanctum' => [
        'driver' => 'sanctum',
        'provider' => 'tenant_users',
    ],

],


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],

    'tenant_users' => [
        'driver' => 'eloquent',
        'model' => App\Models\TenantUser::class,
    ],
],


    /*
    |--------------------------------------------------------------------------
    | Reset de Senha
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'tenant_users' => [
            'provider' => 'tenant_users',
            'table' => 'tenant_password_resets', // tabela específica do tenant
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
