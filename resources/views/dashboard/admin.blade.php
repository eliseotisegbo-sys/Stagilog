@extends('layouts.dashboard')

@section('title', 'Tableau de Bord Administrateur - STAGILOG')
@section('header_title', 'Tableau de Bord Administrateur')

@section('dashboard_content')
<div class="space-y-8">
    
    <!-- SALUTATION BANNER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0D1B4B] tracking-tight">
                {{ $salutation }}, {{ auth()->user()->name }}
            </h1>
            <p class="text-sm font-medium text-[#6B7AA1] mt-1">
                Supervision en temps réel des dossiers de stage et des universités partenaires.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.ecoles.create') }}" class="inline-flex items-center space-x-2 bg-white hover:bg-slate-50 text-[#1B3A8C] border border-slate-200 px-4 py-2.5 rounded-2xl text-xs font-bold shadow-sm transition">
                <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nouvelle École</span>
            </a>

            <a href="{{ route('admin.dossiers.index') }}" class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg hover:shadow-blue-900/20 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Gérer les Dossiers</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Écoles Partenaires -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Écoles Partenaires</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $totalEcoles }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Établissements enregistrés</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Dossiers Totaux -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Dossiers Soumis</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $totalDossiers }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $dossiersValides }} validés par TFG</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: En Attente de Validation -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">En Attente</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#E8001D]">{{ $dossiersEnAttente }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $dossiersRefuses }} refusés</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-[#E8001D] flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Stagiaires & Étudiants -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Stagiaires</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $totalEtudiants }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $totalRapports }} rapports déposés</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- SÉLECTEUR DE PÉRIODE AVANCÉ POUR LES STATISTIQUES (PALETTE AVEC BOUTONS DIRECTS & DOUBLE CALENDRIER) -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-card p-5 relative">
        <form method="GET" action="{{ route('dashboard.admin') }}" id="period-form" class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-4">
            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#EEF4FF] text-[#1B3A8C] flex items-center justify-center shadow-inner flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#0D1B4B]">Période des Statistiques</h4>
                    <p class="text-[11px] text-slate-400">Filtrer par jour, semaine ou année avec double calendrier</p>
                </div>
            </div>

            <!-- Palette des raccourcis directs & Sélecteur Personnalisé -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Raccourci Direct: Aujourd'hui -->
                <button type="button" onclick="applyDirectPreset('jour')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition flex items-center space-x-1.5 {{ request('start_date') === now()->format('Y-m-d') && request('end_date') === now()->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    <span>Aujourd'hui</span>
                </button>

                <!-- Raccourci Direct: Semaine (7 jours en arrière) -->
                <button type="button" onclick="applyDirectPreset('semaine')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition flex items-center space-x-1.5 {{ request('start_date') === now()->subDays(7)->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    <span>Semaine</span>
                </button>

                <!-- Raccourci Direct: Année (1 an en arrière) -->
                <button type="button" onclick="applyDirectPreset('annee')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition flex items-center space-x-1.5 {{ request('start_date') === now()->subYear()->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    <span>Année</span>
                </button>

                <!-- Trigger Popover Période Personnalisée -->
                <div class="relative">
                    <button type="button" onclick="toggleDatePopover()" id="btn-date-trigger"
                            class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-2xl text-xs font-bold text-[#0D1B4B] shadow-sm transition">
                        <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span id="date-summary-label">{{ $periodLabel }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- POPOVER ADVANCED CALENDAR (Double Calendrier sans dates futures) -->
                    <div id="date-popover" class="hidden absolute right-0 top-full mt-3 z-50 bg-white rounded-3xl shadow-2xl border border-slate-200 p-6 flex flex-col gap-4 w-full sm:w-[640px]">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-xs font-black uppercase tracking-wider text-[#0D1B4B]">Sélectionner une Plage Personnalisée</span>
                            <span class="text-[10px] font-bold text-slate-400">Dates antérieures ou égales à aujourd'hui</span>
                        </div>

                        <!-- Double Calendrier Flatpickr -->
                        <div class="overflow-x-auto">
                            <div id="flatpickr-inline-target" class="flex justify-center min-h-[290px]"></div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center space-x-2 text-xs">
                                <span class="font-bold text-slate-400">Du :</span>
                                <input type="text" id="popover_start_display" readonly class="w-24 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-[#0D1B4B] text-[11px]">
                                <span class="font-bold text-slate-400">Au :</span>
                                <input type="text" id="popover_end_display" readonly class="w-24 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-[#0D1B4B] text-[11px]">
                            </div>
                            <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                                <button type="button" onclick="clearDatePreset()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">Effacer</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-[#1B3A8C] text-white font-bold text-xs hover:bg-[#142B6B] shadow-md transition">Appliquer</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if(request('start_date'))
                <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 px-3.5 py-2 rounded-2xl text-xs font-bold transition" title="Réinitialiser à toutes les périodes">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Toutes</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- GRAPHIQUE PRINCIPAL & DERNIÈRES NOTIFICATIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Courbe Principale Réelle (8 cols) -->
        <div class="lg:col-span-8 bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-[#0D1B4B]">Historique des Dossiers Soumis</h3>
                    <p class="text-xs font-medium text-slate-400">
                        @if($periodLabel !== 'Toutes les périodes')
                            Période sélectionnée : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span>
                        @else
                            Nombre réel de dossiers enregistrés par mois (6 derniers mois)
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#1B3A8C]"></span>
                    <span class="text-xs font-bold text-slate-600">Dossiers</span>
                </div>
            </div>

            <!-- Conteneur Graphique ApexCharts -->
            <div id="chart-timeline" class="w-full h-80"></div>
        </div>

        <!-- Colonne Droite : Demandes Récentes & Taux (4 cols) -->
        <div class="lg:col-span-4 bg-white p-8 rounded-3xl shadow-card border border-slate-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Demandes Récentes</h3>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Temps Réel</span>
                </div>

                <div class="space-y-4">
                    @forelse($derniersDossiers as $dossier)
                    <a href="{{ route('admin.dossiers.show', $dossier->id_dossier) }}" class="block p-4 rounded-2xl bg-slate-50/70 hover:bg-blue-50/50 border border-slate-100 transition group">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-bold text-[#0D1B4B] group-hover:text-[#1B3A8C] text-xs transition">
                                    {{ $dossier->ecole->nom_ecole ?? 'École Partenaire' }}
                                </span>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $dossier->filiere }} ({{ $dossier->etudiants->count() }} stagiaires)</p>
                            </div>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-[10px] text-slate-400">
                            <span>{{ $dossier->cycle->nom_cycle ?? 'Cycle standard' }}</span>
                            <span>{{ $dossier->created_at ? $dossier->created_at->diffForHumans() : '' }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-6">Aucune demande récente.</p>
                    @endforelse
                </div>
            </div>

            <!-- Taux d'approbation réel -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between text-xs font-bold text-slate-600 mb-2">
                    <span>Taux d'Approbation Réel</span>
                    <span class="text-[#1B3A8C] font-black">{{ $tauxApprobation }}%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-[#1B3A8C] h-full rounded-full transition-all duration-500" style="width: {{ $tauxApprobation }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5">{{ $dossiersValides }} validés sur {{ $totalDossiers }} dossiers soumis au total.</p>
            </div>
        </div>
    </div>

    <!-- 2 GRAPHES SECONDAIRES RÉELS : RÉPARTITION PAR FILIÈRE & PAR ÉCOLE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Répartition par Filière Réelle -->
        <div class="bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Répartition par Filière</h3>
                    <p class="text-xs font-medium text-slate-400">Dossiers soumis par domaine technique</p>
                </div>
            </div>
            <div id="chart-filieres" class="w-full h-64 flex items-center justify-center"></div>
        </div>

        <!-- Top Écoles Partenaires Réelles -->
        <div class="bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Activité par Établissement</h3>
                    <p class="text-xs font-medium text-slate-400">Nombre de dossiers soumis par université</p>
                </div>
            </div>
            <div id="chart-ecoles" class="w-full h-64"></div>
        </div>
    </div>

    <!-- TABLEAU RÉCAPITULATIF DES DOSSIERS SOUMIS RÉCENTS -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Derniers Dossiers de Stage Enregistrés</h3>
                <p class="text-xs font-medium text-slate-400">Liste des demandes récentes soumises par les écoles</p>
            </div>
            <a href="{{ route('admin.dossiers.index') }}" class="text-xs font-bold text-[#1B3A8C] hover:underline">
                Voir tout ({{ $totalDossiers }}) &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">ID / École</th>
                        <th class="py-4 px-6">Filière & Cycle</th>
                        <th class="py-4 px-6">Stagiaires</th>
                        <th class="py-4 px-6">Période Prévue</th>
                        <th class="py-4 px-6">Statut TFG</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($derniersDossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6">
                            <span class="font-mono font-bold text-[#0D1B4B]">{{ $dossier->code_dossier ?? 'STAGE-' . $dossier->id_dossier }}</span>
                            <div class="font-semibold text-slate-600 mt-0.5">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B]">{{ $dossier->filiere }}</div>
                            <div class="text-[11px] text-blue-600 font-bold">{{ $dossier->cycle->nom_cycle ?? 'Cycle standard' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">
                                {{ $dossier->etudiants->count() }} étudiant(s)
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-600 text-xs lowercase">
                            {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                            <span class="text-slate-400 mx-1">au</span>
                            {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700 border border-red-200' : ($dossier->statut === 'sous_reserve' ? 'bg-blue-100 text-[#1B3A8C] border border-blue-200' : 'bg-amber-100 text-amber-700 border border-amber-200')) }}">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : ($dossier->statut === 'sous_reserve' ? 'Sous réserve' : 'En attente')) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.dossiers.show', $dossier->id_dossier) }}" 
                               class="inline-flex items-center space-x-1 text-xs font-bold text-[#1B3A8C] hover:text-[#E8001D] bg-[#F0F4FF] hover:bg-red-50 px-3 py-1.5 rounded-xl transition">
                                <span>Examiner</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Aucun dossier enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
let fpRangePicker = null;

function toggleDatePopover() {
    const pop = document.getElementById('date-popover');
    pop.classList.toggle('hidden');
}

function formatDate(d) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function applyDirectPreset(preset) {
    const today = new Date();
    let start = null;
    let end = new Date(today);

    if (preset === 'jour') {
        start = new Date(today);
    } else if (preset === 'semaine') {
        start = new Date(today);
        start.setDate(today.getDate() - 7);
    } else if (preset === 'annee') {
        start = new Date(today);
        start.setFullYear(today.getFullYear() - 1);
    }

    if (start && end) {
        document.getElementById('start_date').value = formatDate(start);
        document.getElementById('end_date').value = formatDate(end);
        document.getElementById('period-form').submit();
    }
}

function clearDatePreset() {
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('popover_start_display').value = '';
    document.getElementById('popover_end_display').value = '';
    if (fpRangePicker) {
        fpRangePicker.clear();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sVal = document.getElementById('start_date').value;
    const eVal = document.getElementById('end_date').value;
    document.getElementById('popover_start_display').value = sVal || '';
    document.getElementById('popover_end_display').value = eVal || '';

    // Initialize Flatpickr in range mode with dual month view (maxDate: today)
    if (typeof flatpickr !== 'undefined') {
        flatpickr.localize(flatpickr.l10ns.fr);
        fpRangePicker = flatpickr("#flatpickr-inline-target", {
            inline: true,
            mode: "range",
            showMonths: 2,
            maxDate: "today",
            dateFormat: "Y-m-d",
            defaultDate: (sVal && eVal) ? [sVal, eVal] : null,
            locale: "fr",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 1) {
                    const sStr = formatDate(selectedDates[0]);
                    document.getElementById('start_date').value = sStr;
                    document.getElementById('popover_start_display').value = sStr;
                } else if (selectedDates.length === 2) {
                    const sStr = formatDate(selectedDates[0]);
                    const eStr = formatDate(selectedDates[1]);
                    document.getElementById('start_date').value = sStr;
                    document.getElementById('end_date').value = eStr;
                    document.getElementById('popover_start_display').value = sStr;
                    document.getElementById('popover_end_display').value = eStr;
                }
            }
        });
    }

    // Fermer le popover si on clique en dehors
    document.addEventListener('click', function(e) {
        const pop = document.getElementById('date-popover');
        const trigger = document.getElementById('btn-date-trigger');
        if (pop && !pop.classList.contains('hidden') && !pop.contains(e.target) && !trigger.contains(e.target)) {
            pop.classList.add('hidden');
        }
    });
    
    // 1. Timeline Area Chart (Données réelles de la BD)
    var optionsTimeline = {
        series: [{
            name: 'Dossiers',
            data: @json($timelineData)
        }],
        chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        colors: ['#1B3A8C'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($timelineMonths),
            labels: { style: { colors: '#6B7AA1', fontSize: '11px', fontWeight: 600 } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: { 
                formatter: function(val) { return Math.floor(val); },
                style: { colors: '#6B7AA1', fontSize: '11px', fontWeight: 600 } 
            }
        },
        grid: {
            borderColor: '#F1F5F9',
            strokeDashArray: 4
        }
    };
    var chartTimeline = new ApexCharts(document.querySelector("#chart-timeline"), optionsTimeline);
    chartTimeline.render();

    // 2. Donut Chart (Filières réelles de la BD)
    var fCounts = @json($filiereCounts);
    var fLabels = @json($filiereLabels);
    
    var optionsFilieres = {
        series: fCounts.length > 0 && fCounts.some(v => v > 0) ? fCounts : [1],
        labels: fCounts.length > 0 && fCounts.some(v => v > 0) ? fLabels : ['Aucune donnée'],
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        colors: ['#1B3A8C', '#3B82F6', '#10B981', '#F59E0B', '#E8001D', '#8B5CF6', '#EC4899'],
        legend: {
            position: 'bottom',
            fontSize: '11px',
            fontWeight: 600,
            labels: { colors: '#475569' }
        },
        dataLabels: { enabled: false }
    };
    var chartFilieres = new ApexCharts(document.querySelector("#chart-filieres"), optionsFilieres);
    chartFilieres.render();

    // 3. Bar Chart (Écoles réelles de la BD)
    var optionsEcoles = {
        series: [{
            name: 'Dossiers',
            data: @json($ecolesBarData)
        }],
        chart: {
            type: 'bar',
            height: 250,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        colors: ['#3B82F6'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '40%',
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($ecolesBarLabels),
            labels: { 
                rotate: 0,
                trim: true,
                style: { colors: '#6B7AA1', fontSize: '11px', fontWeight: 600 } 
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: { 
                formatter: function(val) { return Math.floor(val); },
                style: { colors: '#6B7AA1', fontSize: '11px' } 
            }
        },
        grid: {
            borderColor: '#F1F5F9',
            strokeDashArray: 4
        }
    };
    var chartEcoles = new ApexCharts(document.querySelector("#chart-ecoles"), optionsEcoles);
    chartEcoles.render();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@endpush
@endsection
