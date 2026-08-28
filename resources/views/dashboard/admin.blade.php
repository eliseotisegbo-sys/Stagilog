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

    <!-- 4 KPI CARDS (Vraies valeurs de la BDD, sans données factices ni emojis) -->
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

    <!-- SÉLECTEUR DE PÉRIODE POUR LES STATISTIQUES & GRAPHIQUES -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-card p-5">
        <form method="GET" action="{{ route('dashboard.admin') }}" id="period-form" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-[#EEF4FF] text-[#1B3A8C] flex items-center justify-center shadow-inner flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#0D1B4B]">Période des Statistiques</h4>
                    <p class="text-[11px] text-slate-400">Filtrer l'historique et les graphiques d'activité</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 max-w-2xl">
                <div class="relative flex-1 w-full">
                    <input type="text" id="start_date_display" placeholder="Date de début"
                        class="w-full pl-3.5 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-[#0D1B4B] focus:ring-2 focus:ring-[#1B3A8C] focus:border-transparent outline-none cursor-pointer bg-slate-50 transition"
                        readonly>
                    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                </div>
                
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl hidden sm:inline-block">au</span>

                <div class="relative flex-1 w-full">
                    <input type="text" id="end_date_display" placeholder="Date de fin"
                        class="w-full pl-3.5 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-[#0D1B4B] focus:ring-2 focus:ring-[#1B3A8C] focus:border-transparent outline-none cursor-pointer bg-slate-50 transition"
                        readonly>
                    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-md flex-1 sm:flex-initial">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        Filtrer
                    </button>

                    @if(request('start_date'))
                    <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Réinitialiser
                    </a>
                    @endif
                </div>
            </div>

            @if($periodLabel !== 'Toutes les périodes')
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 bg-[#EEF4FF] text-[#1B3A8C] text-[11px] font-bold px-3 py-1.5 rounded-full border border-[#BFDBFE]">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                    {{ $periodLabel }}
                </span>
            </div>
            @endif
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
                            Période : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span>
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
                    <a href="{{ route('admin.dossiers.index') }}" class="text-xs font-bold text-[#1B3A8C] hover:underline">Voir tout</a>
                </div>

                <div class="space-y-4">
                    @forelse($derniersDossiers->take(4) as $dossier)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-[#F0F4FF] transition">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-[#0D1B4B] truncate">{{ $dossier->ecole->nom_ecole ?? 'École Partenaire' }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $dossier->filiere }} ({{ $dossier->etudiants->count() }} étud.)</p>
                            </div>
                            <span class="ml-2 flex-shrink-0 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-6">Aucun dossier enregistré.</p>
                    @endforelse
                </div>
            </div>

            <!-- Taux d'Approbation Réel -->
            <div class="mt-6 p-4 rounded-2xl bg-gradient-to-br from-[#1B3A8C] to-[#0D1B4B] text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-blue-200 uppercase font-bold tracking-wider">Taux de Validation</p>
                        <h4 class="text-2xl font-black mt-0.5">{{ $tauxApprobation }}%</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION DEUXIÈME : GRAPHIQUES RÉELS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Donut Chart : Répartition par Filière (6 cols) -->
        <div class="lg:col-span-6 bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="mb-6">
                <h3 class="text-base font-extrabold text-[#0D1B4B]">Répartition par Filière</h3>
                <p class="text-xs font-medium text-slate-400">
                    @if($periodLabel !== 'Toutes les périodes') Période : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span> @else Nombre de dossiers selon les filières techniques @endif
                </p>
            </div>
            <div id="chart-filieres" class="w-full flex justify-center h-64"></div>
        </div>

        <!-- Bar Chart : Activité par École (6 cols) -->
        <div class="lg:col-span-6 bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="mb-6">
                <h3 class="text-base font-extrabold text-[#0D1B4B]">Dossiers par Université</h3>
                <p class="text-xs font-medium text-slate-400">
                    @if($periodLabel !== 'Toutes les périodes') Période : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span> @else Classement selon les dossiers déposés @endif
                </p>
            </div>
            <div id="chart-ecoles" class="w-full h-64"></div>
        </div>
    </div>

    <!-- TABLEAU RÉCENT : LISTE DES DERNIERS DOSSIERS DE STAGE -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Dossiers Récemment Soumis</h3>
                <p class="text-xs font-medium text-slate-400">Demandes de stages en provenance des établissements</p>
            </div>
            <a href="{{ route('admin.dossiers.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#1B3A8C] hover:text-[#142B6B]">
                <span>Gérer tous les dossiers</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Établissement</th>
                        <th class="py-4 px-6">Filière & Cycle</th>
                        <th class="py-4 px-6">Étudiants</th>
                        <th class="py-4 px-6">Période de Stage</th>
                        <th class="py-4 px-6">Statut</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($derniersDossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B]">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $dossier->annee_academique }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold text-slate-800">{{ $dossier->filiere }}</span>
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
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
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
document.addEventListener('DOMContentLoaded', function() {
    // Flatpickr date pickers (Thème Image 1)
    var startPicker = flatpickr('#start_date_display', {
        locale: 'fr',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D j M Y',
        maxDate: 'today',
        defaultDate: document.getElementById('start_date').value || null,
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.classList.add('flatpickr-range-theme');
        },
        onChange: function(selectedDates, dateStr) {
            document.getElementById('start_date').value = dateStr;
            endPicker.set('minDate', dateStr);
        }
    });
    var endPicker = flatpickr('#end_date_display', {
        locale: 'fr',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D j M Y',
        maxDate: 'today',
        defaultDate: document.getElementById('end_date').value || null,
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.classList.add('flatpickr-range-theme');
        },
        onChange: function(selectedDates, dateStr) {
            document.getElementById('end_date').value = dateStr;
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
