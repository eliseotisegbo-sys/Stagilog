<?php

namespace App\Http\Controllers;

use App\Models\ecoles;
use Illuminate\Http\Request;

class EcolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ecoles = ecoles::with(['users', 'dossiers'])->get();
        return response()->json($ecoles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_ecole' => 'required|string|max:255',
            'adresse_ecole' => 'nullable|string',
            'num_ecole' => 'nullable|string|max:50',
            'mail' => 'nullable|email|max:255',
        ]);

        $ecole = ecoles::create($validated);
        return response()->json($ecole, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ecole = ecoles::with(['users', 'dossiers.etudiants'])->findOrFail($id);
        return response()->json($ecole);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ecole = ecoles::findOrFail($id);

        $validated = $request->validate([
            'nom_ecole' => 'sometimes|required|string|max:255',
            'adresse_ecole' => 'nullable|string',
            'num_ecole' => 'nullable|string|max:50',
            'mail' => 'nullable|email|max:255',
        ]);

        $ecole->update($validated);
        return response()->json($ecole);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ecole = ecoles::findOrFail($id);
        $ecole->delete();
        return response()->json(['message' => 'École supprimée avec succès']);
    }
}
