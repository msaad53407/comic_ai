<?php

namespace App\Services\Providers;

use App\Services\AIServiceInterface;

class GeminiNanoService implements AIServiceInterface
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function generateText(string $prompt, ?int $panelCount = null): string
    {
        // Using Gemini 2.5 Flash for text generation
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        $panelInstruction = $panelCount
            ? "Create a comic script with EXACTLY {$panelCount} panels."
            : "Create a detailed comic script with 3 to 10 panels.";

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "{$panelInstruction} Story: {$prompt}. Include panel descriptions and dialogue. Format each panel starting with 'Panel X:'"]
                    ]
                ]
            ]
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                    "x-goog-api-key: {$this->apiKey}\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            error_log("Gemini API Error: Failed to fetch");
            return "Panel 1: {$prompt}\n(Generated offline)\nDialogue: 'This is a placeholder script.'";
        }

        $result = json_decode($response, true);

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        error_log("Gemini API: Unexpected response: " . substr($response, 0, 500));
        return "Panel 1: {$prompt}\n(Script generation in progress)";
    }

    public function generateImage(string $prompt, array $options = []): string
    {
        // Using Gemini 2.5 Flash Image (Nano Banana)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent";

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $streamOptions = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                    "x-goog-api-key: {$this->apiKey}\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true,
                'timeout' => 60
            ]
        ];

        $context = stream_context_create($streamOptions);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            error_log("Gemini 2.5 Flash Image Error: " . ($error['message'] ?? 'Unknown error'));
            return "https://placehold.co/800x450/333/FFF?text=" . urlencode("AI Generation Failed");
        }

        error_log("Gemini 2.5 Flash Image Response received, length: " . strlen($response));

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Gemini 2.5 Flash Image JSON Error: " . json_last_error_msg());
            return "https://placehold.co/800x450?text=" . urlencode("JSON Parse Error");
        }

        // Check for image data in the response
        // Response format: candidates[0].content.parts[] where parts can have inlineData.data
        if (isset($result['candidates'][0]['content']['parts'])) {
            foreach ($result['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['inlineData']['data'])) {
                    // Decode base64 image
                    $imageData = base64_decode($part['inlineData']['data']);
                    $filename = 'comic_' . time() . '_' . uniqid() . '.png';
                    $filepath = __DIR__ . '/../../../public/uploads/' . $filename;

                    // Ensure uploads directory exists
                    $uploadsDir = __DIR__ . '/../../../public/uploads';
                    if (!is_dir($uploadsDir)) {
                        mkdir($uploadsDir, 0755, true);
                    }

                    file_put_contents($filepath, $imageData);
                    error_log("Gemini 2.5 Flash Image: Successfully saved to " . $filename);

                    return '/uploads/' . $filename;
                }
            }
        }

        // Fallback to placeholder
        error_log("Gemini 2.5 Flash Image: No inlineData in response: " . substr(json_encode($result), 0, 500));
        return "https://placehold.co/800x450?text=" . urlencode(substr($prompt, 0, 30));
    }

    /**
     * Parse a comic script into individual panel descriptions
     * Returns an array of panel objects with description and dialogue
     * Maximum 10 panels
     */
    public function parseScriptToPanels(string $script): array
    {
        $panels = [];

        // Split by "Panel X:" pattern (case-insensitive)
        preg_match_all('/Panel\s+(\d+):\s*(.*?)(?=Panel\s+\d+:|$)/is', $script, $matches, PREG_SET_ORDER);

        foreach ($matches as $index => $match) {
            if ($index >= 10)
                break; // Max 10 panels

            $panelText = trim($match[2]);

            // Try to separate description and dialogue
            $description = $panelText;
            $dialogue = '';

            // Check if there's a "Dialogue:" section
            if (preg_match('/Dialogue:\s*(.+)/is', $panelText, $dialogueMatch)) {
                $dialogue = trim($dialogueMatch[1]);
                // Remove dialogue from description
                $description = trim(preg_replace('/Dialogue:\s*.+/is', '', $panelText));
            }

            $panels[] = [
                'order' => $index + 1,
                'description' => $description,
                'dialogue' => $dialogue,
                'full_text' => $panelText
            ];
        }

        // If no panels found with the pattern, create at least one panel
        if (empty($panels)) {
            $panels[] = [
                'order' => 1,
                'description' => $script,
                'dialogue' => '',
                'full_text' => $script
            ];
        }

        return $panels;
    }

    public function enhancePromptText(string $prompt): string
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        $systemInstruction = "You are a professional comic book writer. Your task is to take a simple user idea and expand it into a detailed, structured comic prompt. 
        Make it descriptive, visual, and engaging. Focus on setting the scene, character details, and mood. 
        Keep it concise enough for a generator but detailed enough for quality results. 
        Do not add 'Here is the enhanced prompt:' prefix, just return the prompt itself.";

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "{$systemInstruction}\n\nUser Input: {$prompt}\n\nEnhanced Prompt:"]
                    ]
                ]
            ]
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                    "x-goog-api-key: {$this->apiKey}\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            error_log("Gemini API Error (Enhance): Failed to fetch");
            return $prompt; // Fallback to original
        }

        $result = json_decode($response, true);

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($result['candidates'][0]['content']['parts'][0]['text']);
        }

        return $prompt;
    }
}
