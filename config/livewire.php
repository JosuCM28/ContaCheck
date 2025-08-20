<?php

return [
    'temporary_file_upload' => [
        'middleware' => [
            'web',
            \Illuminate\Routing\Middleware\ValidateSignature::class, 
        ],
    ],

    // Opcional: fija URLs si fuese necesario
    'url'       => env('LIVEWIRE_URL', null),
    'asset_url' => env('ASSET_URL', null),
];
