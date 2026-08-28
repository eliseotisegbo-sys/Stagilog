@extends('layouts.dashboard')

@section('title', 'Rapports des Étudiants - STAGILOG')
@section('header_title', 'Rapports')

@section('dashboard_content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Live Search -->
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-ecole-rapports"
                   placeholder="Rechercher par nom d'étudiant, email..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] shadow-sm">
        </div>

        <!-- Bouton Étudiants en stage -->
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('ecole.rapports.stagiaires') }}"
               class="group relative inline-flex items-center space-x-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300/80 px-4 py-2.5 rounded-2xl text-xs font-black shadow-sm hover:shadow-md transition-all duration-300">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="hidden sm:inline">Étudiants en stage actuellement</span>
                <span class="sm:hidden">En stage</span>
            </a>
        </div>
    </div>

    <!-- Tableau Livrables -->
    <div id="section-all-rapports" class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100">
            <h3 class="text-sm font-extrabold text-[#0D1B4B]">Livrables &amp; Documents des Étudiants</h3>
            <p class="text-[11px] text-slate-400">Accédez aux rapports et attestations déposés par TFG SARL</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[800px]" id="ecole-rapports-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Noms et Prénoms</th>
                        <th class="py-3 px-4">Niveau &amp; Filière</th>
                        <th class="py-3 px-4">Dossier</th>
                        <th class="py-3 px-4">Période de Stage</th>
                        <th class="py-3 px-4">Documents</th>
                        <th class="py-3 px-4 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($etudiants as $etu)
                    @php
                        $debut = $etu->dossier && $etu->dossier->datedebut ? \Carbon\Carbon::parse($etu->dossier->datedebut)->startOfDay() : null;
                        $fin   = $etu->dossier && $etu->dossier->datefin  ? \Carbon\Carbon::parse($etu->dossier->datefin)->endOfDay()   : null;
                        $now   = now();
                        if (!$debut || !$fin) {
                            $stageStatus = 'unknown'; $stageLabel = 'Dates non définies'; $stageBadge = 'bg-slate-100 text-slate-500';
                        } elseif ($now->lt($debut)) {
                            $d = (int) $now->diffInDays($debut);
                            $stageStatus = 'upcoming';
                            $stageLabel  = $d === 0 ? 'Commence demain' : "Dans $d jour" . ($d > 1 ? 's' : '');
                            $stageBadge  = 'bg-blue-100 text-[#1B3A8C]';
                        } elseif ($now->lte($fin)) {
                            $tot = max(1, $debut->diffInDays($fin));
                            $pct = min(100, max(0, round(($debut->diffInDays($now) / $tot) * 100)));
                            $stageStatus = 'ongoing'; $stageLabel = "En cours ($pct%)"; $stageBadge = 'bg-emerald-100 text-emerald-800';
                        } else {
                            $d = (int) $fin->diffInDays($now);
                            $stageStatus = 'finished';
                            if ($d === 0)      $stageLabel = "Terminé aujourd'hui";
                            elseif ($d < 7)    $stageLabel = "Fini il y a $d j.";
                            elseif ($d < 30)   { $w = floor($d/7);  $stageLabel = "Fini il y a $w sem."; }
                            elseif ($d < 365)  { $m = floor($d/30); $stageLabel = "Fini il y a $m mois"; }
                            else               { $y = floor($d/365);$stageLabel = "Fini il y a $y an" . ($y>1?'s':''); }
                            $stageBadge = 'bg-slate-100 text-slate-600';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-3 px-4">
                            <div class="font-bold text-[#0D1B4B] search-target">{{ $etu->nom_etudiant }} {{ $etu->prenom_etudiant }}</div>
                            <div class="text-[11px] text-slate-400 search-target">{{ $etu->email_etu }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[10px] uppercase">{{ $etu->niveau_etude ?? ($etu->dossier->filiere ?? '-') }}</span>
                            <div class="text-[11px] text-slate-600 mt-0.5 search-target">{{ $etu->dossier->filiere ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-mono font-bold text-[#1B3A8C] text-[11px]">{{ $etu->dossier->code_dossier ?? ((auth()->user()->ecole?->sigle ?? 'STG') . '-' . ($etu->dossier->created_at ? $etu->dossier->created_at->format('dmYHi') : '')) }}</div>
                            <div class="text-[11px] text-emerald-600 font-semibold">Validé TFG</div>
                        </td>
                        <td class="py-3 px-4">
                            @if($debut && $fin)
                            <div class="text-[11px] font-semibold text-[#0D1B4B] lowercase leading-tight">
                                {{ $debut->locale('fr')->isoFormat('D MMM YY') }}
                                <span class="text-slate-400 mx-1">au</span>
                                {{ $fin->locale('fr')->isoFormat('D MMM YY') }}
                            </div>
<div class="text-[10px] text-slate-400">{{ (int) round(max(1, $debut->diffInDays($fin))) }} jours</div>                            @endif
                            <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $stageBadge }}">
                                @if($stageStatus === 'ongoing')<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block flex-shrink-0"></span>
                                @elseif($stageStatus === 'upcoming')<svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif($stageStatus === 'finished')<svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                                {{ $stageLabel }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($etu->documents->count() > 0)
                                <div class="space-y-1">
                                    @foreach($etu->documents as $doc)
                                    <a href="{{ asset('uploads/rapports/' . $doc->fichier) }}" target="_blank" 
                                       class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#1B3A8C] rounded-xl font-bold text-[10px] border border-blue-100 transition shadow-sm w-full">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span class="truncate">{{ $doc->nom_document }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            @elseif($etu->rapport)
                                <a href="{{ asset('uploads/rapports/' . $etu->rapport) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-[10px] transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Rapport</span>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[10px] font-medium">Aucun rapport</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($etu->documents->count() > 0 || $etu->rapport)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Disponibles</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold text-[10px]">En attente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Aucun stagiaire validé pour le moment. Les étudiants apparaîtront dès la validation de leur dossier.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($etudiants->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">{{ $etudiants->links() }}</div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('live-search-ecole-rapports').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#ecole-rapports-table tbody tr.search-row').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
