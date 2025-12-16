<?php

namespace App\Services;

use App\Services\Providers\GeminiNanoService;
use Exception;

class AIProviderFactory
{
    public static function create(string $providerName): AIServiceInterface
    {
        $config = require __DIR__ . '/../../config/app.php';
        $apiKey = $config['api_keys'][$providerName] ?? null;

        switch ($providerName) {
            case 'gemini_nano':
                return new GeminiNanoService($apiKey);
            // Future providers can be added here
            // case 'openai':
            //     return new OpenAIService($apiKey);
            default:
                throw new Exception("AI Provider '{$providerName}' not supported.");
        }
    }
}
