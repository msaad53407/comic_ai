<?php

namespace App\Controllers;

use App\Models\ComicModel;
use App\Services\AIProviderFactory;
use App\Core\AuthMiddleware;

class ComicController extends BaseController
{
    private $comicModel;

    public function __construct()
    {
        $this->comicModel = new ComicModel();
    }

    public function index()
    {
        AuthMiddleware::handle();

        // Fetch recent comics for history sidebar
        $userId = $_SESSION['user_id'];
        $recentComics = $this->comicModel->getAllByUserId($userId);

        // Fetch first panel for each comic as thumbnail
        $panelModel = new \App\Models\PanelModel();
        foreach ($recentComics as &$comic) {
            $panels = $panelModel->getByComicId($comic['id']);
            $comic['first_panel'] = !empty($panels) ? $panels[0]['image_path'] : null;
        }

        $this->view('home/index', ['recentComics' => $recentComics]);
    }

    public function generate()
    {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed']);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $prompt = $input['prompt'] ?? '';
        $style = $input['style'] ?? 'Modern';
        $layout = $input['layout'] ?? '3-panel';
        $panelCount = isset($input['panel_count']) && is_numeric($input['panel_count']) ? (int) $input['panel_count'] : null;

        // Validate panel count if provided
        if ($panelCount !== null) {
            if ($panelCount < 1)
                $panelCount = 1;
            if ($panelCount > 10)
                $panelCount = 10;
        }

        if (empty($prompt)) {
            return $this->json(['error' => 'Prompt is required']);
        }

        try {
            // Get configured AI provider
            $config = require __DIR__ . '/../../config/app.php';
            $aiService = AIProviderFactory::create($config['ai_provider']);

            // Step 1: Generate Story/Script
            $script = $aiService->generateText($prompt, $panelCount);

            // Step 2: Parse script into individual panels (max 10)
            $panelDescriptions = $aiService->parseScriptToPanels($script);
            $panelCount = count($panelDescriptions);

            // Step 3: Create comic record first
            $userId = $_SESSION['user_id'];
            $comicId = $this->comicModel->create($userId, $prompt, $style, $layout, $script, $panelCount);

            // Step 4: Generate image for each panel and save to database
            $panelModel = new \App\Models\PanelModel();
            $panels = [];

            foreach ($panelDescriptions as $panelData) {
                // Create image prompt combining style and panel description
                // Enhanced for consistency across panels
                $imagePrompt = $this->buildPanelPrompt($style, $panelData, $prompt, $panelData['order'], $panelCount);

                // Generate image for this panel
                $imageUrl = $aiService->generateImage($imagePrompt);

                // Save panel to database
                $panelId = $panelModel->create(
                    $comicId,
                    $imageUrl,
                    $panelData['full_text'],
                    $panelData['order']
                );

                $panels[] = [
                    'id' => $panelId,
                    'image_url' => $imageUrl,
                    'dialogue' => $panelData['full_text'],
                    'order' => $panelData['order']
                ];
            }

            return $this->json([
                'success' => true,
                'comic_id' => $comicId,
                'panels' => $panels,
                'script' => $script,
                'panel_count' => $panelCount
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    // New endpoint: Generate script only (Step 1 of progressive generation)
    public function generateScript()
    {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed']);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $prompt = $input['prompt'] ?? '';
        $style = $input['style'] ?? 'Modern';
        $layout = $input['layout'] ?? '3-panel';
        $panelCount = isset($input['panel_count']) && is_numeric($input['panel_count']) ? (int) $input['panel_count'] : null;

        // Validate panel count if provided
        if ($panelCount !== null) {
            if ($panelCount < 1)
                $panelCount = 1;
            if ($panelCount > 10)
                $panelCount = 10;
        }

        if (empty($prompt)) {
            return $this->json(['error' => 'Prompt is required']);
        }

        try {
            $config = require __DIR__ . '/../../config/app.php';
            $aiService = AIProviderFactory::create($config['ai_provider']);

            // Generate script
            $script = $aiService->generateText($prompt, $panelCount);

            // Parse into panels
            $panelDescriptions = $aiService->parseScriptToPanels($script);
            $panelCount = count($panelDescriptions);

            // Create comic record
            $userId = $_SESSION['user_id'];
            $comicId = $this->comicModel->create($userId, $prompt, $style, $layout, $script, $panelCount);

            return $this->json([
                'success' => true,
                'comic_id' => $comicId,
                'script' => $script,
                'panel_count' => $panelCount,
                'panel_descriptions' => $panelDescriptions
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    // New endpoint: Generate single panel (Step 2 of progressive generation)
    public function generatePanel()
    {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed']);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $comicId = $input['comic_id'] ?? '';
        $panelData = $input['panel_data'] ?? [];
        $style = $input['style'] ?? 'Modern';
        $storyPrompt = $input['story_prompt'] ?? '';
        $totalPanels = $input['total_panels'] ?? 1;

        if (empty($comicId) || empty($panelData)) {
            return $this->json(['error' => 'Comic ID and panel data required']);
        }

        try {
            $config = require __DIR__ . '/../../config/app.php';
            $aiService = AIProviderFactory::create($config['ai_provider']);

            // Build enhanced prompt for consistency
            $imagePrompt = $this->buildPanelPrompt($style, $panelData, $storyPrompt, $panelData['order'], $totalPanels);

            // Generate image
            $imageUrl = $aiService->generateImage($imagePrompt);

            // Save panel
            $panelModel = new \App\Models\PanelModel();
            $panelId = $panelModel->create(
                $comicId,
                $imageUrl,
                $panelData['full_text'],
                $panelData['order']
            );

            return $this->json([
                'success' => true,
                'panel' => [
                    'id' => $panelId,
                    'image_url' => $imageUrl,
                    'dialogue' => $panelData['full_text'],
                    'order' => $panelData['order']
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    // Helper method to build consistent panel prompts
    private function buildPanelPrompt($style, $panelData, $storyPrompt, $panelOrder, $totalPanels)
    {
        $prompt = "Create a comic panel in consistent {$style} style. ";
        $prompt .= "This is panel {$panelOrder} of {$totalPanels} in a comic story. ";
        $prompt .= "Story context: {$storyPrompt}. ";
        $prompt .= "\n\nPanel {$panelOrder} scene: {$panelData['description']}";

        if (!empty($panelData['dialogue'])) {
            $prompt .= "\nDialogue: {$panelData['dialogue']}";
        }

        $prompt .= "\n\nIMPORTANT: Maintain consistent art style, character appearance, and visual aesthetic throughout. ";
        $prompt .= "Use the same artistic techniques, color palette, and character designs as other panels in this {$style} comic.";

        return $prompt;
    }

    public function enhancePrompt()
    {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed']);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $prompt = $input['prompt'] ?? '';

        if (empty($prompt)) {
            return $this->json(['error' => 'Prompt is required']);
        }

        try {
            $config = require __DIR__ . '/../../config/app.php';
            $aiService = AIProviderFactory::create($config['ai_provider']);

            $enhancedPrompt = $aiService->enhancePromptText($prompt);

            return $this->json([
                'success' => true,
                'enhanced_prompt' => $enhancedPrompt
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    public function history()
    {
        AuthMiddleware::handle();

        $userId = $_SESSION['user_id'];
        $comics = $this->comicModel->getAllByUserId($userId);

        // Fetch first panel for each comic as thumbnail
        $panelModel = new \App\Models\PanelModel();
        foreach ($comics as &$comic) {
            $panels = $panelModel->getByComicId($comic['id']);
            $comic['first_panel'] = !empty($panels) ? $panels[0]['image_path'] : null;
        }

        $this->view('home/history', ['comics' => $comics]);
    }

    public function detail($id)
    {
        AuthMiddleware::handle();

        $userId = $_SESSION['user_id'];
        $comic = $this->comicModel->getById($id);

        // Verify ownership
        if (!$comic || $comic['user_id'] != $userId) {
            header('Location: /history');
            exit;
        }

        // Get panels for this comic
        $panelModel = new \App\Models\PanelModel();
        $panels = $panelModel->getByComicId($id);

        // Get recent comics for sidebar
        $recentComics = $this->comicModel->getAllByUserId($userId);

        // Fetch first panel for each comic as thumbnail
        foreach ($recentComics as &$recentComic) {
            $recentPanels = $panelModel->getByComicId($recentComic['id']);
            $recentComic['first_panel'] = !empty($recentPanels) ? $recentPanels[0]['image_path'] : null;
        }

        $this->view('home/detail', [
            'comic' => $comic,
            'panels' => $panels,
            'recentComics' => $recentComics
        ]);
    }
}
