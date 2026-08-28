@extends('layouts.dashboard')

@section('title', 'Documents de Stage - STAGILOG')
@section('header_title', 'Documents de Stage - ' . $etudiant->nom_etudiant . ' ' . $etudiant->prenom_etudiant)

@section('dashboard_content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Header Card -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.rapports.index') }}" class="p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition" title="Retour aux rapports">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h3 class="text-xl font-black text-[#0D1B4B]">{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</h3>
                <p class="text-xs text-slate-500 font-medium">
                    {{ $etudiant->dossier->ecole->nom_ecole ?? 'École' }} • {{ $etudiant->niveau_etude ?? $etudiant->dossier->filiere }} (Dossier <span class="font-mono font-bold text-[#1B3A8C]">{{ $etudiant->dossier->code_dossier ?? (($etudiant->dossier->ecole->sigle ?? 'STG') . '-' . ($etudiant->dossier->created_at ? $etudiant->dossier->created_at->format('dmYHi') : '')) }}</span>)
                </p>
                @if($datedebut)
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Période de stage : <strong>{{ $datedebut->format('d/m/Y') }}</strong> au <strong>{{ $etudiant->dossier->datefin ? $etudiant->dossier->datefin->format('d/m/Y') : '-' }}</strong>
                </p>
                @endif
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            @if($stageCommence)
                <span class="px-3.5 py-1.5 bg-emerald-100 text-emerald-800 font-extrabold text-xs rounded-full uppercase tracking-wider flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Stage en cours / Dépôt Débloqué</span>
                </span>
            @else
                <span class="px-3.5 py-1.5 bg-amber-100 text-amber-800 font-extrabold text-xs rounded-full uppercase tracking-wider flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Début le {{ $datedebut->format('d/m/Y') }}</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Alertes & Alerts de la session -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('info'))
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('info') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Banner Information si le stage n'a pas démarré -->
    @if(!$stageCommence && $datedebut)
    <div class="p-5 bg-amber-50/90 border border-amber-200 rounded-3xl text-amber-900 flex items-start space-x-4 shadow-sm">
        <div>
            <h4 class="text-sm font-extrabold text-amber-900">Dépôt officiel verrouillé jusqu'au début de la période de stage</h4>
            <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                Le stage de cet étudiant est programmé du <strong>{{ $datedebut->format('d/m/Y') }}</strong> au <strong>{{ $etudiant->dossier->datefin ? $etudiant->dossier->datefin->format('d/m/Y') : '-' }}</strong>. 
                Vous pouvez préparer vos documents ci-dessous et les enregistrer en <strong>Brouillon</strong>. La publication définitive et la transmission aux écoles seront débloquées à la date de début de stage.
            </p>
        </div>
    </div>
    @endif

    <!-- Section 1 : Formulaire d'ajout Multi-Documents (Brouillons & Définitions) -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8 space-y-6">
        <div>
            <h4 class="text-sm font-extrabold text-[#0D1B4B]">Déposer des Documents pour cet Étudiant</h4>
            <p class="text-xs text-slate-400 mt-1">Vous pouvez ajouter plusieurs fichiers en même temps (Rapport de stage, Attestation, Procès-verbal, Fiche d'évaluation...) et choisir de les mettre en brouillon ou de les publier.</p>
        </div>

        <form method="POST" action="{{ route('admin.rapports.depot.store', $etudiant->id_etudiant) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Conteneur dynamique des lignes de documents -->
            <div id="document-rows-container" class="space-y-4">
                <!-- Ligne 1 (par défaut) -->
                <div class="doc-row p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="doc-row-label text-xs font-black text-[#0D1B4B] uppercase tracking-wider">Document 1</span>
                        <button type="button" onclick="removeDocRow(this)" class="btn-remove-row hidden text-slate-400 hover:text-[#E8001D] text-xs font-bold flex items-center space-x-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Supprimer</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Nom / Titre du Document <span class="text-[#E8001D]">*</span>
                            </label>
                            <input type="text" name="documents[0][nom_document]" required
                                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] transition"
                                   placeholder="Ex: Rapport de Stage Final, Attestation...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Fichier Joint (PDF, Word, Excel, ZIP) <span class="text-[#E8001D]">*</span>
                            </label>
                            <input type="file" name="documents[0][fichier]" required accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton Ajouter un autre document -->
            <div class="flex items-center justify-start">
                <button type="button" onclick="addDocRow()" 
                        class="inline-flex items-center space-x-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-[#1B3A8C] rounded-2xl text-xs font-extrabold border border-blue-200/60 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajouter un autre document</span>
                </button>
            </div>

            <!-- Boutons d'Action (Enregistrer Brouillon VS Transmettre Définitivement) -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="submit" name="action" value="brouillon" 
                        class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs border border-slate-300/80 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    <span>Enregistrer en Brouillon</span>
                </button>

                @if($stageCommence)
                <button type="submit" name="action" value="publier" 
                        class="w-full sm:w-auto px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Déposer &amp; Transmettre Définitivement</span>
                </button>
                @else
                <button type="submit" name="action" value="brouillon"
                        class="w-full sm:w-auto px-8 py-3.5 bg-slate-300 text-slate-600 rounded-2xl font-bold text-xs cursor-pointer flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Enregistrer (Déblocage publication le {{ $datedebut->format('d/m/Y') }})</span>
                </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Section 2 : Liste des documents déjà enregistrés / publiés -->
    @php
        $draftsCount = $etudiant->documents->where('statut', 'brouillon')->count();
        $publishedCount = $etudiant->documents->where('statut', 'publie')->count();
    @endphp

    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h4 class="text-sm font-extrabold text-[#0D1B4B]">Documents Enregistrés pour cet Étudiant ({{ $etudiant->documents->count() }})</h4>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $publishedCount }} publié(s) • {{ $draftsCount }} en brouillon
                </p>
            </div>

            @if($draftsCount > 0 && $stageCommence)
            <form action="{{ route('admin.rapports.depot.publier', $etudiant->id_etudiant) }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-md transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Publier tous les brouillons ({{ $draftsCount }})</span>
                </button>
            </form>
            @endif
        </div>

        @if($etudiant->documents->count() > 0)
        <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
            @foreach($etudiant->documents as $doc)
            <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 hover:bg-slate-50/80 transition">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center space-x-2 flex-wrap">
                            <p class="text-sm font-bold text-[#0D1B4B] truncate">{{ $doc->nom_document }}</p>
                            @if(($doc->statut ?? 'publie') === 'brouillon')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                    Brouillon
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Publié
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            Fichier : {{ $doc->fichier }} — {{ $doc->taille_fichier }} — {{ $doc->created_at ? $doc->created_at->format('d/m/Y à H:i') : '' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 flex-shrink-0">
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
            <p class="text-xs text-slate-400">Aucun document n'a encore été préparé pour cet étudiant.</p>
        </div>
        @endif
    </div>
</div>

<script>
let docRowCount = 1;

function addDocRow() {
    const container = document.getElementById('document-rows-container');
    const idx = docRowCount++;

    const row = document.createElement('div');
    row.className = 'doc-row p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-4 transition-all duration-200';
    row.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="doc-row-label text-xs font-black text-[#0D1B4B] uppercase tracking-wider">Document ${container.children.length + 1}</span>
            <button type="button" onclick="removeDocRow(this)" class="btn-remove-row text-slate-400 hover:text-[#E8001D] text-xs font-bold flex items-center space-x-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Supprimer</span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Nom / Titre du Document <span class="text-[#E8001D]">*</span>
                </label>
                <input type="text" name="documents[${idx}][nom_document]" required
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] transition"
                       placeholder="Ex: Fiche d'évaluation, Procès-verbal...">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Fichier Joint (PDF, Word, Excel, ZIP) <span class="text-[#E8001D]">*</span>
                </label>
                <input type="file" name="documents[${idx}][fichier]" required accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
            </div>
        </div>
    `;

    container.appendChild(row);
    updateRowLabels();
}

function removeDocRow(btn) {
    const container = document.getElementById('document-rows-container');
    if (container.children.length <= 1) {
        return;
    }
    const row = btn.closest('.doc-row');
    row.remove();
    updateRowLabels();
}

function updateRowLabels() {
    const container = document.getElementById('document-rows-container');
    const rows = container.querySelectorAll('.doc-row');
    rows.forEach((row, index) => {
        const label = row.querySelector('.doc-row-label');
        if (label) label.innerText = 'Document ' + (index + 1);
        
        const btnRemove = row.querySelector('.btn-remove-row');
        if (btnRemove) {
            if (rows.length > 1) {
                btnRemove.classList.remove('hidden');
            } else {
                btnRemove.classList.add('hidden');
            }
        }
    });
}
</script>
@endsection
