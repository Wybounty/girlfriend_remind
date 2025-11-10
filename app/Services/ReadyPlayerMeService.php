<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ReadyPlayerMeService
{
    /**
     * Générer une URL d'avatar Ready Player Me basée sur les caractéristiques
     */
    public function generateAvatarUrl(array $characteristics): string
    {
        // Ready Player Me utilise un système de configuration JSON
        $config = $this->buildAvatarConfig($characteristics);
        
        // Créer un ID unique pour cet avatar
        $avatarId = uniqid('avatar_');
        
        // URL de base Ready Player Me
        $baseUrl = 'https://models.readyplayer.me';
        
        // Pour l'instant, on retourne une URL avec les paramètres
        // En production, vous pourriez utiliser l'API Ready Player Me pour créer un avatar custom
        return "{$baseUrl}/{$avatarId}.glb";
    }

    /**
     * Construire la configuration de l'avatar
     */
    private function buildAvatarConfig(array $characteristics): array
    {
        return [
            'skinTone' => $this->mapSkinTone($characteristics['skin_tone'] ?? 'medium'),
            'hairColor' => $this->mapHairColor($characteristics['hair_color'] ?? 'brown'),
            'hairStyle' => $this->mapHairStyle($characteristics['hair_style'] ?? 'straight'),
            'eyeColor' => $this->mapEyeColor($characteristics['eye_color'] ?? 'brown'),
            'gender' => $characteristics['gender'] ?? 'neutral',
            'bodyType' => $characteristics['body_type'] ?? 'average',
            'outfit' => [
                'top' => $characteristics['clothing_color_1'] ?? '#808080',
                'bottom' => $characteristics['clothing_color_2'] ?? '#ffffff',
            ],
            'accessories' => $characteristics['accessories'] ?? [],
        ];
    }

    /**
     * Mapper le ton de peau vers une valeur hex
     */
    private function mapSkinTone(string $tone): string
    {
        $toneMap = [
            'very pale' => '#FFE4C4',
            'pale' => '#FDD7B3',
            'light' => '#F1C6A5',
            'medium' => '#E3B08E',
            'tan' => '#D9A066',
            'brown' => '#8D5524',
            'dark brown' => '#6B4226',
            'very dark' => '#4A2511',
        ];

        return $toneMap[$tone] ?? '#E3B08E';
    }

    /**
     * Mapper la couleur de cheveux
     */
    private function mapHairColor(string $color): string
    {
        $colorMap = [
            'blonde' => '#F4E4C1',
            'light brown' => '#C4A588',
            'brown' => '#8B6F47',
            'dark brown' => '#5C4033',
            'black' => '#1a1a1a',
            'red' => '#A5301D',
            'gray' => '#9CA3AF',
            'white' => '#E5E7EB',
        ];

        return $colorMap[$color] ?? '#8B6F47';
    }

    /**
     * Mapper le style de cheveux
     */
    private function mapHairStyle(string $style): string
    {
        $styleMap = [
            'straight' => 'straight',
            'wavy' => 'wavy',
            'curly' => 'curly',
            'braided' => 'braids',
            'other' => 'default',
        ];

        return $styleMap[$style] ?? 'default';
    }

    /**
     * Mapper la couleur des yeux
     */
    private function mapEyeColor(string $color): string
    {
        $colorMap = [
            'blue' => '#5B9BD5',
            'green' => '#70AD47',
            'brown' => '#7F6545',
            'hazel' => '#8E7618',
            'gray' => '#7F7F7F',
            'black' => '#2F2F2F',
        ];

        return $colorMap[$color] ?? '#7F6545';
    }

    /**
     * Obtenir les couleurs de peau disponibles
     */
    public static function getAvailableSkinTones(): array
    {
        return [
            'very pale' => '#FFE4C4',
            'pale' => '#FDD7B3',
            'light' => '#F1C6A5',
            'medium' => '#E3B08E',
            'tan' => '#D9A066',
            'brown' => '#8D5524',
            'dark brown' => '#6B4226',
            'very dark' => '#4A2511',
        ];
    }

    /**
     * Obtenir les couleurs de cheveux disponibles
     */
    public static function getAvailableHairColors(): array
    {
        return [
            'blonde' => '#F4E4C1',
            'light brown' => '#C4A588',
            'brown' => '#8B6F47',
            'dark brown' => '#5C4033',
            'black' => '#1a1a1a',
            'red' => '#A5301D',
            'gray' => '#9CA3AF',
            'white' => '#E5E7EB',
        ];
    }
}

