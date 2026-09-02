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

            <!-- Palette des raccourcis + sélecteur inline sur la même ligne -->
            <div class="flex flex-wrap items-center gap-2">

                <!-- Raccourci: Aujourd'hui -->
                <button type="button" onclick="applyDirectPreset('jour')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('start_date') === now()->format('Y-m-d') && request('end_date') === now()->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    Aujourd'hui
                </button>

                <!-- Raccourci: Semaine -->
                <button type="button" onclick="applyDirectPreset('semaine')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('start_date') === now()->subDays(7)->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    Semaine
                </button>

                <!-- Raccourci: Année -->
                <button type="button" onclick="applyDirectPreset('annee')"
                        class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('start_date') === now()->subYear()->format('Y-m-d') ? 'bg-[#1B3A8C] text-white shadow-md' : 'bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-[#1B3A8C] border border-slate-200' }}">
                    Année
                </button>

                <!-- Séparateur vertical -->
                <div class="h-6 w-px bg-slate-200 mx-1"></div>

                <!-- Période personnalisée inline: Du / Au / Effacer / Appliquer -->
                <div class="flex items-center gap-1.5 relative">
                    <span class="text-xs font-bold text-slate-400 whitespace-nowrap">Du&nbsp;:</span>
                    <div class="relative">
                        <input type="text" id="inline_start_date" placeholder="jj/mm/aaaa" autocomplete="off"
                               class="w-28 px-2.5 py-1.5 bg-white border border-slate-200 rounded-xl text-[11px] font-bold text-[#0D1B4B] text-center focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] cursor-pointer"
                               value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}">
                        <!-- Micro calendrier Du -->
                        <div id="cal-start" class="hidden absolute left-0 top-full mt-2 z-50 bg-white rounded-2xl shadow-2xl border border-slate-200 p-1"></div>
                    </div>

                    <span class="text-xs font-bold text-slate-400 whitespace-nowrap">Au&nbsp;:</span>
                    <div class="relative">
                        <input type="text" id="inline_end_date" placeholder="jj/mm/aaaa" autocomplete="off"
                               class="w-28 px-2.5 py-1.5 bg-white border border-slate-200 rounded-xl text-[11px] font-bold text-[#0D1B4B] text-center focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] cursor-pointer"
                               value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}">
                        <!-- Micro calendrier Au -->
                        <div id="cal-end" class="hidden absolute left-0 top-full mt-2 z-50 bg-white rounded-2xl shadow-2xl border border-slate-200 p-1"></div>
                    </div>

                    <button type="button" onclick="clearInlineDates()"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition whitespace-nowrap">Effacer</button>
                    <button type="button" onclick="applyInlineDates()"
                            class="px-4 py-1.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white font-bold text-xs rounded-xl shadow transition whitespace-nowrap">Appliquer</button>
                </div>
            </div>
        </form>
    </div>

    <!-- 1. GRAPHIQUE PRINCIPAL : PLEINE LARGEUR AVEC TAUX D'APPROBATION INTÉGRÉ -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-100">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
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
            
            <div class="flex flex-wrap items-center gap-4">
                <!-- Taux d'approbation réel compact -->
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                    <div class="text-right">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Taux d'Approbation</span>
                        <span class="text-xs font-bold text-slate-600">{{ $dossiersValides }}/{{ $totalDossiers }} validés</span>
                    </div>
                    <span class="text-base font-black text-emerald-600">{{ $tauxApprobation }}%</span>
                </div>

                <div class="flex items-center space-x-2 bg-blue-50/60 px-3.5 py-2 rounded-2xl border border-blue-100/50">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#1B3A8C]"></span>
                    <span class="text-xs font-bold text-[#1B3A8C]">Dossiers soumis</span>
                </div>
            </div>
        </div>

        <!-- Conteneur Graphique ApexCharts -->
        <div id="chart-timeline" class="w-full"></div>
    </div>

    <!-- 2. GRAPHES SECONDAIRES RÉELS : RÉPARTITION PAR FILIÈRE & PAR ÉCOLE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- Répartition par Filière Réelle -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Répartition par Filière</h3>
                    <p class="text-xs font-medium text-slate-400">Dossiers soumis par domaine technique</p>
                </div>
            </div>
            <div id="chart-filieres" class="w-full flex items-center justify-center"></div>
        </div>

        <!-- Top Écoles Partenaires Réelles -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Activité par Établissement</h3>
                    <p class="text-xs font-medium text-slate-400">Nombre de dossiers soumis par université</p>
                </div>
            </div>
            <div id="chart-ecoles" class="w-full"></div>
        </div>
    </div>

    <!-- 3. SECTION DEMANDES RÉCENTES (SOUS LES GRAPHIQUES) -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-[#0D1B4B]">Demandes Récentes</h3>
                    <p class="text-xs font-medium text-slate-400">Derniers dossiers soumis en attente ou traités</p>
                </div>
            </div>
            <a href="{{ route('admin.dossiers.index') }}" class="text-xs font-bold text-[#1B3A8C] hover:underline flex items-center gap-1">
                <span>Voir tout ({{ $totalDossiers }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($derniersDossiers as $dossier)
            <a href="{{ route('admin.dossiers.show', $dossier->id_dossier) }}" 
               class="p-4 rounded-2xl bg-slate-50/70 hover:bg-blue-50/50 border border-slate-100 hover:border-blue-200 transition-all duration-200 group flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <span class="font-bold text-[#0D1B4B] group-hover:text-[#1B3A8C] text-xs transition truncate" title="{{ $dossier->ecole->nom_ecole ?? 'École Partenaire' }}">
                            {{ $dossier->ecole->nom_ecole ?? 'École Partenaire' }}
                        </span>
                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider flex-shrink-0 {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium">{{ $dossier->filiere }} ({{ $dossier->etudiants->count() }} stagiaires)</p>
                </div>
                <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-200/60 text-[10px] text-slate-400">
                    <span class="font-semibold text-slate-500">{{ $dossier->cycle->nom_cycle ?? 'Cycle standard' }}</span>
                    <span>{{ $dossier->created_at ? $dossier->created_at->diffForHumans() : '' }}</span>
                </div>
            </a>
            @empty
            <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                Aucune demande récente pour le moment.
            </div>
            @endforelse
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
/* ──── Helpers ──── */
function formatDate(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}
function parseDisplayDate(str) {
    // Parse dd/mm/yyyy
    const parts = str.split('/');
    if (parts.length === 3) return new Date(parts[2], parts[1] - 1, parts[0]);
    return null;
}
function toDisplay(isoStr) {
    if (!isoStr) return '';
    const parts = isoStr.split('-');
    if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
    return isoStr;
}

/* ──── Presets rapides ──── */
function applyDirectPreset(preset) {
    const today = new Date();
    let start = new Date(today);
    let end = new Date(today);
    if (preset === 'semaine') start.setDate(today.getDate() - 7);
    if (preset === 'annee') start.setFullYear(today.getFullYear() - 1);
    document.getElementById('start_date').value = formatDate(start);
    document.getElementById('end_date').value = formatDate(end);
    document.getElementById('period-form').submit();
}

/* ──── Inline date inputs ──── */
function applyInlineDates() {
    const startRaw = document.getElementById('inline_start_date').value.trim();
    const endRaw   = document.getElementById('inline_end_date').value.trim();
    
    let startIso = '';
    let endIso   = '';
    
    // Si les dates sont saisies via le calendrier Flatpickr, elles seront déjà synchronisées
    // Sinon, on les parse manuellement
    if (startRaw) {
        const d = parseDisplayDate(startRaw);
        if (d && !isNaN(d)) {
            startIso = formatDate(d);
        } else {
            // Peut-être déjà au format Y-m-d
            startIso = startRaw;
        }
    }
    
    if (endRaw) {
        const d = parseDisplayDate(endRaw);
        if (d && !isNaN(d)) {
            endIso = formatDate(d);
        } else {
            endIso = endRaw;
        }
    }
    
    // Vérifier que les dates sont valides avant de soumettre
    if (!startIso && !endIso) {
        alert('Veuillez sélectionner au moins une date.');
        return;
    }
    
    // Mettre à jour les champs hidden
    document.getElementById('start_date').value = startIso;
    document.getElementById('end_date').value   = endIso;
    
    // Soumettre le formulaire
    document.getElementById('period-form').submit();
}

function clearInlineDates() {
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value   = '';
    document.getElementById('inline_start_date').value = '';
    document.getElementById('inline_end_date').value   = '';
    if (window.fpStart) window.fpStart.clear();
    if (window.fpEnd)   window.fpEnd.clear();
    document.getElementById('period-form').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const sVal = document.getElementById('start_date').value;
    const eVal = document.getElementById('end_date').value;

    // Sync display fields on load
    if (sVal) document.getElementById('inline_start_date').value = toDisplay(sVal);
    if (eVal) document.getElementById('inline_end_date').value   = toDisplay(eVal);

    // Initialise Flatpickr mini-calendars (maxDate: today, no future)
    if (typeof flatpickr !== 'undefined') {
        flatpickr.localize(flatpickr.l10ns.fr);

        const commonOpts = {
            dateFormat: 'd/m/Y',
            maxDate: 'today',
            locale: 'fr',
            disableMobile: true,
            altInput: false,
            allowInput: true,
            clickOpens: true,
        };

        window.fpStart = flatpickr('#inline_start_date', {
            ...commonOpts,
            defaultDate: sVal ? toDisplay(sVal) : null,
            onChange: function(dates, dateStr, instance) {
                if (dates[0]) {
                    // Mettre à jour le champ hidden avec la date au format Y-m-d
                    document.getElementById('start_date').value = formatDate(dates[0]);
                    // Mettre à jour le champ visible avec la date au format d/m/Y
                    document.getElementById('inline_start_date').value = dateStr;
                    // Mettre à jour la date min du calendrier de fin
                    if (window.fpEnd) {
                        window.fpEnd.set('minDate', dates[0]);
                    }
                }
            }
        });

        window.fpEnd = flatpickr('#inline_end_date', {
            ...commonOpts,
            defaultDate: eVal ? toDisplay(eVal) : null,
            minDate: sVal || null,
            onChange: function(dates, dateStr, instance) {
                if (dates[0]) {
                    // Mettre à jour le champ hidden avec la date au format Y-m-d
                    document.getElementById('end_date').value = formatDate(dates[0]);
                    // Mettre à jour le champ visible avec la date au format d/m/Y
                    document.getElementById('inline_end_date').value = dateStr;
                    // Mettre à jour la date max du calendrier de début
                    if (window.fpStart) {
                        window.fpStart.set('maxDate', dates[0]);
                    }
                }
            }
        });
    }
    
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
