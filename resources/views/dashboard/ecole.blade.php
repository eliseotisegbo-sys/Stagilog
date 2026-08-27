@extends('layouts.dashboard')

@section('title', 'Tableau de Bord École - STAGILOG')
@section('header_title', 'Espace École Partenaire')

@section('dashboard_content')
<div class="space-y-8">
    
    <!-- SALUTATION BANNER SANS EMOJI -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0D1B4B] tracking-tight">
                {{ $salutation }}, {{ $userName }}
            </h1>
            <p class="text-sm font-medium text-[#6B7AA1] mt-1">
                {{ $ecole->nom_ecole ?? 'Établissement Partenaire' }} &mdash; Suivi de vos promotions et des évaluations de stage.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('ecole.dossiers.create') }}" class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg hover:shadow-blue-900/20 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nouveau Dossier de Stage</span>
            </a>
        </div>
    </div>

    <!-- SÉLECTEUR DE PÉRIODE -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-card p-5">
        <form method="GET" action="{{ route('dashboard.ecole') }}" id="period-form" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 rounded-xl bg-[#EEF4FF] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-bold text-[#0D1B4B] whitespace-nowrap">Filtrer par période</span>
            </div>
            <div class="flex-1 flex flex-col sm:flex-row items-center gap-3 w-full">
                <div class="relative flex-1 w-full">
                    <input type="text" id="start_date_display" placeholder="Date de début"
                        class="w-full pl-3 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-[#0D1B4B] focus:ring-2 focus:ring-[#1B3A8C] focus:border-transparent outline-none cursor-pointer bg-slate-50 transition"
                        readonly>
                    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                </div>
                <span class="text-slate-400 font-bold text-xs hidden sm:block">→</span>
                <div class="relative flex-1 w-full">
                    <input type="text" id="end_date_display" placeholder="Date de fin"
                        class="w-full pl-3 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-[#0D1B4B] focus:ring-2 focus:ring-[#1B3A8C] focus:border-transparent outline-none cursor-pointer bg-slate-50 transition"
                        readonly>
                    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="submit" class="inline-flex items-center gap-1.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filtrer
                </button>
                @if(request('start_date'))
                <a href="{{ route('dashboard.ecole') }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
                @endif
            </div>
            @if($periodLabel !== 'Toutes les périodes')
            <div class="flex items-center gap-2 ml-auto">
                <span class="inline-flex items-center gap-1.5 bg-[#EEF4FF] text-[#1B3A8C] text-[11px] font-bold px-3 py-1.5 rounded-full border border-[#BFDBFE]">
                    {{ $periodLabel }}
                </span>
            </div>
            @endif
        </form>
    </div>

    <!-- 4 KPI CARDS (Vraies valeurs de la base de données) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Mes Dossiers -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Dossiers</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $totalDossiers }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $dossiersBrouillon }} en brouillon</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Dossiers Validés -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Dossiers Validés</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-emerald-600">{{ $dossiersValides }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Acceptés par TFG SARL</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: En Cours d'Instruction -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">En Examen</span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-amber-600">{{ $dossiersEnAttente }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $dossiersRefuses }} refusés</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Rapports Déposés -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rapports</span>
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $rapportsDisponibles }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Sur {{ $totalEtudiants }} étudiant(s)</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUE & ACTIONS RAPIDES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Courbe Activité École Réelle (8 cols) -->
        <div class="lg:col-span-8 bg-white p-8 rounded-3xl shadow-card border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-[#0D1B4B]">Activité des Soumissions</h3>
                    <p class="text-xs font-medium text-slate-400">
                        @if($periodLabel !== 'Toutes les périodes')
                            Période : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span>
                        @else
                            Dossiers déposés par votre établissement (6 derniers mois)
                        @endif
                    </p>
                </div>
            </div>

            <div id="chart-ecole-timeline" class="w-full h-72"></div>
        </div>

        <!-- Raccourcis & Assistance (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Box Action Rapide -->
            <div class="bg-gradient-to-br from-[#1B3A8C] to-[#0D1B4B] p-6 rounded-3xl text-white shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold mb-1">Inscrire une nouvelle promotion ?</h4>
                <p class="text-xs text-blue-200 mb-6 leading-relaxed">
                    Créez un dossier complet avec les CVs des étudiants et la note de demande officielle.
                </p>
                <a href="{{ route('ecole.dossiers.create') }}" class="inline-flex items-center justify-center w-full bg-white text-[#1B3A8C] hover:bg-blue-50 py-3 rounded-2xl text-xs font-black shadow transition">
                    Créer un nouveau dossier
                </a>
            </div>
        </div>
    </div>

    <!-- TABLEAU DE VOS DOSSIERS -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Mes Dossiers Récents</h3>
                <p class="text-xs font-medium text-slate-400">Statut de traitement de vos demandes de stages</p>
            </div>
            <a href="{{ route('ecole.dossiers.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#1B3A8C] hover:text-[#142B6B]">
                <span>Voir la liste complète</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Filière & Promotion</th>
                        <th class="py-4 px-6">Cycle</th>
                        <th class="py-4 px-6">Étudiants</th>
                        <th class="py-4 px-6">Période Prévue</th>
                        <th class="py-4 px-6">Statut Soumission</th>
                        <th class="py-4 px-6">Validation TFG</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($recentsDossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B]">{{ $dossier->filiere }}</div>
                            <div class="text-[11px] text-slate-400">Année : {{ $dossier->annee_academique }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[11px]">
                                {{ $dossier->cycle->nom_cycle ?? 'Licence / Master' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold">{{ $dossier->etudiants->count() }} étudiant(s)</span>
                        </td>
                        <td class="py-4 px-6 text-slate-600 text-xs lowercase">
                            {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                            <span class="text-slate-400 mx-1">au</span>
                            {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $dossier->statut_brouillon === 'soumis' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $dossier->statut_brouillon === 'soumis' ? 'Soumis' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('ecole.dossiers.show', $dossier->id_dossier) }}" 
                               class="inline-flex items-center text-xs font-bold text-[#1B3A8C] hover:underline">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">Aucun dossier enregistré pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar { font-family: 'Plus Jakarta Sans', sans-serif !important; border-radius: 16px !important; box-shadow: 0 20px 60px rgba(13,27,75,0.15) !important; border: 1px solid #E2E8F0 !important; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #1B3A8C !important; border-color: #1B3A8C !important; }
    .flatpickr-day.inRange { background: #EEF4FF !important; border-color: #EEF4FF !important; color: #1B3A8C !important; }
    .flatpickr-months .flatpickr-month { background: #0D1B4B !important; border-radius: 16px 16px 0 0 !important; }
    .flatpickr-current-month, .flatpickr-monthDropdown-months, .flatpickr-weekday { color: white !important; }
    .flatpickr-day:hover { background: #EEF4FF !important; color: #1B3A8C !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var startPicker = flatpickr('#start_date_display', {
        locale: 'fr',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D j M Y',
        maxDate: 'today',
        defaultDate: document.getElementById('start_date').value || null,
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
        onChange: function(selectedDates, dateStr) {
            document.getElementById('end_date').value = dateStr;
        }
    });

    var options = {
        series: [{
            name: 'Dossiers',
            data: @json($dossiersData)
        }],
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        colors: ['#10B981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
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
            categories: @json($months),
            labels: { style: { colors: '#6B7AA1', fontSize: '11px', fontWeight: 600 } },
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
    var chart = new ApexCharts(document.querySelector("#chart-ecole-timeline"), options);
    chart.render();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@endpush

@endsection
