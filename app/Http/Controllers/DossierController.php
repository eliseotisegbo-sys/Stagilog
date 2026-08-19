<?php

namespace App\Http\Controllers;

use App\Models\dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dossiers = dossier::with(['ecole', 'etudiants'])->get();
        return response()->json($dossiers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee_academique' => 'required|string|max:50',
            'filiere' => 'required|string|max:255',
            'lettredemande' => 'nullable|string|max:255',
            'datedebut' => 'required|date',
            'datefin' => 'required|date|after:datedebut',
            'id_ecole' => 'required|exists:ecoles,id_ecole',
        ]);

        $dossier = dossier::create($validated);
        return response()->json($dossier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dossier = dossier::with(['ecole', 'etudiants'])->findOrFail($id);
        return response()->json($dossier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dossier = dossier::findOrFail($id);

        $validated = $request->validate([
            'annee_academique' => 'sometimes|required|string|max:50',
            'filiere' => 'sometimes|required|string|max:255',
            'lettredemande' => 'nullable|string|max:255',
            'datedebut' => 'sometimes|required|date',
            'datefin' => 'sometimes|required|date|after:datedebut',
            'id_ecole' => 'sometimes|required|exists:ecoles,id_ecole',
        ]);

        $dossier->update($validated);
        return response()->json($dossier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dossier = dossier::findOrFail($id);
        $dossier->delete();
        return response()->json(['message' => 'Dossier supprimé avec succès']);
    }
}
