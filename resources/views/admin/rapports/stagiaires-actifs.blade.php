@extends('layouts.dashboard')

@section('title', 'Étudiants en Stage Actuellement - STAGILOG')
@section('header_title', 'Étudiants en Stage Actuellement')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- Top Action & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Live Search Instantané -->
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-stagiaires"
                   placeholder="Rechercher par étudiant, filière, école..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] shadow-sm">
        </div>

        <!-- Bouton Retour -->
        <div>
            <a href="{{ route('admin.rapports.index') }}" 
               class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-[#1B3A8C] border border-slate-200 rounded-2xl text-xs font-bold shadow-sm transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Retour aux Rapports</span>
            </a>
        </div>
    </div>

    <!-- TABLEAU ÉPURÉ : ÉTUDIANTS EN STAGE ACTUELLEMENT -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="stagiaires-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Noms et Prénoms</th>
                        <th class="py-4 px-6">Niveau &amp; Filière</th>
                        <th class="py-4 px-6">Période du Stage</th>
                        <th class="py-4 px-6 text-right">Progression</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($etudiants as $etu)
                    @php
                        $debut = $etu->dossier && $etu->dossier->datedebut ? \Carbon\Carbon::parse($etu->dossier->datedebut)->startOfDay() : null;
                        $fin = $etu->dossier && $etu->dossier->datefin ? \Carbon\Carbon::parse($etu->dossier->datefin)->endOfDay() : null;
                        $now = now();
                        $isTermine = $fin && $now->gte($fin);
                        $progressPercent = 0;
                        if ($debut && $fin) {
                            if ($now->lte($debut)) {
                                $progressPercent = 0;
                            } elseif ($now->gte($fin)) {
                                $progressPercent = 100;
                            } else {
                                $totalDays = max(1, $debut->diffInDays($fin));
                                $passedDays = $debut->diffInDays($now);
                                $progressPercent = min(100, max(0, round(($passedDays / $totalDays) * 100)));
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] text-sm search-target">{{ $etu->nom_etudiant }} {{ $etu->prenom_etudiant }}</div>
                            <div class="text-[11px] text-slate-400 search-target">{{ $etu->email_etu }}</div>
                            <div class="text-[10px] text-[#1B3A8C] font-semibold mt-0.5 search-target">{{ $etu->dossier->ecole->nom_ecole ?? 'École Partenaire' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[10px] uppercase">
                                {{ $etu->niveau_etude ?? $etu->dossier->filiere }}
                            </span>
                            <div class="text-[11px] text-slate-600 font-medium mt-0.5 search-target">{{ $etu->dossier->filiere }}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-700 lowercase">
                            <div class="font-semibold text-[#0D1B4B]">
                                {{ $debut ? $debut->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                                <span class="text-slate-400 mx-1">au</span>
                                {{ $fin ? $fin->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                            </div>
                            @if($debut && $fin)
                            <div class="text-[10px] text-slate-400 capitalize mt-0.5">
                                Durée : {{ round(max(1, $debut->diffInDays($fin))) }} jours
                            </div>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex flex-col items-end">
                                @if($isTermine)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] border border-emerald-200 shadow-sm mb-1.5">
                                        <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Terminé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-[#1B3A8C] font-bold text-[10px] border border-blue-200 mb-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1B3A8C] mr-1.5 animate-pulse"></span>
                                        En cours ({{ $progressPercent }}%)
                                    </span>
                                @endif
                                
                                <div class="w-28 bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="h-2 rounded-full transition-all duration-500 {{ $isTermine ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#1B3A8C] to-blue-500' }}"
                                         style="width: {{ $progressPercent }}%;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400">
                            Aucun stagiaire en cours de stage pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($etudiants->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $etudiants->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('live-search-stagiaires').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#stagiaires-table tbody tr.search-row');
    
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
