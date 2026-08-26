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
    </div>

    <!-- Tableau des Rapports / Stagiaires Admis -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
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
                                <span>Gérer les Documents</span>
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
    const rows = document.querySelectorAll('#rapports-table tbody tr.search-row');
    
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
