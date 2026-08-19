<?php

namespace App\Http\Controllers;

use App\Models\etudiants;
use Illuminate\Http\Request;

class EtudiantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $etudiants = etudiants::with('dossier.ecole')->get();
        return response()->json($etudiants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_etudiant' => 'required|string|max:255',
            'prenom_etudiant' => 'required|string|max:255',
            'email_etu' => 'required|email|max:255|unique:etudiants,email_etu',
            'cv' => 'required|string|max:255',
            'rapport' => 'nullable|string|max:255',
            'id_dossier' => 'required|exists:dossiers,id_dossier',
        ]);

        $etudiant = etudiants::create($validated);
        return response()->json($etudiant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $etudiant = etudiants::with('dossier.ecole')->findOrFail($id);
        return response()->json($etudiant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $etudiant = etudiants::findOrFail($id);

        $validated = $request->validate([
            'nom_etudiant' => 'sometimes|required|string|max:255',
            'prenom_etudiant' => 'sometimes|required|string|max:255',
            'email_etu' => 'sometimes|required|email|max:255|unique:etudiants,email_etu,' . $id . ',id_etudiant',
            'cv' => 'sometimes|required|string|max:255',
            'rapport' => 'nullable|string|max:255',
            'id_dossier' => 'sometimes|required|exists:dossiers,id_dossier',
        ]);

        $etudiant->update($validated);
        return response()->json($etudiant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $etudiant = etudiants::findOrFail($id);
        $etudiant->delete();
        return response()->json(['message' => 'Étudiant supprimé avec succès']);
    }
}
