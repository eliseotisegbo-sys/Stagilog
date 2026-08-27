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
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard École
     */
    public function ecole(Request $request)
    {
        $user = auth()->user();
        $idEcole = $user->id_ecole;
        $ecole = $user->ecole;

        $hour = (int) now()->format('H');
        $salutation = ($hour >= 18 || $hour < 5) ? 'Bonsoir' : 'Bonjour';
        $userName = session('user_session_name') ?? $user->name;

        // --- Filtre de période ---
        $startDate = null;
        $endDate   = null;
        $periodLabel = 'Toutes les périodes';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
                $endDate   = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
                $periodLabel = 'Du ' . $startDate->locale('fr')->isoFormat('D MMM YYYY') . ' au ' . $endDate->locale('fr')->isoFormat('D MMM YYYY');
            } catch (\Exception $e) {
                $startDate = null;
                $endDate   = null;
            }
        }

        // Base query helper with optional period
        $baseQuery = function() use ($idEcole, $startDate, $endDate) {
            $q = Dossier::where('id_ecole', $idEcole)
                        ->where('statut_brouillon', 'soumis');
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $q;
        };

        $totalDossiers     = (clone $baseQuery())->count();
        $dossiersValides   = (clone $baseQuery())->where('statut', 'valide')->count();
        $dossiersEnAttente = (clone $baseQuery())->where('statut', 'en_attente')->count();
        $dossiersRefuses   = (clone $baseQuery())->where('statut', 'refuse')->count();

        // Brouillons (pas de filtre période car non soumis)
        $dossiersBrouillon = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'brouillon')->count();

        // Total étudiants liés aux dossiers soumis filtrés
        $dossierIds = (clone $baseQuery())->pluck('id_dossier');
        $totalEtudiants    = Etudiant::whereIn('id_dossier', $dossierIds)->count();
        $rapportsDisponibles = Etudiant::whereIn('id_dossier', $dossierIds)->whereNotNull('rapport')->count();

        // 5 Derniers dossiers
        $recentsDossiers = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants'])
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->latest()
            ->take(5)
            ->get();

        // --- Graphique mensuel adaptatif ---
        $months      = [];
        $dossiersData = [];

        if ($startDate && $endDate) {
            // Itérer mois par mois sur la période choisie
            $current = $startDate->copy()->startOfMonth();
            $last    = $endDate->copy()->startOfMonth();
            while ($current->lte($last)) {
                $months[] = ucfirst($current->locale('fr')->isoFormat('MMM YYYY'));
                $dossiersData[] = Dossier::where('id_ecole', $idEcole)
                    ->where('statut_brouillon', 'soumis')
                    ->whereYear('created_at',  $current->year)
                    ->whereMonth('created_at', $current->month)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                $current->addMonth();
            }
        } else {
            // Par défaut : 6 derniers mois
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = ucfirst($date->locale('fr')->isoFormat('MMM YYYY'));
                $dossiersData[] = Dossier::where('id_ecole', $idEcole)
                    ->where('statut_brouillon', 'soumis')
                    ->whereYear('created_at',  $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
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
            'dossiersData',
            'startDate',
            'endDate',
            'periodLabel'
        ));
    }

    /**
     * Dashboard Admin
     */
    public function admin(Request $request)
    {
        $hour = (int) now()->format('H');
        $salutation = ($hour >= 18 || $hour < 5) ? 'Bonsoir' : 'Bonjour';

        // --- Filtre de période ---
        $startDate   = null;
        $endDate     = null;
        $periodLabel = 'Toutes les périodes';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
                $endDate   = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
                $periodLabel = 'Du ' . $startDate->locale('fr')->isoFormat('D MMM YYYY') . ' au ' . $endDate->locale('fr')->isoFormat('D MMM YYYY');
            } catch (\Exception $e) {
                $startDate = null;
                $endDate   = null;
            }
        }

        // Compteurs globaux — toujours toutes les écoles
        $totalEcoles = Ecole::count();

        // Dossiers (avec filtre de période si défini)
        $dossierQuery = function() use ($startDate, $endDate) {
            $q = Dossier::query();
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $q;
        };

        $totalDossiers     = (clone $dossierQuery())->count();
        $dossiersEnAttente = (clone $dossierQuery())->where('statut', 'en_attente')->where('statut_brouillon', 'soumis')->count();
        $dossiersValides   = (clone $dossierQuery())->where('statut', 'valide')->count();
        $dossiersRefuses   = (clone $dossierQuery())->where('statut', 'refuse')->count();
        $totalEtudiants    = Etudiant::count(); // toujours global
        $totalRapports     = Etudiant::whereNotNull('rapport')->count();

        $tauxApprobation   = $totalDossiers > 0 ? round(($dossiersValides / $totalDossiers) * 100, 1) : 0;

        // 5 Derniers dossiers filtrés
        $derniersDossiers = Dossier::with(['ecole', 'cycle', 'filiereRelation', 'etudiants'])
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->latest()
            ->take(5)
            ->get();

        // --- Filières avec filtre ---
        $filieresStats = Filiere::withCount(['dossiers' => function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get();
        $filiereLabels = $filieresStats->pluck('nom_filiere')->toArray();
        $filiereCounts = $filieresStats->pluck('dossiers_count')->toArray();

        // --- Graphique mensuel adaptatif ---
        $timelineMonths = [];
        $timelineData   = [];

        if ($startDate && $endDate) {
            $current = $startDate->copy()->startOfMonth();
            $last    = $endDate->copy()->startOfMonth();
            while ($current->lte($last)) {
                $timelineMonths[] = ucfirst($current->locale('fr')->isoFormat('MMM YYYY'));
                $timelineData[]   = Dossier::whereYear('created_at',  $current->year)
                    ->whereMonth('created_at', $current->month)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                $current->addMonth();
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $timelineMonths[] = ucfirst($date->locale('fr')->isoFormat('MMM YYYY'));
                $timelineData[]   = Dossier::whereYear('created_at',  $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }

        // --- Répartition par école (filtrée) ---
        $ecolesWithDossiers = Ecole::withCount(['dossiers' => function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->orderByDesc('dossiers_count')->take(6)->get();

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
            'ecolesBarData',
            'startDate',
            'endDate',
            'periodLabel'
        ));
    }
}
