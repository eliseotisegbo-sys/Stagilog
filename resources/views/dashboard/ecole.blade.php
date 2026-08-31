@extends('layouts.dashboard')

@section('title', 'Espace Établissement - STAGILOG')
@section('header_title', 'Tableau de Bord Établissement')

@section('dashboard_content')
<div class="space-y-8">
    
    <!-- SALUTATION BANNER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0D1B4B] tracking-tight">
                {{ $salutation }}, {{ $ecole->nom_ecole ?? 'École Partenaire' }}
            </h1>
            <p class="text-sm font-medium text-[#6B7AA1] mt-1">
                Suivi de vos demandes de stage et des rapports académiques TFG SARL.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('ecole.dossiers.create') }}" class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nouveau Dossier</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Total Dossiers -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Dossiers Déposés</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-[#0D1B4B]">{{ $totalDossiers }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Total des soumissions</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Dossiers Validés -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Validés par TFG</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-emerald-600">{{ $dossiersValides }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Dossiers approuvés</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: En Attente de Validation -->
        <div class="bg-white p-6 rounded-3xl shadow-card border border-slate-100/80 hover:shadow-hover transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">En Examen</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-amber-600">{{ $dossiersEnAttente }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">En cours de traitement</p>
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

    <!-- SÉLECTEUR DE PÉRIODE AVANCÉ POUR L'ACTIVITÉ (COMBO JOUR / SEMAINE / ANNÉE) -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-card p-5 relative">
        <form method="GET" action="{{ route('dashboard.ecole') }}" id="period-form" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#EEF4FF] text-[#1B3A8C] flex items-center justify-center shadow-inner flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#0D1B4B]">Période d'Activité</h4>
                    <p class="text-[11px] text-slate-400">Filtrer par jour, semaine ou année avec double calendrier</p>
                </div>
            </div>

            <!-- Trigger Button & Popover Container -->
            <div class="relative flex items-center gap-3">
                <button type="button" onclick="toggleDatePopover()" id="btn-date-trigger"
                        class="inline-flex items-center space-x-3 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-2xl text-xs font-bold text-[#0D1B4B] shadow-sm transition">
                    <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span id="date-summary-label">{{ $periodLabel }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                @if(request('start_date'))
                <a href="{{ route('dashboard.ecole') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Réinitialiser</span>
                </a>
                @endif

                <!-- POPOVER ADVANCED CALENDAR & PRESETS (Sélectionneurs Jour, Semaine, Année) -->
                <div id="date-popover" class="hidden absolute right-0 top-full mt-3 z-50 bg-white rounded-3xl shadow-2xl border border-slate-200 p-6 flex flex-col md:flex-row gap-6 w-full sm:w-[740px]">
                    <!-- Colonne des raccourcis : Jour, Semaine, Année, Toutes les périodes -->
                    <div class="w-full md:w-44 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-4 flex flex-col space-y-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Raccourcis</span>
                        <button type="button" onclick="selectDatePreset('jour')" class="preset-btn text-left px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-[#1B3A8C] transition flex items-center justify-between">
                            <span>Jour</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" onclick="selectDatePreset('semaine')" class="preset-btn text-left px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-[#1B3A8C] transition flex items-center justify-between">
                            <span>Semaine</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" onclick="selectDatePreset('annee')" class="preset-btn text-left px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-[#1B3A8C] transition flex items-center justify-between">
                            <span>Année</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div class="pt-2 border-t border-slate-100 mt-2">
                            <button type="button" onclick="selectDatePreset('all')" class="preset-btn text-left px-3.5 py-2 rounded-xl text-xs font-bold text-[#1B3A8C] bg-blue-50/60 hover:bg-blue-100 transition w-full">
                                Toutes les périodes
                            </button>
                        </div>
                    </div>

                    <!-- Zone Calendrier Double Mois & Barre de validation -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="overflow-x-auto">
                            <div id="flatpickr-inline-target" class="flex justify-center min-h-[290px]"></div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
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
            </div>
        </form>
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
                            Période sélectionnée : <span class="text-[#1B3A8C] font-bold">{{ $periodLabel }}</span>
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
                    Créez un dossier complet avec les dates de stage individuelles des étudiants et la note officielle.
                </p>
                <a href="{{ route('ecole.dossiers.create') }}" class="inline-flex items-center justify-center w-full bg-white text-[#1B3A8C] hover:bg-blue-50 py-3 rounded-2xl text-xs font-black shadow transition">
                    Créer un nouveau dossier
                </a>
            </div>
        </div>
    </div>

    <!-- TABLEAU RÉCENT -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Derniers Dossiers Enregistrés</h3>
                <p class="text-xs font-medium text-slate-400">Historique de vos dossiers de stage soumis à TFG SARL</p>
            </div>
            <a href="{{ route('ecole.dossiers.index') }}" class="text-xs font-bold text-[#1B3A8C] hover:underline">
                Voir tous mes dossiers &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Réf Dossier</th>
                        <th class="py-4 px-6">Filière / Spécialité</th>
                        <th class="py-4 px-6">Promotion</th>
                        <th class="py-4 px-6">Étudiants</th>
                        <th class="py-4 px-6">Période Demandée</th>
                        <th class="py-4 px-6">Statut TFG</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($derniersDossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6 font-mono font-bold text-[#0D1B4B]">
                            {{ $dossier->code_dossier ?? ($ecole->sigle . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '')) }}
                        </td>
                        <td class="py-4 px-6 font-bold text-[#0D1B4B]">
                            {{ $dossier->filiere }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $dossier->annee_academique }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-[#1B3A8C] font-bold">
                                {{ $dossier->etudiants->count() }} étudiant(s)
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-600 text-xs lowercase">
                            {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                            <span class="text-slate-400 mx-1">au</span>
                            {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            @if($dossier->statut_brouillon === 'brouillon')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                    Brouillon
                                </span>
                            @elseif($dossier->statut === 'valide')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                    Validé
                                </span>
                            @elseif($dossier->statut === 'refuse')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700">
                                    Refusé
                                </span>
                            @elseif($dossier->statut === 'sous_reserve')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-[#1B3A8C]">
                                    Sous réserve
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">
                                    En attente
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
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

function selectDatePreset(mode) {
    const today = new Date();
    let start = null;
    let end = null;

    if (mode === 'jour') {
        start = new Date(today);
        end = new Date(today);
    } else if (mode === 'semaine') {
        const day = today.getDay();
        const diffToMon = today.getDate() - day + (day === 0 ? -6 : 1);
        start = new Date(today.getFullYear(), today.getMonth(), diffToMon);
        end = new Date(today.getFullYear(), today.getMonth(), diffToMon + 6);
    } else if (mode === 'annee') {
        start = new Date(today.getFullYear(), 0, 1);
        end = new Date(today.getFullYear(), 11, 31);
    } else if (mode === 'all') {
        clearDatePreset();
        return;
    }

    if (start && end) {
        const sStr = formatDate(start);
        const eStr = formatDate(end);
        document.getElementById('start_date').value = sStr;
        document.getElementById('end_date').value = eStr;
        document.getElementById('popover_start_display').value = sStr;
        document.getElementById('popover_end_display').value = eStr;
        if (fpRangePicker) {
            fpRangePicker.setDate([sStr, eStr], true);
        }
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

    // Initialize Flatpickr in range mode with dual month view
    if (typeof flatpickr !== 'undefined') {
        flatpickr.localize(flatpickr.l10ns.fr);
        fpRangePicker = flatpickr("#flatpickr-inline-target", {
            inline: true,
            mode: "range",
            showMonths: 2,
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

    document.addEventListener('click', function(e) {
        const pop = document.getElementById('date-popover');
        const trigger = document.getElementById('btn-date-trigger');
        if (pop && !pop.classList.contains('hidden') && !pop.contains(e.target) && !trigger.contains(e.target)) {
            pop.classList.add('hidden');
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
