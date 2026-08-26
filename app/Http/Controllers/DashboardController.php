<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use App\Models\Dossier;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Cycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Dashboard École
     */
    public function ecole()
    {
        $user = auth()->user();
        $idEcole = $user->id_ecole;
        $ecole = $user->ecole;

        $hour = (int) now()->format('H');
        $salutation = ($hour >= 18 || $hour < 5) ? 'Bonsoir' : 'Bonjour';

        $userName = session('user_session_name') ?? $user->name;

        $totalDossiers = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'soumis')->count();
        $dossiersValides = Dossier::where('id_ecole', $idEcole)->where('statut', 'valide')->count();
        $dossiersEnAttente = Dossier::where('id_ecole', $idEcole)->where('statut', 'en_attente')->where('statut_brouillon', 'soumis')->count();
        $dossiersBrouillon = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'brouillon')->count();
        $dossiersRefuses = Dossier::where('id_ecole', $idEcole)->where('statut', 'refuse')->count();

        // Total étudiants liés aux dossiers de cette école
        $dossierIds = Dossier::where('id_ecole', $idEcole)->pluck('id_dossier');
        $totalEtudiants = Etudiant::whereIn('id_dossier', $dossierIds)->count();
        $rapportsDisponibles = Etudiant::whereIn('id_dossier', $dossierIds)->whereNotNull('rapport')->count();

        // 5 Derniers dossiers réels
        $recentsDossiers = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants'])
            ->latest()
            ->take(5)
            ->get();

        // Données mensuelles réelles de l'école (6 derniers mois) - UNIQUEMENT DOSSIERS SOUMIS (exclut brouillons)
        $months = [];
        $dossiersData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = ucfirst($date->locale('fr')->isoFormat('MMM YYYY'));
            $count = Dossier::where('id_ecole', $idEcole)
                ->where('statut_brouillon', 'soumis')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $dossiersData[] = $count;
        }

        return view('dashboard.ecole', compact(
            'ecole',
            'userName',
            'salutation',
            'totalDossiers',
            'dossiersValides',
            'dossiersEnAttente',
            'dossiersBrouillon',
            'dossiersRefuses',
            'totalEtudiants',
            'rapportsDisponibles',
            'recentsDossiers',
            'months',
            'dossiersData'
        ));
    }

    /**
     * Dashboard Admin
     */
    public function admin()
    {
        $hour = (int) now()->format('H');
        $salutation = ($hour >= 18 || $hour < 5) ? 'Bonsoir' : 'Bonjour';

        $totalEcoles = Ecole::count();
        $totalDossiers = Dossier::count();
        $dossiersEnAttente = Dossier::where('statut', 'en_attente')->where('statut_brouillon', 'soumis')->count();
        $dossiersValides = Dossier::where('statut', 'valide')->count();
        $dossiersRefuses = Dossier::where('statut', 'refuse')->count();
        $totalEtudiants = Etudiant::count();
        $totalRapports = Etudiant::whereNotNull('rapport')->count();

        $tauxApprobation = $totalDossiers > 0 ? round(($dossiersValides / $totalDossiers) * 100, 1) : 0;

        // 5 Derniers dossiers soumis réels
        $derniersDossiers = Dossier::with(['ecole', 'cycle', 'filiereRelation', 'etudiants'])
            ->latest()
            ->take(5)
            ->get();

        // Statistiques par filière réelles pour Donut ApexCharts
        $filieresStats = Filiere::withCount('dossiers')->get();
        $filiereLabels = $filieresStats->pluck('nom_filiere')->toArray();
        $filiereCounts = $filieresStats->pluck('dossiers_count')->toArray();

        // Données mensuelles réelles de tous les dossiers (6 derniers mois)
        $timelineMonths = [];
        $timelineData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $timelineMonths[] = ucfirst($date->locale('fr')->isoFormat('MMM YYYY'));
            $count = Dossier::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $timelineData[] = $count;
        }

        // Répartition réelle par École
        $ecolesWithDossiers = Ecole::withCount('dossiers')->orderByDesc('dossiers_count')->take(6)->get();
        $ecolesBarLabels = $ecolesWithDossiers->pluck('nom_ecole')->map(function($nom) {
            if (preg_match('/\((.*?)\)/', $nom, $matches)) {
                return $matches[1];
            }
            return Str::limit($nom, 16);
        })->toArray();
        $ecolesBarData = $ecolesWithDossiers->pluck('dossiers_count')->toArray();

        return view('dashboard.admin', compact(
            'salutation',
            'totalEcoles',
            'totalDossiers',
            'dossiersEnAttente',
            'dossiersValides',
            'dossiersRefuses',
            'totalEtudiants',
            'totalRapports',
            'tauxApprobation',
            'derniersDossiers',
            'filiereLabels',
            'filiereCounts',
            'timelineMonths',
            'timelineData',
            'ecolesBarLabels',
            'ecolesBarData'
        ));
    }
}
