<?php
return [
    // the client environment
    'env' => env('DORCAS_ENV', 'production'),

    'url' => env('DORCAS_BASE_URL', 'https://api.dorcas.ng'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | You need to provide the credentials that will be used while communicating
    | with the Dorcas API.
    |
    |
    */
    'client' => [

        // the client ID provided to you for use with your app
        'id' => env('DORCAS_CLIENT_ID', 0),

        // the client secret
        'secret' => env('DORCAS_CLIENT_SECRET', '')
    ]
];
