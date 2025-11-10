<?php

namespace App\Http\Controllers;

use App\Models\Girlfriend;
use App\Models\PixelAvatar;
use App\Services\OllamaImageService;
use App\Services\ReadyPlayerMeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PixelAvatarController extends Controller
{
    protected OllamaImageService $ollamaService;
    protected ReadyPlayerMeService $rpmService;

    public function __construct(OllamaImageService $ollamaService, ReadyPlayerMeService $rpmService)
    {
        $this->ollamaService = $ollamaService;
        $this->rpmService = $rpmService;
    }

    /**
     * Afficher la page de création d'avatar
     */
    public function create(Girlfriend $girlfriend)
    {
        // Vérifier la connexion Ollama
        $ollamaConnected = $this->ollamaService->checkOllamaConnection();
        $models = $this->ollamaService->getAvailableModels();

        return inertia('PixelAvatar/Create', [
            'girlfriend' => $girlfriend,
            'ollamaConnected' => $ollamaConnected,
            'availableModels' => $models,
        ]);
    }

    /**
     * Générer l'avatar 3D
     */
    public function store(Request $request, Girlfriend $girlfriend)
    {
        $request->validate([
            'photo' => 'required|image|max:10240', // 10MB max
        ]);

        try {
            // 1. Sauvegarder la photo originale
            $photo = $request->file('photo');
            $originalPath = $photo->store('avatars/originals', 'public');
            $fullPath = storage_path('app/public/' . $originalPath);

            // 2. Analyser avec Ollama pour extraire les caractéristiques
            $analysis = $this->ollamaService->analyzeImage($fullPath);
            
            if (!$analysis['success']) {
                return back()->withErrors([
                    'photo' => 'Erreur lors de l\'analyse: ' . $analysis['error']
                ]);
            }

            $characteristics = $analysis['characteristics'];

            // 3. Générer l'URL Ready Player Me (optionnel)
            $rpmUrl = $this->rpmService->generateAvatarUrl($characteristics);

            // 4. Sauvegarder en base de données
            $avatar = PixelAvatar::create([
                'girlfriend_id' => $girlfriend->id,
                'original_photo' => $originalPath,
                'characteristics' => $characteristics,
                'avatar_type' => '3d',
                'rpm_url' => $rpmUrl,
                'segmented_photo' => null,
                'pixel_art_photo' => null,
                'animation_frames' => null,
                'pixel_size' => null,
            ]);

            return Redirect::route('pixel-avatar.show', [$girlfriend->id, $avatar->id])
                ->with('success', 'Avatar 3D créé avec succès ! 🎨✨');

        } catch (\Exception $e) {
            Log::error('Error creating 3D avatar: ' . $e->getMessage());
            
            return back()->withErrors([
                'photo' => 'Erreur lors de la création de l\'avatar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Afficher l'avatar pixel art
     */
    public function show(Girlfriend $girlfriend, PixelAvatar $avatar)
    {
        return inertia('PixelAvatar/Show', [
            'girlfriend' => $girlfriend,
            'avatar' => $avatar,
        ]);
    }

    /**
     * Lister les avatars d'une girlfriend
     */
    public function index(Girlfriend $girlfriend)
    {
        $avatars = $girlfriend->pixelAvatars()->latest()->get();

        return inertia('PixelAvatar/Index', [
            'girlfriend' => $girlfriend,
            'avatars' => $avatars,
        ]);
    }

    /**
     * Supprimer un avatar
     */
    public function destroy(Girlfriend $girlfriend, PixelAvatar $avatar)
    {
        // Supprimer les fichiers
        if ($avatar->original_photo) {
            Storage::disk('public')->delete($avatar->original_photo);
        }
        if ($avatar->segmented_photo) {
            Storage::disk('public')->delete($avatar->segmented_photo);
        }
        if ($avatar->pixel_art_photo) {
            Storage::disk('public')->delete($avatar->pixel_art_photo);
        }
        
        // Supprimer les frames
        if ($avatar->animation_frames) {
            foreach ($avatar->animation_frames as $frame) {
                Storage::disk('public')->delete($frame['path']);
            }
        }

        $avatar->delete();

        return Redirect::route('pixel-avatar.index', $girlfriend->id)
            ->with('success', 'Avatar supprimé.');
    }

    /**
     * Tester la connexion Ollama
     */
    public function testConnection()
    {
        $connected = $this->ollamaService->checkOllamaConnection();
        $models = $this->ollamaService->getAvailableModels();

        return response()->json([
            'connected' => $connected,
            'models' => $models,
            'url' => config('services.ollama.url'),
        ]);
    }
}
