@extends('layouts.dashboard')

@section('title', 'Documents de Stage - STAGILOG')
@section('header_title', 'Documents de Stage - ' . $etudiant->nom_etudiant . ' ' . $etudiant->prenom_etudiant)

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Header Card -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.rapports.index') }}" class="p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h3 class="text-xl font-black text-[#0D1B4B]">{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</h3>
                <p class="text-xs text-slate-500 font-medium">
                    {{ $etudiant->dossier->ecole->nom_ecole ?? 'École' }} • {{ $etudiant->niveau_etude ?? $etudiant->dossier->filiere }} (Dossier <span class="font-mono font-bold text-[#1B3A8C]">{{ $etudiant->dossier->code_dossier ?? (($etudiant->dossier->ecole->sigle ?? 'STG') . '-' . ($etudiant->dossier->created_at ? $etudiant->dossier->created_at->format('dmYHi') : '')) }}</span>)
                </p>
            </div>
        </div>
        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full uppercase tracking-wider">
            Stagiaire Admis
        </span>
    </div>

    <!-- Section 1 : Formulaire d'ajout de document avec Nom Personnalisé -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8">
        <h4 class="text-sm font-extrabold text-[#0D1B4B] mb-1">Déposer un Nouveau Document</h4>
        <p class="text-xs text-slate-400 mb-6">Précisez le nom du document (Rapport, Attestation, Fiche d'évaluation...) et joignez le fichier.</p>

        <form method="POST" action="{{ route('admin.rapports.depot.store', $etudiant->id_etudiant) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom du document -->
                <div>
                    <label for="nom_document" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom / Titre du Document <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_document" id="nom_document" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Ex: Rapport de Stage Final, Attestation de stage...">
                    @error('nom_document') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Fichier joint -->
                <div>
                    <label for="fichier" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Fichier Joint (PDF, Word, Excel, ZIP) <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="file" name="fichier" id="fichier" required accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                    @error('fichier') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                    Déposer ce Document
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2 : Liste des documents déjà déposés pour cet étudiant -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8">
        <h4 class="text-sm font-extrabold text-[#0D1B4B] mb-4">Documents Déposés pour cet Étudiant ({{ $etudiant->documents->count() }})</h4>

        @if($etudiant->documents->count() > 0)
        <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
            @foreach($etudiant->documents as $doc)
            <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0D1B4B]">{{ $doc->nom_document }}</p>
                        <p class="text-[11px] text-slate-400">
                            Fichier : {{ $doc->fichier }} — {{ $doc->taille_fichier }} — Ajouté le {{ $doc->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ asset('uploads/rapports/' . $doc->fichier) }}" target="_blank" 
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#1B3A8C] rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Télécharger</span>
                    </a>

                    <form action="{{ route('admin.rapports.document.destroy', $doc->id_document) }}" method="POST" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de ce document ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-[#E8001D] hover:bg-red-50 rounded-xl transition" title="Supprimer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <p class="text-xs text-slate-400">Aucun document n'a encore été déposé pour cet étudiant.</p>
        </div>
        @endif
    </div>
</div>
@endsection
