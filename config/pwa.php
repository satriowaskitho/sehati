<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Would you like the install button to appear on all pages? Set true/false
    |--------------------------------------------------------------------------
    */

    'install-button' => true,

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    |  php artisan erag:pwa-update-manifest
    */

    'manifest' => [
        'name' => 'Sehati',
        'short_name' => 'Sehati',
        'background_color' => '#febd25 ',
        'display' => 'fullscreen',
        'description' => 'Sehati',
        'theme_color' => '#febd25',
        'icons' => [
            [
                'src' => 'logo_sehati_nobg.png.png',
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Configuration
    |--------------------------------------------------------------------------
    | Toggles the application's debug mode based on the environment variable
    */

    'debug' => env('APP_DEBUG', false),

];
