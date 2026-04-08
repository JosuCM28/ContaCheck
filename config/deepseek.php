<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeepSeek API Key
    |--------------------------------------------------------------------------
    | Tu clave de API de DeepSeek. Se lee desde el .env como DEEPSEEK_API_KEY.
    */
    'api_key' => env('DEEPSEEK_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | DeepSeek Base URI
    |--------------------------------------------------------------------------
    | URL base de la API. DeepSeek es compatible con OpenAI, por eso usamos
    | el cliente openai-php apuntando a este endpoint.
    */
    'base_uri' => env('DEEPSEEK_BASE_URI', 'api.deepseek.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Modelo por defecto
    |--------------------------------------------------------------------------
    | deepseek-chat     → DeepSeek-V3 (modo estándar, más rápido)
    | deepseek-reasoner → DeepSeek-V3 (modo pensamiento, más preciso)
    */
    'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),

    /*
    |--------------------------------------------------------------------------
    | Timeout de las peticiones (segundos)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('DEEPSEEK_TIMEOUT', 60),

];
