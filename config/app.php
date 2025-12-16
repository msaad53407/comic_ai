<?php

use App\Core\Env;

return [
    'app_name' => 'ComicAI',
    'base_url' => Env::get('BASE_URL', 'http://localhost:8000'),
    'ai_provider' => 'gemini_nano',
    'api_keys' => [
        'gemini_nano' => Env::get('GEMINI_API_KEY'),
    ],
];
