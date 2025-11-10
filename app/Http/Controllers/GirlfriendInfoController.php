<?php

namespace App\Http\Controllers;

use App\Models\Girlfriend;
use App\Models\GirlfriendInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GirlfriendInfoController extends Controller
{
    /**
     * Afficher le formulaire de création.
     */
    public function create(Girlfriend $girlfriend)
    {
        return inertia('Girlfriend/InfoCreate', [
            'girlfriend' => $girlfriend,
        ]);
    }

    /**
     * Enregistrer une nouvelle information.
     */
    public function store(Request $request, Girlfriend $girlfriend)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'reponses' => 'required|string',
        ]);

        $girlfriend->infos()->create($validated);

        return Redirect::route('girlfriends.show', $girlfriend->id)
            ->with('success', 'Information ajoutée avec succès ! 💕');
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Girlfriend $girlfriend, GirlfriendInfo $info)
    {
        return inertia('Girlfriend/InfoEdit', [
            'girlfriend' => $girlfriend,
            'info' => $info,
        ]);
    }

    /**
     * Mettre à jour une information.
     */
    public function update(Request $request, Girlfriend $girlfriend, GirlfriendInfo $info)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'reponses' => 'required|string',
        ]);

        $info->update($validated);

        return Redirect::route('girlfriends.show', $girlfriend->id)
            ->with('success', 'Information mise à jour avec succès ! 💕');
    }

    /**
     * Supprimer une information.
     */
    public function destroy(Girlfriend $girlfriend, GirlfriendInfo $info)
    {
        $info->delete();

        return Redirect::route('girlfriends.show', $girlfriend->id)
            ->with('success', 'Information supprimée.');
    }
}
