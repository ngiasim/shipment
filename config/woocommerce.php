<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Home URL to the store you want to connect to here
    |--------------------------------------------------------------------------
    */
    'store_url' => env('WOOCOMMERCE_STORE_URL', 'http://localhost/wordpress/'),

    /*
    |--------------------------------------------------------------------------
    | Consumer Key
    |--------------------------------------------------------------------------
    */
    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY', 'ck_51c8c8d2c0eb635ba9108792ee7cb87d74cf1b62'),

    /*
    |--------------------------------------------------------------------------
    | Consumer Secret
    |--------------------------------------------------------------------------
    */
    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET', 'cs_5c42d7bfe0b00c7c4af9c1b57c3e35e39c906761'),

    /*
    |--------------------------------------------------------------------------
    | SSL support
    |--------------------------------------------------------------------------
    */
    'verify_ssl' => env('WOOCOMMERCE_VERIFY_SSL', false),

    /*
    |--------------------------------------------------------------------------
    | API version
    |--------------------------------------------------------------------------
    */
    'api_version' => env('WOOCOMMERCE_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | WP API usage
    |--------------------------------------------------------------------------
    */
    'wp_api' => env('WOOCOMMERCE_WP_API', true),

    /*
    |--------------------------------------------------------------------------
    | Force Basic Authentication as query string
    |--------------------------------------------------------------------------
    */
    'query_string_auth' => env('WOOCOMMERCE_WP_QUERY_STRING_AUTH', true),

    /*
    |--------------------------------------------------------------------------
    | WP timeout
    |--------------------------------------------------------------------------
    */
    'timeout' => env('WOOCOMMERCE_WP_TIMEOUT', 15),
];