@extends('layouts.dashboard')

@section('title', 'Dossiers de Stage - STAGILOG')
@section('header_title', 'Dossiers de Stage')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- BANDEAU DE GESTION DE LA CAMPAGNE DE DÉPÔT DES DOSSIERS -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-card flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-gradient-to-br from-blue-50 to-transparent rounded-full pointer-events-none"></div>

        <div class="flex items-start sm:items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $depotActif ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                @if($depotActif)
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                @endif
            </div>

            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider">Période de Dépôt des Dossiers</h3>
                    @if($depotActif)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Dépôts Ouverts
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-800 border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Dépôts Fermés
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-500 mt-1">
                    @if($depotDebut && $depotFin)
                        Période officielle fixée : <strong class="text-[#1B3A8C]">du {{ \Carbon\Carbon::parse($depotDebut)->locale('fr')->isoFormat('D MMMM YYYY') }} au {{ \Carbon\Carbon::parse($depotFin)->locale('fr')->isoFormat('D MMMM YYYY') }}</strong>
                    @else
                        Aucune restriction de dates active. Les écoles peuvent déposer leurs dossiers en continu.
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap z-10">
            <!-- Bouton Toggle Activation / Désactivation -->
            <form action="{{ route('admin.dossiers.toggle-depots') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center space-x-2 {{ $depotActif ? 'bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md' }}">
                    @if($depotActif)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Désactiver les dépôts</span>
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Activer les dépôts</span>
                    @endif
                </button>
            </form>

            <!-- Bouton Définir la Période & Notifier -->
            <button type="button" onclick="openModalPeriodeDepot()" 
                    class="px-4 py-2.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Définir une Période </span>
            </button>
        </div>
    </div>

    <!-- Filtres rapides par statut -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">
        <a href="{{ route('admin.dossiers.index') }}" 
           class="p-4 rounded-2xl bg-white border {{ !$status ? 'border-[#1B3A8C] ring-2 ring-[#1B3A8C]/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">Tous les dossiers</p>
                <h4 class="text-xl font-black text-[#0D1B4B]">{{ $countTotal }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                &Sigma;
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'en_attente']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'en_attente' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-amber-600">En attente</p>
                <h4 class="text-xl font-black text-amber-600">{{ $countAttente }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'sous_reserve']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'sous_reserve' ? 'border-blue-600 ring-2 ring-blue-600/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#1B3A8C]">Sous réserve</p>
                <h4 class="text-xl font-black text-[#1B3A8C]">{{ $countSousReserve }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-[#1B3A8C]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'valide']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'valide' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-emerald-600">Validés</p>
                <h4 class="text-xl font-black text-emerald-600">{{ $countValide }}</h4>
            </div>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </a>

        <a href="{{ route('admin.dossiers.index', ['statut' => 'refuse']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'refuse' ? 'border-red-500 ring-2 ring-red-500/20 shadow-md' : 'border-slate-100 shadow-card hover:bg-slate-50' }} transition flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-red-600">Refusés</p>
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
                            @if($dossier->statut === 'valide')
                                <span class="inline-flex items-center px-3 py-1 rounded-full font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200" style="font-size: 0.6875rem;">
                                    VALIDÉ
                                </span>
                            @elseif($dossier->statut === 'refuse')
                                <span class="inline-flex items-center px-3 py-1 rounded-full font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200" style="font-size: 0.6875rem;">
                                    REFUSÉ
                                </span>
                            @elseif($dossier->statut === 'sous_reserve')
                                <span class="inline-flex items-center px-3 py-1 rounded-full font-black uppercase tracking-wider bg-blue-100 text-[#1B3A8C] border border-blue-200" style="font-size: 0.6875rem;">
                                    SOUS RÉSERVE
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200" style="font-size: 0.6875rem;">
                                    EN ATTENTE
                                </span>
                            @endif
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
    <div class="p-4 border-t border-slate-100 bg-slate-50 rounded-2xl">
        {{ $dossiers->links() }}
    </div>
    @endif

    <!-- MODAL DÉFINIR PÉRIODE DE DÉPÔT ET NOTIFIER LES ÉCOLES -->
    <div id="modal-periode-depot" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full border border-slate-100 overflow-hidden transform transition-all">
            <!-- Entête Modal -->
            <div class="px-6 py-5 bg-gradient-to-r from-[#0D1B4B] to-[#1B3A8C] text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wider">Campagne de Dépôt</h3>
                        <p class="text-[11px] text-blue-200">Fixer les dates et notifier les établissements</p>
                    </div>
                </div>
                <button type="button" onclick="closeModalPeriodeDepot()" class="p-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Corps du formulaire -->
            <form action="{{ route('admin.dossiers.configurer-depots') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_date_debut" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Date de Début <span class="text-[#E8001D]">*</span>
                        </label>
                        <input type="date" name="date_debut" id="modal_date_debut" required
                               value="{{ old('date_debut', $depotDebut ?? now()->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white">
                    </div>

                    <div>
                        <label for="modal_date_fin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Date Limite (Fin) <span class="text-[#E8001D]">*</span>
                        </label>
                        <input type="date" name="date_fin" id="modal_date_fin" required
                               value="{{ old('date_fin', $depotFin ?? now()->addMonths(1)->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white">
                    </div>
                </div>

                <div>
                    <label for="modal_instructions" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Consignes ou Note particulière (Optionnel)
                    </label>
                    <textarea name="instructions" id="modal_instructions" rows="3"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white resize-none"
                              placeholder="Ex: Priorité accordée aux filières Réseaux & Télécoms et Génie Logiciel...">{{ old('instructions', $depotInstructions) }}</textarea>
                </div>

                <!-- Checkbox Notification Email -->
                <div class="p-3.5 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-start space-x-3">
                    <input type="checkbox" name="notifier_ecoles" id="notifier_ecoles" value="1" checked
                           class="mt-0.5 w-4 h-4 text-[#1B3A8C] rounded border-slate-300 focus:ring-[#1B3A8C]">
                    <label for="notifier_ecoles" class="text-xs text-slate-700 cursor-pointer">
                        <strong class="text-[#0D1B4B] block mb-0.5">Envoyer un email officiel à tous les établissements</strong>
                        Un courrier électronique professionnel sera transmis à l'ensemble des écoles partenaires avec les dates de dépôt et les instructions de TFG SARL.
                    </label>
                </div>

                <!-- Boutons d'action -->
                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModalPeriodeDepot()" 
                            class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-[#1B3A8C] hover:bg-[#142B6B] text-white text-xs font-bold shadow-md transition flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Enregistrer & Diffuser</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModalPeriodeDepot() {
    const modal = document.getElementById('modal-periode-depot');
    modal.classList.remove('hidden');
}

function closeModalPeriodeDepot() {
    const modal = document.getElementById('modal-periode-depot');
    modal.classList.add('hidden');
}

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
