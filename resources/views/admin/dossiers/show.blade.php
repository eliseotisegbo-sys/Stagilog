@extends('layouts.dashboard')

@php
    $codeDossier = $dossier->code_dossier ?? (($dossier->ecole->sigle ?? 'STAGE') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : date('dmYHi')));
@endphp

@section('title', 'Examen du Dossier ' . $codeDossier . ' - STAGILOG')
@section('header_title', 'Dossier ' . $codeDossier . ' - ' . ($dossier->ecole->nom_ecole ?? 'École'))

@section('dashboard_content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.dossiers.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-[#1B3A8C] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour à la liste des dossiers</span>
        </a>

        <!-- Action Decision Buttons -->
        <div class="flex items-center space-x-3">
            @if($dossier->statut !== 'valide')
                <form action="{{ route('admin.dossiers.valider', $dossier->id_dossier) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-2xl text-xs font-bold shadow-lg hover:shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Valider le Dossier</span>
                    </button>
                </form>

                @if($dossier->statut !== 'refuse')
                <button type="button" onclick="document.getElementById('modal-refus').classList.remove('hidden')" 
                        class="inline-flex items-center space-x-2 bg-red-50 hover:bg-red-100 text-[#E8001D] border border-red-200 px-5 py-2.5 rounded-2xl text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Refuser le Dossier</span>
                </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Alert si Validé : Démarrage direct possible -->
    @if($dossier->statut === 'valide')
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-3xl text-emerald-900 flex items-center space-x-4 shadow-sm">
        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h4 class="text-sm font-extrabold text-emerald-900">Dossier Validé par la Direction</h4>
            <p class="text-xs text-emerald-700 mt-0.5">
                Les stagiaires de cette promotion peuvent démarrer leur stage directement à partir du 
                <strong>{{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}</strong> 
                au 
                <strong>{{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}</strong>.
            </p>
        </div>
    </div>
    @endif

    <!-- Alert Motif Refus si refusé -->
    @if($dossier->statut === 'refuse' && $dossier->motif_refus)
    <div class="p-6 bg-red-50 border border-red-200 rounded-3xl text-red-900 flex items-start space-x-4">
        <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-red-800">Dossier Refusé par l'Administration</h4>
            <p class="text-xs text-red-700 mt-1"><strong>Motif :</strong> {{ $dossier->motif_refus }}</p>
        </div>
    </div>
    @endif

    <!-- 2 Colonnes : Métadonnées du Dossier + Documents -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Colonne Gauche : Infos Générales (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-card border border-slate-100 p-8 space-y-6">
            <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Établissement Demandeur</span>
                    <h3 class="text-xl font-black text-[#0D1B4B] mt-0.5">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</h3>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}">
                    Statut : {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Filière & Spécialité</p>
                    <p class="text-sm font-bold text-[#0D1B4B] mt-1">{{ $dossier->filiere }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Cycle Académique</p>
                    <p class="text-sm font-bold text-[#1B3A8C] mt-1">{{ $dossier->cycle->nom_cycle ?? 'Standard' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Période de Stage Définie</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 lowercase">
                                {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }} 
                                <span class="text-slate-400 mx-1">au</span>
                                {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                            </p>
                        </div>
                        <button type="button" onclick="openModifierPeriodeModal()" 
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-[#1B3A8C]/10 hover:bg-[#1B3A8C] text-[#1B3A8C] hover:text-white rounded-xl text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Modifier les dates</span>
                        </button>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-2">
                    <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Type de Stage & Niveau</p>
                    <p class="text-sm font-bold text-slate-800 mt-1">
                        {{ $dossier->type_stage ?? 'Stage en entreprise' }} ({{ $dossier->niveau_etude ?? 'BAC+' }})
                    </p>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Note de Demande Officielle (1 col) -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Note de Demande</h4>
                
                @if($dossier->note_demande)
                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-[#1B3A8C] mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-[#0D1B4B] truncate">{{ $dossier->note_demande }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Lettre officielle de l'école</p>
                    
                    <a href="{{ asset('uploads/notes/' . $dossier->note_demande) }}" target="_blank" 
                       class="mt-4 inline-flex items-center space-x-1 text-xs font-bold text-[#1B3A8C] hover:underline">
                        <span>Ouvrir le document</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
                @else
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center text-slate-400 text-xs">
                    Aucun fichier de demande joint.
                </div>
                @endif
            </div>

            <div class="pt-6 border-t border-slate-100 text-xs text-slate-500">
                <p>Identifiant dossier : <strong class="font-mono text-[#1B3A8C]">{{ $codeDossier }}</strong></p>
                <p class="mt-1">Créé le : {{ $dossier->created_at ? $dossier->created_at->locale('fr')->isoFormat('ddd. D MMMM YYYY [à] HH:mm') : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- LISTE DES ÉTUDIANTS CANDIDATS -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Étudiants Rattachés ({{ $dossier->etudiants->count() }})</h3>
                <p class="text-xs font-medium text-slate-400">Liste des stagiaires proposés par l'université</p>
            </div>
            @if($dossier->statut === 'valide')
            <a href="{{ route('admin.rapports.index') }}" class="text-xs font-bold text-[#1B3A8C] hover:underline">
                Déposer un Rapport &rarr;
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Nom & Prénom</th>
                        <th class="py-4 px-6">Email Étudiant</th>
                        <th class="py-4 px-6">Niveau & Date Naissance</th>
                        <th class="py-4 px-6">Curriculum Vitae</th>
                        <th class="py-4 px-6 text-right">Rapport & Documents</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($dossier->etudiants as $etudiant)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B] text-sm">{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $etudiant->email_etu }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-semibold">{{ $etudiant->niveau_etude ?? $dossier->niveau_etude }}</div>
                            <div class="text-[11px] text-slate-400">{{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : '-' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($etudiant->cv)
                            <a href="{{ asset('uploads/cv/' . $etudiant->cv) }}" target="_blank" 
                                class="inline-flex items-center space-x-1.5 px-3 py-1 bg-blue-50 text-[#1B3A8C] hover:bg-blue-100 rounded-xl font-bold transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Consulter CV</span>
                            </a>
                            @else
                            <span class="text-slate-400">Non fourni</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if($dossier->statut === 'valide')
                                <a href="{{ route('admin.rapports.depot', $etudiant->id_etudiant) }}" 
                                   class="inline-flex items-center space-x-1 text-xs font-bold text-[#1B3A8C] hover:text-[#E8001D] transition">
                                    <span>Gérer documents ({{ $etudiant->documents->count() }})</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 text-[11px]">En attente de validation</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Aucun étudiant renseigné dans ce dossier.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL REFUS DU DOSSIER -->
<div id="modal-refus" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-slate-100">
        <div class="flex items-center space-x-3 text-red-600 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-black text-[#0D1B4B]">Refuser le Dossier {{ $codeDossier }}</h3>
        </div>

        <p class="text-xs text-slate-500 mb-4">
            Veuillez renseigner le motif de refus. Cette justification sera transmise à l'école partenaire.
        </p>

        <form method="POST" action="{{ route('admin.dossiers.refuser', $dossier->id_dossier) }}">
            @csrf
            <div class="mb-6">
                <label for="motif_refus" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Motif du Refus <span class="text-[#E8001D]">*</span>
                </label>
                <textarea name="motif_refus" id="motif_refus" rows="4" 
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition"
                          placeholder="Ex: Capacité d'accueil atteinte pour cette période, documents manquants..." required></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modal-refus').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 bg-[#E8001D] hover:bg-red-700 text-white rounded-2xl font-bold text-xs shadow-lg transition">
                    Confirmer le Refus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL MODIFICATION DE LA PÉRIODE DE STAGE -->
<div id="modal-modifier-periode" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 relative">
        <div class="flex items-center space-x-3 text-[#1B3A8C] mb-4">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-[#0D1B4B]">Ajuster la Période</h3>
                <p class="text-[11px] text-slate-400 font-semibold">{{ $codeDossier }}</p>
            </div>
        </div>

        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            En tant qu'administrateur, vous pouvez adapter les dates officielles de début et de fin de stage avant validation.
        </p>

        <form method="POST" action="{{ route('admin.dossiers.modifier-periode', $dossier->id_dossier) }}" class="space-y-4">
            @csrf
            <div>
                <label for="modal_datedebut" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Nouvelle Date de Début <span class="text-[#E8001D]">*</span>
                </label>
                <input type="text" name="datedebut" id="modal_datedebut" required
                       value="{{ $dossier->datedebut ? $dossier->datedebut->format('Y-m-d') : '' }}"
                       class="datepicker-input w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-[#0D1B4B] focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
            </div>

            <div>
                <label for="modal_datefin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Nouvelle Date de Fin <span class="text-[#E8001D]">*</span>
                </label>
                <input type="text" name="datefin" id="modal_datefin" required
                       value="{{ $dossier->datefin ? $dossier->datefin->format('Y-m-d') : '' }}"
                       class="datepicker-input w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-[#0D1B4B] focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModifierPeriodeModal()"
                        class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-lg transition">
                    Enregistrer la période
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModifierPeriodeModal() {
    document.getElementById('modal-modifier-periode').classList.remove('hidden');
    if (window.initCustomDatepickers) {
        window.initCustomDatepickers();
    }
}
function closeModifierPeriodeModal() {
    document.getElementById('modal-modifier-periode').classList.add('hidden');
}
</script>
@endpush
@endsection
