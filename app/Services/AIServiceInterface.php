<?php

namespace App\Services;

interface AIServiceInterface
{
    /**
     * Generate text based on a prompt
     * @param string $prompt The user's prompt
     * @param int|null $panelCount Optional number of panels to generate
     * @return string The generated text
     */
    public function generateText(string $prompt, ?int $panelCount = null): string;

    /**
     * Generate an image based on a prompt and options.
     *
     * @param string $prompt
     * @param array $options Optional parameters for image generation
     * @return string URL or path to the generated image
     */
    public function generateImage(string $prompt, array $options = []): string;

    /**
     * Parse a comic script into individual panel descriptions
     * @param string $script The full script text
     * @return array Array of panel objects with description, dialogue, and order
     */
    public function parseScriptToPanels(string $script): array;

    /**
     * Enhance a user prompt to be more descriptive and structured
     * @param string $prompt The raw user prompt
     * @return string The enhanced prompt
     */
    public function enhancePromptText(string $prompt): string;
}
