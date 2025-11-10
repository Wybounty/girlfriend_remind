<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IdeaController extends Controller
{
    /**
     * Afficher la liste de toutes les idées.
     */
    public function index()
    {
        $ideas = Idea::latest()->get();
        
        return inertia('Idea/Index', [
            'ideas' => $ideas,
        ]);
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return inertia('Idea/Create');
    }

    /**
     * Enregistrer une nouvelle idée.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'reponses' => 'required|string',
        ]);

        $idea = Idea::create($validated);

        return Redirect::route('ideas.index')
            ->with('success', 'Idée ajoutée avec succès ! 💡');
    }

    /**
     * Afficher les détails d'une idée.
     */
    public function show(Idea $idea)
    {
        return inertia('Idea/Show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Idea $idea)
    {
        return inertia('Idea/Edit', [
            'idea' => $idea,
        ]);
    }

    /**
     * Mettre à jour une idée.
     */
    public function update(Request $request, Idea $idea)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'reponses' => 'required|string',
        ]);

        $idea->update($validated);

        return Redirect::route('ideas.show', $idea->id)
            ->with('success', 'Idée mise à jour avec succès ! 💡');
    }

    /**
     * Supprimer une idée.
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();

        return Redirect::route('ideas.index')
            ->with('success', 'Idée supprimée.');
    }
}
