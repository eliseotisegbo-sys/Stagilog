<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Cycle;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    /**
     * Liste des filières et cycles
     */
    public function index()
    {
        $filieres = Filiere::withCount('dossiers')->get();
        $cycles = Cycle::withCount('dossiers')->get();

        return view('admin.filieres.index', compact('filieres', 'cycles'));
    }

    /**
     * Créer une filière
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_filiere' => 'required|string|max:255|unique:filieres,nom_filiere',
            'sigle' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ], [
            'nom_filiere.required' => 'Le nom de la filière est requis.',
            'nom_filiere.unique' => 'Cette filière existe déjà.',
        ]);

        Filiere::create([
            'nom_filiere' => $request->nom_filiere,
            'sigle' => $request->sigle,
            'description' => $request->description,
            'actif' => true,
        ]);

        return back()->with('success', "La filière '{$request->nom_filiere}' a été ajoutée avec succès.");
    }

    /**
     * Activer/Désactiver une filière
     */
    public function toggle($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->actif = !$filiere->actif;
        $filiere->save();

        $statut = $filiere->actif ? 'activée' : 'désactivée';
        return back()->with('success', "La filière '{$filiere->nom_filiere}' a été {$statut}.");
    }

    /**
     * Supprimer une filière
     */
    public function destroy($id)
    {
        $filiere = Filiere::withCount('dossiers')->findOrFail($id);

        if ($filiere->dossiers_count > 0) {
            return back()->with('error', "Impossible de supprimer cette filière car {$filiere->dossiers_count} dossier(s) y sont rattachés. Vous pouvez la désactiver à la place.");
        }

        $filiere->delete();
        return back()->with('success', "Filière supprimée avec succès.");
    }

    /**
     * Créer un nouveau Cycle académique (Licence, Master, Ingénieur, etc.)
     */
    public function storeCycle(Request $request)
    {
        $request->validate([
            'nom_cycle' => 'required|string|max:255|unique:cycles,nom_cycle',
            'description' => 'nullable|string|max:255',
        ], [
            'nom_cycle.required' => 'Le nom du cycle est obligatoire (ex: Licence, Master, Ingénieur, Doctorat).',
            'nom_cycle.unique' => 'Ce cycle académique existe déjà.',
        ]);

        Cycle::create([
            'nom_cycle' => $request->nom_cycle,
            'description' => $request->description,
        ]);

        return back()->with('success', "Le cycle académique '{$request->nom_cycle}' a été ajouté avec succès.");
    }

    /**
     * Supprimer un Cycle académique
     */
    public function destroyCycle($id)
    {
        $cycle = Cycle::withCount('dossiers')->findOrFail($id);

        if ($cycle->dossiers_count > 0) {
            return back()->with('error', "Impossible de supprimer ce cycle car {$cycle->dossiers_count} dossier(s) y sont rattachés.");
        }

        $cycle->delete();
        return back()->with('success', "Le cycle a été supprimé.");
    }
}
