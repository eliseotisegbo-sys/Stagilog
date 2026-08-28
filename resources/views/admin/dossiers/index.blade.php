@extends('layouts.dashboard')

@section('title', 'Dossiers de Stage - STAGILOG')
@section('header_title', 'Dossiers de Stage')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- Filtres rapides par statut -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.dossiers.index') }}" 
           class="p-4 rounded-2xl bg-white border {{ !$status ? 'border-[#1B3A8C] ring-2 ring-[#1B3A8C]/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tous les dossiers</p>
                <h4 class="text-xl font-black text-[#0D1B4B]">{{ $countTotal }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                &Sigma;
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'en_attente']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'en_attente' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">En attente</p>
                <h4 class="text-xl font-black text-amber-600">{{ $countAttente }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'valide']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'valide' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Validés</p>
                <h4 class="text-xl font-black text-emerald-600">{{ $countValide }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'refuse']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'refuse' ? 'border-red-500 ring-2 ring-red-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-red-600">Refusés</p>
                <h4 class="text-xl font-black text-red-600">{{ $countRefuse }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </a>
    </div>

    <!-- Barre de Recherche Instantanée -->
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-admin-dossiers"
                   placeholder="Rechercher instantanément par code, école, filière, cycle..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white shadow-sm transition">
        </div>
    </div>

    <!-- Tableau des dossiers -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="admin-dossiers-table" style="font-size: 0.8125rem !important;">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-extrabold border-b border-slate-100" style="font-size: 0.6875rem !important;">
                    <tr>
                        <th class="py-4 px-6">ID & Université</th>
                        <th class="py-4 px-6">Filière / Cycle</th>
                        <th class="py-4 px-6">Type & Promotion</th>
                        <th class="py-4 px-6">Étudiants</th>
                        <th class="py-4 px-6">Période</th>
                        <th class="py-4 px-6">Statut TFG</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($dossiers as $dossier)
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] search-target font-mono" style="font-size: 0.8125rem;">{{ $dossier->code_dossier ?? (($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '')) }}</div>
                            <div class="font-semibold text-slate-600 font-sans mt-0.5" style="font-size: 0.6875rem;">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</div>
                            <div class="text-slate-400" style="font-size: 0.6875rem;">Année : {{ $dossier->annee_academique }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900 search-target" style="font-size: 0.8125rem;">{{ $dossier->filiere }}</div>
                            <div class="text-[#1B3A8C] font-extrabold mt-0.5" style="font-size: 0.6875rem;">{{ $dossier->cycle->nom_cycle ?? 'Standard' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-slate-800 font-semibold search-target" style="font-size: 0.75rem;">{{ $dossier->type_stage ?? 'Stage professionnel' }}</div>
                            <div class="text-slate-400 mt-0.5" style="font-size: 0.6875rem;">{{ $dossier->annee_academique }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-[#1B3A8C] font-extrabold" style="font-size: 0.6875rem;">
                                {{ $dossier->etudiants->count() }} étudiant(s)
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-700 font-medium capitalize" style="font-size: 0.75rem;">
                            {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('D MMM YYYY') : '-' }}
                            <span class="text-slate-400 mx-1">au</span>
                            {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('D MMM YYYY') : '-' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-black uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}" style="font-size: 0.6875rem;">
                                {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('admin.dossiers.show', $dossier->id_dossier) }}" 
                               class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-[#1B3A8C] text-white hover:bg-[#142B6B] rounded-xl font-bold shadow-sm transition" style="font-size: 0.6875rem;">
                                <span>Examiner</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400" style="font-size: 0.875rem;">
                            Aucun dossier de stage trouvé pour cette sélection.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
document.getElementById('live-search-admin-dossiers').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#admin-dossiers-table tbody tr.search-row');
    
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
