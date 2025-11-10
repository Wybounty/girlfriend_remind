<?php

namespace App\Http\Controllers;

use App\Models\Girlfriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GirlfriendController extends Controller
{
    /**
     * Afficher la liste des girlfriends (dans votre cas, probablement juste une).
     */
    public function index()
    {
        $girlfriends = Girlfriend::all();
        
        return inertia('Girlfriend/Index', [
            'girlfriends' => $girlfriends,
        ]);
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return inertia('Girlfriend/Create');
    }

    /**
     * Enregistrer une nouvelle girlfriend.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'surnom' => 'required|string|max:255',
            'date_anniversaire' => 'required|date',
            'allergie' => 'required|string',
            'date_rencontre' => 'required|date',
            'nom_doudou' => 'required|string|max:255',
            'plat_prefere' => 'required|string|max:255',
        ]);

        // Calculer automatiquement le signe astrologique
        $validated['signe_astro'] = Girlfriend::calculerSigneAstro($validated['date_anniversaire']);

        $girlfriend = Girlfriend::create($validated);

        return Redirect::route('girlfriends.show', $girlfriend->id)
            ->with('success', 'Les informations ont été enregistrées avec succès ! 💕');
    }

    /**
     * Afficher les détails d'une girlfriend.
     */
    public function show(Girlfriend $girlfriend)
    {
        return inertia('Girlfriend/Show', [
            'girlfriend' => $girlfriend->load('infos'),
            'age' => $girlfriend->age,
            'jours_ensemble' => $girlfriend->jours_ensemble,
        ]);
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Girlfriend $girlfriend)
    {
        return inertia('Girlfriend/Edit', [
            'girlfriend' => $girlfriend,
        ]);
    }

    /**
     * Mettre à jour les informations.
     */
    public function update(Request $request, Girlfriend $girlfriend)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'surnom' => 'required|string|max:255',
            'date_anniversaire' => 'required|date',
            'allergie' => 'required|string',
            'date_rencontre' => 'required|date',
            'nom_doudou' => 'required|string|max:255',
            'plat_prefere' => 'required|string|max:255',
        ]);

        // Recalculer le signe astrologique si la date d'anniversaire a changé
        $validated['signe_astro'] = Girlfriend::calculerSigneAstro($validated['date_anniversaire']);

        $girlfriend->update($validated);

        return Redirect::route('girlfriends.show', $girlfriend->id)
            ->with('success', 'Les informations ont été mises à jour avec succès ! 💕');
    }

    /**
     * Supprimer une girlfriend.
     */
    public function destroy(Girlfriend $girlfriend)
    {
        $girlfriend->delete();

        return Redirect::route('girlfriends.index')
            ->with('success', 'Les informations ont été supprimées.');
    }
}
