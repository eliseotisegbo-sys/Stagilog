@extends('layouts.dashboard')

@section('title', 'Profil Administrateur & Sécurité - STAGILOG')
@section('header_title', 'Profil & Administrateurs')

@section('dashboard_content')
<div class="space-y-8">

    <!-- En-tête avec Profil Super Admin -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
            <div class="relative flex-shrink-0">
                @if($user->photo_profil)
                    <img src="{{ asset('uploads/avatars/' . $user->photo_profil) }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-3xl object-cover border-4 border-white shadow-xl bg-slate-100">
                @else
                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-[#1B3A8C] to-[#0D1B4B] text-white flex items-center justify-center font-black text-3xl shadow-xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-xl bg-emerald-500 border-2 border-white flex items-center justify-center shadow-md">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-center sm:text-left">
                @if($isSuperAdmin)
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 text-[#E8001D] mb-2">
                    Super Administrateur
                </div>
                @else
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-[#1B3A8C] mb-2">
                    Administrateur
                </div>
                @endif
                <h2 class="text-2xl font-black text-[#0D1B4B]">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }} — Technology Forever Group SARL</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="#section-admins" class="px-4 py-2.5 rounded-2xl bg-blue-50 text-[#1B3A8C] hover:bg-blue-100 text-xs font-bold transition">
                Gérer les Administrateurs ({{ $admins->count() }})
            </a>
            <a href="#section-historique" class="px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-bold transition">
                Historique des Connexions
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- SECTION 1 : MODIFIER MON PROFIL ADMIN (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <form method="POST" action="{{ route('admin.parametres.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Informations du profil + Photo -->
                <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
                    <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-6">
                        Modifier Mon Profil & Photo
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nom complet -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Nom complet <span class="text-[#E8001D]">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                                   placeholder="Votre nom complet">
                            @error('name') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Adresse Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Adresse Email <span class="text-[#E8001D]">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                                   placeholder="admin@tfg-sarl.com">
                            @error('email') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Photo de Profil (Upload) -->
                        <div class="sm:col-span-2">
                            <label for="photo_profil" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Photo de Profil (PNG, JPG, WEBP - Max. 4MB)
                            </label>
                            <input type="file" name="photo_profil" id="photo_profil" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                            <p class="text-[10px] text-slate-400 mt-1.5">Cette photo est affichée sur votre tableau de bord, dans l'en-tête et dans le menu.</p>
                        </div>
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
                    <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2">
                        Modifier Mon Mot de Passe
                    </h3>
                    <p class="text-[11px] text-slate-400 mb-6">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Mot de passe actuel -->
                        <div class="sm:col-span-2">
                            <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Mot de passe actuel
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                            @error('current_password') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div>
                            <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Nouveau mot de passe (min. 6 caractères)
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                            @error('new_password') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirmation nouveau mot de passe -->
                        <div>
                            <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Confirmer le nouveau mot de passe
                            </label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                        </div>
                    </div>
                </div>

                <!-- Bouton Enregistrer + Mode -->
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <!-- Bascule Mode Sombre / Clair -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-500">Apparence :</span>
                        <div class="flex items-center bg-slate-100 rounded-2xl p-1 gap-1">
                            <button type="button" id="theme-btn-light"
                                    onclick="setAppTheme('light')"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-13H20m-16 0H2.34M18.36 5.64l-.71.71M6.34 17.66l-.71.71M18.36 18.36l-.71-.71M6.34 6.34l-.71-.71M12 8a4 4 0 110 8 4 4 0 010-8z"/></svg>
                                Clair
                            </button>
                            <button type="button" id="theme-btn-dark"
                                    onclick="setAppTheme('dark')"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                Sombre
                            </button>
                        </div>
                    </div>

                    <button type="submit" 
                            class="inline-flex items-center space-x-2 bg-[#1B3A8C] hover:bg-[#142B6B] text-white px-8 py-4 rounded-2xl font-bold text-xs shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Enregistrer les Modifications de Mon Profil</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- SECTION 2 : GESTION DES COMPTES ADMINISTRATEURS (5 cols) -->
        <div class="lg:col-span-5 space-y-6" id="section-admins">
            
            @if($isSuperAdmin)
            <!-- Formulaire Ajouter un Administrateur (Uniquement Super Admin) -->
            <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8">
                <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2 flex items-center space-x-2">
                    <span>Créer un Compte Administrateur</span>
                </h3>
                <p class="text-xs text-slate-400 mb-6">Ajouter un autre collaborateur avec les privilèges d'administration TFG SARL.</p>

                <form method="POST" action="{{ route('admin.parametres.admin-user.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="admin_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nom & Prénom <span class="text-[#E8001D]">*</span></label>
                        <input type="text" name="name" id="admin_name" required placeholder="Ex: M. Paul Ndiaye"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                    </div>

                    <div>
                        <label for="admin_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email <span class="text-[#E8001D]">*</span></label>
                        <input type="email" name="email" id="admin_email" required placeholder="collaborateur@tfg-sarl.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                    </div>

                    <div>
                        <label for="admin_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Mot de Passe <span class="text-[#E8001D]">*</span></label>
                        <input type="password" name="password" id="admin_password" required minlength="6" placeholder="Min. 6 caractères"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                    </div>

                    <div>
                        <label for="admin_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirmation Mot de Passe <span class="text-[#E8001D]">*</span></label>
                        <input type="password" name="password_confirmation" id="admin_password_confirmation" required minlength="6" placeholder="Confirmer mot de passe"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                    </div>

                    <div>
                        <label for="admin_photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Photo de Profil (Optionnelle)</label>
                        <input type="file" name="photo_profil" id="admin_photo" accept="image/*"
                               class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#1B3A8C] file:text-white">
                    </div>

                    <button type="submit" 
                            class="w-full py-3 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-xl font-bold text-xs shadow-md transition">
                        Créer le Compte Administrateur
                    </button>
                </form>
            </div>
            @else
            @endif

            <!-- Liste des Administrateurs existants -->
            <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8">
                <h4 class="text-xs font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-4 flex items-center justify-between">
                    <span>Administrateurs TFG SARL</span>
                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-[#1B3A8C] font-bold text-[10px]">{{ $admins->count() }} admins</span>
                </h4>

                <div class="space-y-3">
                    @foreach($admins as $adm)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="flex items-center space-x-3 min-w-0">
                            @if($adm->photo_profil)
                                <img src="{{ asset('uploads/avatars/' . $adm->photo_profil) }}" alt="{{ $adm->name }}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-xl bg-[#1B3A8C] text-white flex items-center justify-center font-black text-xs flex-shrink-0">
                                    {{ strtoupper(substr($adm->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="truncate">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-[#0D1B4B] truncate">{{ $adm->name }}</span>
                                    @if($firstAdmin && $adm->id === $firstAdmin->id)
                                    <span class="text-[9px] font-black uppercase text-[#E8001D] bg-red-50 px-1.5 py-0.2 rounded">Super Admin</span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $adm->email }}</span>
                            </div>
                        </div>

                        @if($adm->id !== $user->id)
                            @if($isSuperAdmin)
                            <form action="{{ route('admin.parametres.admin-user.destroy', $adm->id) }}" method="POST" class="inline flex-shrink-0 ml-2" onsubmit="return confirm('Supprimer cet administrateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-[#E8001D] hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        @else
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 flex-shrink-0">Vous</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- SECTION 3 : HISTORIQUE COMPLET DES CONNEXIONS (Tableau 1:1) -->
    <!-- ======================================================= -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden" id="section-historique">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Historique de Connexion</h3>
                <p class="text-xs font-medium text-slate-400">Journal d'audit de sécurité des sessions et déconnexions.</p>
            </div>
            <span class="text-xs font-bold text-[#1B3A8C] bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                Audit de Connexion STAGILOG
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6">Heure Connecté</th>
                        <th class="py-4 px-6">Heure Déconnecté</th>
                        <th class="py-4 px-6">Adresse IP</th>
                        <th class="py-4 px-6">Navigateur &amp; Appareil</th>
                        <th class="py-4 px-6">Nom et Prénom</th>
                        <th class="py-4 px-6 text-right">Statut Session</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($connexions as $c)
                    <tr class="hover:bg-slate-50/70 transition">
                        <!-- Date -->
                        <td class="py-4 px-6 font-bold text-[#0D1B4B]">
                            {{ $c->created_at ? $c->created_at->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                        </td>
                        
                        <!-- Heure connecté -->
                        <td class="py-4 px-6">
                            <span class="font-mono font-bold text-[#1B3A8C] bg-blue-50 px-2 py-1 rounded-md">
                                {{ $c->created_at ? $c->created_at->format('H:i:s') : '-' }}
                            </span>
                        </td>

                        <!-- Heure déconnecté -->
                        <td class="py-4 px-6">
                            @if($c->deconnecte_at)
                                <span class="font-mono text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                                    {{ $c->deconnecte_at->format('H:i:s') }}
                                </span>
                            @elseif($c->statut === 'succes')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                    En session
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        <!-- Adresse IP Séparée -->
                        <td class="py-4 px-6 font-mono font-semibold text-slate-700">
                            {{ $c->ip_address ?? '127.0.0.1' }}
                        </td>

                        <!-- Navigateur & Appareil Séparé -->
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B]">{{ $c->navigateur ?? 'Navigateur Web' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $c->appareil ?? 'Ordinateur' }}</div>
                        </td>

                        <!-- Nom et Prénom -->
                        <td class="py-4 px-6">
                            <div class="font-bold text-[#0D1B4B]">{{ $c->nom ?? ($c->user->name ?? $c->email) }}</div>
                            <div class="text-[10px] text-slate-400">{{ $c->email }} @if($c->role) ({{ strtoupper($c->role) }}) @endif</div>
                        </td>

                        <!-- Statut Session -->
                        <td class="py-4 px-6 text-right">
                            @if($c->statut === 'succes')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Succès
                                </span>
                            @elseif($c->statut === 'deconnexion')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-600">
                                    Déconnecté
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-red-50 text-[#E8001D] border border-red-200">
                                    Échec
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            Aucun enregistrement d'historique de connexion pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($connexions->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $connexions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// ─── Dark Mode Toggle ──────────────────────────────────────────────────────
function setAppTheme(theme) {
    try { localStorage.setItem('stagilog_theme', theme); } catch(e) {}
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    updateThemeBtns(theme);
}

function updateThemeBtns(theme) {
    const light = document.getElementById('theme-btn-light');
    const dark  = document.getElementById('theme-btn-dark');
    if (!light || !dark) return;
    if (theme === 'dark') {
        dark.classList.add('bg-[#1B3A8C]', 'text-white', 'shadow-md');
        dark.classList.remove('text-slate-500');
        light.classList.remove('bg-white', 'text-[#1B3A8C]', 'shadow-sm');
        light.classList.add('text-slate-500');
    } else {
        light.classList.add('bg-white', 'text-[#1B3A8C]', 'shadow-sm');
        light.classList.remove('text-slate-500');
        dark.classList.remove('bg-[#1B3A8C]', 'text-white', 'shadow-md');
        dark.classList.add('text-slate-500');
    }
}
// Init
(function() {
    try { updateThemeBtns(localStorage.getItem('stagilog_theme') || 'light'); } catch(e) {}
})();
</script>
@endpush
@endsection
