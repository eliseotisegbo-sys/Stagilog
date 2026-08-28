<?php

namespace App\Http\Controllers\Ecole;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Etudiant;
use App\Models\EtudiantDocument;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    /**
     * Liste des rapports et PV disponibles pour l'école (STRICTEMENT DOSSIERS VALIDÉS)
     */
    public function index(Request $request)
    {
        $idEcole = auth()->user()->id_ecole;
        $search = $request->query('search');

        // Récupérer uniquement les dossiers VALIDÉS de cette école
        $dossierIds = Dossier::where('id_ecole', $idEcole)
            ->where('statut', 'valide')
            ->pluck('id_dossier');

        $etudiants = Etudiant::whereIn('id_dossier', $dossierIds)
            ->with(['dossier', 'documents' => function($q) {
                $q->where(function($sq) {
                    $sq->where('statut', 'publie')->orWhereNull('statut');
                });
            }])
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nom_etudiant', 'like', "%{$search}%")
                        ->orWhere('prenom_etudiant', 'like', "%{$search}%")
                        ->orWhere('email_etu', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        $totalDocuments = EtudiantDocument::whereHas('etudiant', function($q) use ($dossierIds) {
            $q->whereIn('id_dossier', $dossierIds);
        })->where(function($sq) {
            $sq->where('statut', 'publie')->orWhereNull('statut');
        })->count();

        return view('ecole.rapports.index', compact('etudiants', 'search', 'totalDocuments'));
    }

    /**
     * Liste dédiée des étudiants en stage actuellement avec suivi de progression
     */
    public function stagiairesActifs(Request $request)
    {
        $idEcole = auth()->user()->id_ecole;
        $search = $request->query('search');

        // Récupérer uniquement les dossiers VALIDÉS de cette école
        $dossierIds = Dossier::where('id_ecole', $idEcole)
            ->where('statut', 'valide')
            ->pluck('id_dossier');

        $etudiants = Etudiant::whereIn('id_dossier', $dossierIds)
            ->with(['dossier'])
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nom_etudiant', 'like', "%{$search}%")
                        ->orWhere('prenom_etudiant', 'like', "%{$search}%")
                        ->orWhere('email_etu', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('ecole.rapports.stagiaires-actifs', compact('etudiants', 'search'));
    }
}
