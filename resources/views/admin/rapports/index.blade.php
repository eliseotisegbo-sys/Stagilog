@extends('layouts.dashboard')

@section('title', 'Rapports - STAGILOG')
@section('header_title', 'Rapports')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- Top Action & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Live Search Instantané -->
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-rapports"
                   placeholder="Rechercher un stagiaire, école, filière..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] shadow-sm">
        </div>

        <!-- Bouton "Étudiants en stage actuellement" (Vert Léger + Animation Pulse & Glow) -->
        <div class="flex items-center gap-3">
            <button type="button" id="btn-toggle-active-interns" onclick="toggleActiveInternsView()"
                    class="group relative inline-flex items-center space-x-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300/80 px-4 py-2.5 rounded-2xl text-xs font-black shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                <span id="btn-active-interns-text">Étudiants en stage actuellement</span>
            </button>
        </div>
    </div>

    <!-- TABLEAU : ÉTUDIANTS EN STAGE ACTUELLEMENT (Progression & Période) -->
    <div id="section-active-interns" class="bg-white rounded-3xl shadow-card border border-emerald-200/80 overflow-hidden transition-all duration-300">
        <div class="p-6 border-b border-emerald-100 bg-gradient-to-r from-emerald-50/70 to-teal-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#0D1B4B]">Étudiants en Stage Actuellement</h3>
                    <p class="text-[11px] text-slate-500">Suivi en temps réel de la période de stage et du niveau d'avancement</p>
                </div>
            </div>
            <span class="inline-flex items-center space-x-1.5 px-3 py-1 bg-white text-emerald-700 rounded-full text-xs font-bold border border-emerald-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Suivi d'activité TFG SARL</span>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="active-interns-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Noms et Prénoms</th>
                        <th class="py-4 px-6">Niveau & Filière</th>
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
                    <tr class="hover:bg-emerald-50/30 transition search-row">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] text-sm search-target">{{ $etu->nom_etudiant }} {{ $etu->prenom_etudiant }}</div>
                            <div class="text-[11px] text-slate-400 search-target">{{ $etu->email_etu }}</div>
                            <div class="text-[10px] text-[#1B3A8C] font-semibold mt-0.5">{{ $etu->dossier->ecole->nom_ecole ?? 'École Partenaire' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[10px] uppercase">
                                {{ $etu->niveau_etude ?? $etu->dossier->filiere }}
                            </span>
                            <div class="text-[11px] text-slate-600 font-medium mt-0.5 search-target">{{ $etu->dossier->filiere }}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-700 lowercase">
                            <div class="font-semibold text-[#0D1B4B]">
                                {{ $debut ? $debut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                                <span class="text-slate-400 mx-1">au</span>
                                {{ $fin ? $fin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                            </div>
                            @if($debut && $fin)
                            <div class="text-[10px] text-slate-400 capitalize mt-0.5">
                                Durée totale : {{ max(1, $debut->diffInDays($fin)) }} jours
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
                            Aucun stagiaire en cours actuellement.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tableau Général des Rapports / Stagiaires Admis -->
    <div id="section-all-rapports" class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-extrabold text-[#0D1B4B]">Tous les Rapports & Livrables</h3>
                <p class="text-[11px] text-slate-400">Dossiers validés et documents déposés par stagiaire</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="rapports-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Stagiaire Admis</th>
                        <th class="py-4 px-6">Niveau & Filière</th>
                        <th class="py-4 px-6">Université Partenaire</th>
                        <th class="py-4 px-6">Documents Déposés</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($etudiants as $etu)
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] text-sm search-target">{{ $etu->nom_etudiant }} {{ $etu->prenom_etudiant }}</div>
                            <div class="text-[11px] text-slate-400 search-target">{{ $etu->email_etu }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[10px] uppercase">
                                {{ $etu->niveau_etude ?? $etu->dossier->filiere }}
                            </span>
                            <div class="text-[11px] text-slate-600 font-medium mt-0.5 search-target">{{ $etu->dossier->filiere }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800 search-target">{{ $etu->dossier->ecole->nom_ecole ?? 'N/A' }}</div>
                            <div class="text-[11px] text-[#1B3A8C] font-mono font-bold">{{ $etu->dossier->code_dossier ?? (($etu->dossier->ecole->sigle ?? 'STG') . '-' . ($etu->dossier->created_at ? $etu->dossier->created_at->format('dmYHi') : '')) }}</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($etu->documents->count() > 0)
                                <div class="space-y-1.5">
                                    @foreach($etu->documents as $doc)
                                    <a href="{{ asset('uploads/rapports/' . $doc->fichier) }}" target="_blank" 
                                       class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-slate-50 hover:bg-blue-50 text-[#1B3A8C] rounded-lg font-bold text-[11px] border border-slate-200 transition">
                                        <svg class="w-3.5 h-3.5 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ $doc->nom_document }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            @elseif($etu->rapport)
                                <a href="{{ asset('uploads/rapports/' . $etu->rapport) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-bold text-[11px]">
                                    <span>Rapport Principal</span>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Aucun document déposé</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.rapports.depot', $etu->id_etudiant) }}" 
                               class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-xl font-bold text-xs shadow-md transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Dépot de Rapport</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            Aucun stagiaire validé pour le moment. Les étudiants apparaîtront ici dès que leur dossier sera validé.
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
document.getElementById('live-search-rapports').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.search-row');
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function toggleActiveInternsView() {
    const activeSection = document.getElementById('section-active-interns');
    activeSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    activeSection.classList.add('ring-4', 'ring-emerald-400/30');
    setTimeout(() => activeSection.classList.remove('ring-4', 'ring-emerald-400/30'), 1500);
}
</script>
@endpush
@endsection
