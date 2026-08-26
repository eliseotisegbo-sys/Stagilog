@extends('layouts.dashboard')

@section('title', 'Écoles Partenaires - STAGILOG')
@section('header_title', 'Écoles Partenaires')

@section('dashboard_content')
<div class="space-y-6">
    
    <!-- En-tête avec bouton Ajouter -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" id="live-search-ecoles"
                   placeholder="Rechercher une école par nom, email, contact..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] shadow-sm">
        </div>

        <a href="{{ route('admin.ecoles.create') }}" 
           class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-5 py-3 rounded-2xl font-bold text-xs shadow-lg hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Ajouter une école</span>
        </a>
    </div>

    <!-- Alert identifiants générés après création de compte -->
    @if(session('compte_cree'))
    <div class="p-6 bg-emerald-50 border-2 border-emerald-500 rounded-3xl shadow-lg relative">
        <div class="flex items-start justify-between">
            <div>
                <h4 class="text-sm font-black text-emerald-900 uppercase tracking-wider mb-2 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Compte d'accès créé pour {{ session('compte_cree')['ecole'] }}</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium text-emerald-800 bg-white/70 p-4 rounded-2xl border border-emerald-200">
                    <div>
                        <span class="text-slate-500 block text-[11px]">Identifiant (Email) :</span>
                        <strong class="font-mono text-sm text-[#0D1B4B]">{{ session('compte_cree')['email'] }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px]">Mot de passe généré :</span>
                        <strong class="font-mono text-sm text-[#E8001D]">{{ session('compte_cree')['password'] }}</strong>
                    </div>
                </div>
                <p class="text-[11px] text-emerald-700 mt-2">
                    @if(session('compte_cree')['email_envoye'] ?? false)
                        Les identifiants ont été envoyés par email à l'école. Vous pouvez également les copier ci-dessus.
                    @else
                        Compte activé. Vous pouvez transmettre ces identifiants directement à l'établissement.
                    @endif
                </p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Table des Écoles -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="ecoles-table">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Établissement</th>
                        <th class="py-4 px-6">Contact & Email</th>
                        <th class="py-4 px-6">Téléphone</th>
                        <th class="py-4 px-6 text-center">Compte d'Accès</th>
                        <th class="py-4 px-6 text-center">Dossiers</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($ecoles as $ecole)
                    @php
                        $userAccount = $ecole->users->first();
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition search-row">
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1B3A8C] font-black flex items-center justify-center shadow-inner">
                                    {{ strtoupper(substr($ecole->nom_ecole, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-[#0D1B4B] text-sm search-target">{{ $ecole->nom_ecole }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ecole->adresse_ecole ?? 'Adresse non renseignée' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[#0D1B4B] font-bold search-target">{{ $ecole->email ?? $ecole->mail }}</div>
                            <div class="text-[11px] text-slate-400">Email Officiel</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 search-target">
                            {{ $ecole->telephone ?? $ecole->num_ecole ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($userAccount)
                                <div class="inline-flex flex-col items-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Compte Actif
                                    </span>
                                    <button type="button" 
                                            onclick="openPasswordModal({{ $ecole->id_ecole }}, '{{ addslashes($ecole->nom_ecole) }}', '{{ $userAccount->email }}')"
                                            class="text-[11px] text-[#1B3A8C] font-bold hover:underline mt-1">
                                        Modifier le mot de passe
                                    </button>
                                </div>
                            @else
                                <button type="button" 
                                        onclick="openCreateAccountModal({{ $ecole->id_ecole }}, '{{ addslashes($ecole->nom_ecole) }}', '{{ $ecole->email ?? $ecole->mail }}')"
                                        class="inline-flex items-center space-x-1.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-3.5 py-1.5 rounded-xl font-bold text-xs shadow transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Créer un compte</span>
                                </button>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-[#1B3A8C] font-bold">
                                {{ $ecole->dossiers_count }} dossier(s)
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('admin.ecoles.edit', $ecole->id_ecole) }}" 
                               class="inline-flex items-center p-2 text-slate-500 hover:text-[#1B3A8C] hover:bg-blue-50 rounded-xl transition" title="Modifier les coordonnées">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.ecoles.destroy', $ecole->id_ecole) }}" method="POST" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de cette école ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-500 hover:text-[#E8001D] hover:bg-red-50 rounded-xl transition" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Aucun établissement trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ecoles->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $ecoles->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal 1 : Création & Validation du Compte École avec Prévisualisation et Choix d'Envoi -->
<div id="create-account-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 relative">
        <button onclick="closeCreateAccountModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-xl font-black text-[#0D1B4B] mb-1">Création du Compte d'Accès</h3>
        <p id="modal-create-ecole-name" class="text-xs font-semibold text-[#1B3A8C] mb-6"></p>

        <form id="create-account-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Identifiant de connexion (Email)</label>
                <input type="text" id="modal-create-email" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700">
            </div>

            <div>
                <label for="modal-create-pass" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Mot de Passe Initial <span class="text-[#E8001D]">*</span></label>
                <input type="text" name="password" id="modal-create-pass" required minlength="6"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white font-mono font-bold text-[#0D1B4B]">
            </div>

            <div class="flex items-center justify-between">
                <button type="button" onclick="generateRandomPassForCreate()" class="text-xs font-bold text-[#1B3A8C] hover:underline">
                    Générer un autre mot de passe
                </button>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="envoyer_email" value="1" checked 
                           class="w-4 h-4 text-[#1B3A8C] rounded border-slate-300 focus:ring-[#1B3A8C]">
                    <span>Envoyer immédiatement les identifiants par email</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateAccountModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#1B3A8C] text-white text-xs font-bold hover:bg-[#142B6B] shadow-lg transition">
                    Valider & Activer le Compte
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2 : Modification du Mot de Passe par l'Admin -->
<div id="password-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 relative">
        <button onclick="closePasswordModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-xl font-black text-[#0D1B4B] mb-1">Gestion du Mot de Passe</h3>
        <p id="modal-ecole-name" class="text-xs font-semibold text-[#1B3A8C] mb-6"></p>

        <form id="password-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Identifiant de connexion</label>
                <input type="text" id="modal-email" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700">
            </div>

            <div>
                <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nouveau Mot de Passe <span class="text-[#E8001D]">*</span></label>
                <input type="text" name="password" id="new_password" required minlength="6"
                       placeholder="Saisissez ou générez un mot de passe"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white font-mono">
            </div>

            <div class="flex items-center justify-between pt-2">
                <button type="button" onclick="generateRandomPass()" class="text-xs font-bold text-[#1B3A8C] hover:underline">
                    Générer aléatoirement
                </button>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <button type="button" onclick="closePasswordModal()" class="px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#1B3A8C] text-white text-xs font-bold hover:bg-[#142B6B] shadow-lg transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Live Search Instantané dès la 1ère lettre
document.getElementById('live-search-ecoles').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#ecoles-table tbody tr.search-row');
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Modal Création Compte
function openCreateAccountModal(ecoleId, ecoleName, email) {
    document.getElementById('modal-create-ecole-name').innerText = ecoleName;
    document.getElementById('modal-create-email').value = email;
    document.getElementById('create-account-form').action = '/admin/ecoles/' + ecoleId + '/creer-compte';
    generateRandomPassForCreate();
    document.getElementById('create-account-modal').classList.remove('hidden');
}

function closeCreateAccountModal() {
    document.getElementById('create-account-modal').classList.add('hidden');
}

function generateRandomPassForCreate() {
    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
    let pass = "Tfg@";
    for(let i = 0; i < 5; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    pass += Math.floor(100 + Math.random() * 900);
    document.getElementById('modal-create-pass').value = pass;
}

// Modal Modification Mot de Passe
function openPasswordModal(ecoleId, ecoleName, email) {
    document.getElementById('modal-ecole-name').innerText = ecoleName;
    document.getElementById('modal-email').value = email;
    document.getElementById('password-form').action = '/admin/ecoles/' + ecoleId + '/update-password';
    document.getElementById('new_password').value = '';
    document.getElementById('password-modal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('password-modal').classList.add('hidden');
}

function generateRandomPass() {
    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
    let pass = "Tfg@";
    for(let i = 0; i < 6; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('new_password').value = pass;
}
</script>
@endpush
@endsection
