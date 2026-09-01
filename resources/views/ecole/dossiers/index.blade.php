@extends('layouts.dashboard')

@section('title', 'Mes Dossiers de Stage - STAGILOG')
@section('header_title', 'Mes Dossiers de Stage')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- BANDEAU DE STATUT DE LA CAMPAGNE DE DÉPÔT -->
    <div class="p-4 sm:p-5 rounded-3xl border shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $isDepotOpen ? 'bg-gradient-to-r from-blue-50/70 to-emerald-50/70 border-emerald-200' : 'bg-gradient-to-r from-amber-50 to-red-50 border-amber-200' }}">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $isDepotOpen ? 'bg-emerald-500 text-white shadow-md' : 'bg-amber-500 text-white shadow-md' }}">
                @if($isDepotOpen)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-xs font-black uppercase tracking-wider {{ $isDepotOpen ? 'text-[#0D1B4B]' : 'text-amber-900' }}">
                        {{ $isDepotOpen ? 'Dépôts de Dossiers Ouverts' : 'Dépôts de Dossiers Actuellement Fermés' }}
                    </h4>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $isDepotOpen ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                        {{ $isDepotOpen ? 'En cours' : 'Fermé' }}
                    </span>
                </div>
                <p class="text-xs {{ $isDepotOpen ? 'text-slate-600' : 'text-amber-800' }} mt-0.5">
                    @if($depotDebut && $depotFin)
                        Période officielle : <strong>du {{ \Carbon\Carbon::parse($depotDebut)->locale('fr')->isoFormat('D MMMM YYYY') }} au {{ \Carbon\Carbon::parse($depotFin)->locale('fr')->isoFormat('D MMMM YYYY') }}</strong>
                    @else
                        {{ $depotClosedReason ?: 'Dépôts autorisés en continu.' }}
                    @endif
                </p>
            </div>
        </div>

        @if(!$isDepotOpen)
        <span class="text-[11px] font-bold text-amber-800 bg-white/80 px-3 py-1.5 rounded-xl border border-amber-200 self-start sm:self-auto">
            Mode Brouillon autorisé
        </span>
        @endif
    </div>

    <!-- Filtres rapides par statut -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('ecole.dossiers.index') }}" 
           class="p-3.5 rounded-2xl bg-white border {{ !$status ? 'border-[#1B3A8C] ring-2 ring-[#1B3A8C]/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Tous</p>
                <h4 class="text-lg font-black text-[#0D1B4B]">{{ $countTotal }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                &Sigma;
            </div>
        </a>

        <a href="{{ route('ecole.dossiers.index', ['statut' => 'brouillon']) }}" 
           class="p-3.5 rounded-2xl bg-white border {{ $status === 'brouillon' ? 'border-slate-600 ring-2 ring-slate-600/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Brouillons</p>
                <h4 class="text-lg font-black text-slate-700">{{ $countBrouillon }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </a>

        <a href="{{ route('ecole.dossiers.index', ['statut' => 'en_attente']) }}" 
           class="p-3.5 rounded-2xl bg-white border {{ $status === 'en_attente' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600">En attente</p>
                <h4 class="text-lg font-black text-amber-600">{{ $countAttente }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('ecole.dossiers.index', ['statut' => 'sous_reserve']) }}" 
           class="p-3.5 rounded-2xl bg-white border {{ $status === 'sous_reserve' ? 'border-blue-600 ring-2 ring-blue-600/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#1B3A8C]">Sous réserve</p>
                <h4 class="text-lg font-black text-[#1B3A8C]">{{ $countSousReserve }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-blue-50 flex items-center justify-center text-[#1B3A8C]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </a>

        <a href="{{ route('ecole.dossiers.index', ['statut' => 'valide']) }}" 
           class="p-3.5 rounded-2xl bg-white border {{ $status === 'valide' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Validés</p>
                <h4 class="text-lg font-black text-emerald-600">{{ $countValide }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </a>

        <a href="{{ route('ecole.dossiers.index', ['statut' => 'refuse']) }}" 
           class="p-3.5 rounded-2xl bg-white border {{ $status === 'refuse' ? 'border-red-500 ring-2 ring-red-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-red-600">Refusés</p>
                <h4 class="text-lg font-black text-red-600">{{ $countRefuse }}</h4>
            </div>
            <div class="w-7 h-7 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </a>
    </div>

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Live Search Instantané -->
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-dossiers"
                   placeholder="Rechercher instantanément une filière, promotion, code..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] shadow-sm">
        </div>

        <!-- Bouton Déposer -->
        <a href="{{ route('ecole.dossiers.create') }}" 
           class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Nouveau Dossier</span>
        </a>
    </div>

    <!-- Tableau des Dossiers -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="dossiers-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Dossier / Réf</th>
                        <th class="py-4 px-6">Filière & Promotion</th>
                        <th class="py-4 px-6">Étudiants</th>
                        <th class="py-4 px-6">Période Prévue</th>
                        <th class="py-4 px-6 text-center">Statut du Dossier</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($dossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-4 px-6">
                            <span class="font-mono font-extrabold text-[#0D1B4B] text-xs">{{ $dossier->code_dossier ?? (auth()->user()->ecole?->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '') }}</span>
                            <div class="text-[11px] text-slate-400">{{ $dossier->created_at ? $dossier->created_at->locale('fr')->isoFormat('D MMM YYYY') : '-' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] text-sm search-target">{{ $dossier->filiere }}</div>
                            <div class="text-[11px] text-slate-500 search-target">{{ $dossier->type_stage }} • {{ $dossier->annee_academique }}</div>
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
                        <td class="py-4 px-6 text-center">
                            @if($dossier->statut_brouillon === 'brouillon')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                    BROUILLON
                                </span>
                            @elseif($dossier->statut === 'valide')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    VALIDÉ
                                </span>
                            @elseif($dossier->statut === 'refuse')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 text-red-800 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                    REFUSÉ
                                </span>
                            @elseif($dossier->statut === 'sous_reserve')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-[#1B3A8C] border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#1B3A8C] mr-1.5"></span>
                                    SOUS RÉSERVE
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    EN ATTENTE
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            @if($dossier->statut_brouillon === 'brouillon')
                                <a href="{{ route('ecole.dossiers.edit', $dossier->id_dossier) }}" 
                                   class="inline-flex items-center space-x-1 text-xs font-bold text-[#1B3A8C] hover:text-white bg-blue-50 hover:bg-[#1B3A8C] px-3.5 py-1.5 rounded-xl transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Modifier</span>
                                </a>
                                <form action="{{ route('ecole.dossiers.destroy', $dossier->id_dossier) }}" method="POST" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de ce brouillon ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-[#E8001D] rounded-lg transition" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('ecole.dossiers.show', $dossier->id_dossier) }}" 
                                   class="inline-flex items-center space-x-1 text-xs font-bold text-slate-600 hover:text-[#1B3A8C] bg-slate-100 hover:bg-blue-50 px-3.5 py-1.5 rounded-xl transition">
                                    <span>Consulter</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Aucun dossier trouvé pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dossiers->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $dossiers->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('live-search-dossiers').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#dossiers-table tbody tr.search-row');
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
