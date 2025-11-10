<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OllamaImageService
{
    private string $ollamaUrl;
    private string $model;
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->ollamaUrl = config('services.ollama.url', 'http://localhost:11434');
        $this->model = config('services.ollama.model', 'llava:latest');
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Analyser l'image avec Ollama pour extraire les caractéristiques physiques
     */
    public function analyzeImage(string $imagePath): array
    {
        $imageData = base64_encode(file_get_contents($imagePath));

        $prompt = "Analyze this person's physical characteristics in detail. Provide ONLY the following information in this exact format:\n\n"
                . "SKIN_TONE: [very pale/pale/light/medium/tan/brown/dark brown/very dark]\n"
                . "HAIR_COLOR: [blonde/light brown/brown/dark brown/black/red/gray/white/other]\n"
                . "HAIR_LENGTH: [very short/short/medium/long/very long]\n"
                . "HAIR_STYLE: [straight/wavy/curly/braided/other]\n"
                . "EYE_COLOR: [blue/green/brown/hazel/gray/black]\n"
                . "GENDER: [male/female/neutral]\n"
                . "AGE_RANGE: [child/teen/young adult/adult/senior]\n"
                . "BODY_TYPE: [slim/average/athletic/heavy]\n"
                . "CLOTHING_COLOR_1: [main clothing color]\n"
                . "CLOTHING_COLOR_2: [secondary clothing color]\n"
                . "ACCESSORIES: [glasses/earrings/necklace/hat/none]\n\n"
                . "Be precise and use ONLY the exact format above.";

        try {
            $response = Http::timeout(90)->post("{$this->ollamaUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => $prompt,
                'images' => [$imageData],
                'stream' => false,
            ]);

            if ($response->successful()) {
                $analysisText = $response->json('response');
                
                // Parser la réponse pour extraire les caractéristiques
                $characteristics = $this->parseCharacteristics($analysisText);
                
                return [
                    'success' => true,
                    'analysis' => $analysisText,
                    'characteristics' => $characteristics,
                    'model' => $this->model,
                ];
            }

            return [
                'success' => false,
                'error' => 'Ollama API error: ' . $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Ollama analyze error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parser les caractéristiques depuis la réponse Ollama
     */
    private function parseCharacteristics(string $text): array
    {
        $characteristics = [
            'skin_tone' => 'medium',
            'hair_color' => 'brown',
            'hair_length' => 'medium',
            'hair_style' => 'straight',
            'eye_color' => 'brown',
            'gender' => 'neutral',
            'age_range' => 'adult',
            'body_type' => 'average',
            'clothing_color_1' => '#808080',
            'clothing_color_2' => '#ffffff',
            'accessories' => [],
        ];

        // Parser chaque ligne
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            if (preg_match('/SKIN_TONE:\s*(.+)/i', $line, $matches)) {
                $characteristics['skin_tone'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/HAIR_COLOR:\s*(.+)/i', $line, $matches)) {
                $characteristics['hair_color'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/HAIR_LENGTH:\s*(.+)/i', $line, $matches)) {
                $characteristics['hair_length'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/HAIR_STYLE:\s*(.+)/i', $line, $matches)) {
                $characteristics['hair_style'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/EYE_COLOR:\s*(.+)/i', $line, $matches)) {
                $characteristics['eye_color'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/GENDER:\s*(.+)/i', $line, $matches)) {
                $characteristics['gender'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/AGE_RANGE:\s*(.+)/i', $line, $matches)) {
                $characteristics['age_range'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/BODY_TYPE:\s*(.+)/i', $line, $matches)) {
                $characteristics['body_type'] = trim(strtolower($matches[1]));
            } elseif (preg_match('/CLOTHING_COLOR_1:\s*(.+)/i', $line, $matches)) {
                $characteristics['clothing_color_1'] = $this->colorToHex(trim($matches[1]));
            } elseif (preg_match('/CLOTHING_COLOR_2:\s*(.+)/i', $line, $matches)) {
                $characteristics['clothing_color_2'] = $this->colorToHex(trim($matches[1]));
            } elseif (preg_match('/ACCESSORIES:\s*(.+)/i', $line, $matches)) {
                $accessories = trim(strtolower($matches[1]));
                if ($accessories !== 'none') {
                    $characteristics['accessories'] = array_map('trim', explode('/', $accessories));
                }
            }
        }

        return $characteristics;
    }

    /**
     * Convertir un nom de couleur en hex
     */
    private function colorToHex(string $color): string
    {
        $colorMap = [
            'black' => '#000000',
            'white' => '#FFFFFF',
            'red' => '#FF0000',
            'blue' => '#0000FF',
            'green' => '#00FF00',
            'yellow' => '#FFFF00',
            'orange' => '#FFA500',
            'purple' => '#800080',
            'pink' => '#FFC0CB',
            'brown' => '#8B4513',
            'gray' => '#808080',
            'grey' => '#808080',
            'beige' => '#F5F5DC',
            'navy' => '#000080',
            'maroon' => '#800000',
        ];

        $color = strtolower(trim($color));
        return $colorMap[$color] ?? '#808080';
    }

    /**
     * Créer un masque de segmentation simple basé sur l'analyse
     */
    public function createSegmentationMask(string $imagePath, array $analysis): string
    {
        $img = $this->imageManager->read($imagePath);
        
        // Pour l'instant, on garde juste l'image telle quelle
        // La segmentation avancée nécessiterait un modèle spécialisé
        
        // Sauvegarder
        $filename = 'segmented_' . uniqid() . '.png';
        $path = storage_path('app/public/avatars/' . $filename);
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $img->save($path);
        
        return 'avatars/' . $filename;
    }

    /**
     * Convertir l'image en pixel art
     */
    public function pixelate(string $imagePath, int $pixelSize = 16): string
    {
        $img = $this->imageManager->read(storage_path('app/public/' . $imagePath));
        
        // Calculer les dimensions pour le pixel art
        $targetSize = 64; // Taille finale en pixels
        
        // Downscale drastiquement pour effet pixel
        $img->scale($targetSize, $targetSize);
        
        // Upscale sans interpolation pour l'effet pixel
        $finalSize = $targetSize * 4; // 256px
        $img->scale($finalSize, $finalSize);
        
        // Sauvegarder
        $filename = 'pixel_' . uniqid() . '.png';
        $path = storage_path('app/public/avatars/' . $filename);
        
        $img->save($path);
        
        return 'avatars/' . $filename;
    }

    /**
     * Générer des frames d'animation idle
     */
    public function generateIdleAnimation(string $pixelArtPath): array
    {
        $frames = [];
        $imgPath = storage_path('app/public/' . $pixelArtPath);
        
        // Créer 6 frames pour animation idle
        for ($i = 0; $i < 6; $i++) {
            $img = $this->imageManager->read($imgPath);
            
            // Appliquer différentes transformations pour chaque frame
            $offset = (int)(sin($i * M_PI / 3) * 3); // Oscillation verticale
            
            // Pour l'instant on garde l'image telle quelle (l'offset sera géré en CSS)
            
            // Sauvegarder la frame
            $filename = 'frame_' . $i . '_' . uniqid() . '.png';
            $framePath = storage_path('app/public/avatars/frames/' . $filename);
            
            if (!file_exists(dirname($framePath))) {
                mkdir(dirname($framePath), 0755, true);
            }
            
            $img->save($framePath);
            
            $frames[] = [
                'index' => $i,
                'path' => 'avatars/frames/' . $filename,
                'offset' => $offset,
            ];
        }
        
        return $frames;
    }

    /**
     * Vérifier si Ollama est accessible
     */
    public function checkOllamaConnection(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->ollamaUrl}/api/tags");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir les modèles disponibles
     */
    public function getAvailableModels(): array
    {
        try {
            $response = Http::get("{$this->ollamaUrl}/api/tags");
            
            if ($response->successful()) {
                return $response->json('models', []);
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching Ollama models: ' . $e->getMessage());
            return [];
        }
    }
}

