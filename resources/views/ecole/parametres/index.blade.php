@extends('layouts.dashboard')

@section('title', 'Profil Établissement - STAGILOG')
@section('header_title', 'Mon Profil École')

@section('dashboard_content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- En-tête profil école -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
        <!-- Logo de l'école -->
        <div class="relative flex-shrink-0">
            @if($ecole && $ecole->logo)
                <img src="{{ asset('uploads/logos/' . $ecole->logo) }}" alt="{{ $ecole->nom_ecole }}"
                     class="w-24 h-24 rounded-3xl object-contain border-2 border-[#1B3A8C]/10 shadow-md bg-white p-1">
            @else
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-[#1B3A8C] to-[#0D1B4B] flex items-center justify-center shadow-md">
                    <span class="text-3xl font-black text-white">{{ strtoupper(substr($ecole->sigle ?? $ecole->nom_ecole ?? 'E', 0, 2)) }}</span>
                </div>
            @endif
            <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-xl bg-emerald-500 border-2 border-white flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <div class="text-center sm:text-left">
            <h2 class="text-2xl font-black text-[#0D1B4B]">{{ $ecole->nom_ecole ?? 'Mon École' }}</h2>
            @if($ecole && $ecole->sigle)
                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-[#1B3A8C] rounded-full text-xs font-mono font-bold border border-blue-100 mt-1">{{ $ecole->sigle }}</span>
            @endif
            <p class="text-xs text-slate-500 mt-1">Compte officiel : <span class="font-bold text-slate-700">{{ $user->email }}</span></p>
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

    <form method="POST" action="{{ route('ecole.parametres.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Informations de l'établissement -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
            <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-6 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#1B3A8C]"></span>
                <span>Informations du Profil Établissement</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom responsable de la connexion -->
                <div class="sm:col-span-2">
                    <label for="nom_responsable" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Votre Nom (Responsable ou Secrétariat) <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_responsable" id="nom_responsable" 
                           value="{{ old('nom_responsable', $user->name) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Votre nom complet (ex: Moussa Diallo)">
                    <p class="text-[10px] text-slate-400 mt-1">Utilisé pour la salutation personnalisée sur le tableau de bord</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="nom_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom de l'Établissement <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_ecole" id="nom_ecole" 
                           value="{{ old('nom_ecole', $ecole->nom_ecole) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Nom complet de l'école">
                </div>

                <div>
                    <label for="sigle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Sigle <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="sigle" id="sigle" 
                           value="{{ old('sigle', $ecole->sigle) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition uppercase"
                           placeholder="ESP, UCAD, ISM...">
                </div>

                <div>
                    <label for="email_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Email Officiel <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="email" name="email" id="email_ecole" 
                           value="{{ old('email', $ecole->email ?? $ecole->mail) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="contact@ecole.sn">
                </div>

                <div>
                    <label for="telephone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Téléphone
                    </label>
                    <input type="text" name="telephone" id="telephone" 
                           value="{{ old('telephone', $ecole->telephone) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="+221 33 000 00 00">
                </div>

                <div>
                    <label for="adresse_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse / Campus
                    </label>
                    <input type="text" name="adresse_ecole" id="adresse_ecole" 
                           value="{{ old('adresse_ecole', $ecole->adresse_ecole) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Avenue Cheikh Anta Diop...">
                </div>

                <!-- Upload Logo -->
                <div class="sm:col-span-2">
                    <label for="logo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Logo de l'Établissement (PNG, JPG, SVG — Max. 4MB)
                    </label>
                    <input type="file" name="logo" id="logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                    @if($ecole && $ecole->logo)
                    <p class="text-[10px] text-slate-400 mt-1">Logo actuel : <strong>{{ $ecole->logo }}</strong></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Encart Sécurité & Mots de passe gérés par l'Admin -->
        <div class="bg-gradient-to-r from-blue-50/80 to-slate-50 border border-blue-100 rounded-3xl p-6 sm:p-8">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-[#0D1B4B] mb-1">Sécurité &amp; Gestion des Mots de Passe</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Conformément à la politique de sécurité de <strong>Technology Forever Group SARL</strong>, la gestion, l'attribution et la modification des mots de passe des comptes d'accès sont <strong>administrées exclusivement par l'équipe d'administration TFG SARL</strong>.<br>
                        En cas de perte ou pour toute demande de réinitialisation, veuillez contacter l'administrateur à <a href="mailto:stagilogtfg@gmail.com" class="font-bold text-[#1B3A8C] underline">stagilogtfg@gmail.com</a>.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                Enregistrer les informations de l'établissement
            </button>
        </div>
    </form>

    <!-- SECTION SÉCURITÉ : MODIFIER LE MOT DE PASSE -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-[#EEF4FF] text-[#1B3A8C] flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider">Sécurité &amp; Mot de Passe</h3>
                    <p class="text-[11px] text-slate-400">Modifier votre mot de passe d'accès avec votre mot de passe actuel</p>
                </div>
            </div>

            <!-- Bouton Mot de passe oublié -->
            <button type="button" onclick="openForgotPasswordModal()" 
                    class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#1B3A8C] hover:text-[#E8001D] bg-blue-50 hover:bg-red-50 px-3.5 py-2 rounded-xl border border-blue-100 hover:border-red-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mot de passe oublié ?</span>
            </button>
        </div>

        <form method="POST" action="{{ route('ecole.parametres.password.update') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Ancien mot de passe -->
                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Ancien Mot de Passe <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="••••••••">
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nouveau Mot de Passe <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="password" name="new_password" id="new_password" required minlength="6"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Min. 6 caractères">
                </div>

                <!-- Confirmation nouveau mot de passe -->
                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Confirmer le Mot de Passe <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required minlength="6"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Répéter le nouveau mot de passe">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                    Mettre à jour mon mot de passe
                </button>
            </div>
        </form>
    </div>

    <!-- MODAL MOT DE PASSE OUBLIÉ (Vérification par code OTP 6 chiffres par email) -->
    <div id="forgot-password-modal" class="fixed inset-0 z-50 bg-[#0D1B4B]/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full p-8 relative animate-in fade-in zoom-in-95 duration-200">
            <button type="button" onclick="closeForgotPasswordModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1B3A8C] flex items-center justify-center shadow-inner mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>

            <h3 class="text-lg font-extrabold text-[#0D1B4B]">Récupération de Mot de Passe</h3>
            <p class="text-xs text-slate-500 mt-1">Un code de sécurité à 6 chiffres sera envoyé à votre adresse email pour confirmer votre identité.</p>

            <div id="modal-alert-box" class="hidden my-4 p-3.5 rounded-2xl text-xs font-semibold"></div>

            <!-- Étape 1 : Demande de code OTP -->
            <div id="step-send-otp" class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse Email du Compte
                    </label>
                    <input type="email" id="reset-email" value="{{ $user->email }}" readonly
                           class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 outline-none cursor-not-allowed">
                </div>

                <button type="button" id="btn-send-otp" onclick="handleSendOtp()"
                        class="w-full py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-lg transition">
                    Envoyer le code de vérification
                </button>
            </div>

            <!-- Étape 2 : Saisie du code et nouveau mot de passe -->
            <div id="step-verify-otp" class="mt-6 space-y-4 hidden">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Code de sécurité à 6 chiffres <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" id="reset-otp-code" maxlength="6" placeholder="Ex: 849201"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-base font-mono font-bold tracking-widest text-center focus:ring-2 focus:ring-[#1B3A8C] outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Code reçu par email via stagilogtfg@gmail.com</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nouveau Mot de Passe <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="password" id="reset-new-password" placeholder="Min. 6 caractères" minlength="6"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-[#1B3A8C] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Confirmer le Mot de Passe <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="password" id="reset-new-password-confirm" placeholder="Confirmer..." minlength="6"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-[#1B3A8C] outline-none">
                </div>

                <div class="flex items-center space-x-3 pt-2">
                    <button type="button" onclick="handleSendOtp()" class="text-xs text-slate-500 hover:text-[#1B3A8C] font-semibold underline">
                        Renvoyer un code
                    </button>
                    <button type="button" id="btn-verify-otp" onclick="handleVerifyOtp()"
                            class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-xs shadow-lg transition">
                        Valider &amp; Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des connexions récentes (Colonnes IP & Navigateur séparées + Nom et Prénom) -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
        <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2 flex items-center space-x-2">
            <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Historique de Vos Connexions</span>
        </h3>
        <p class="text-[11px] text-slate-400 mb-6">Journal des sessions récentes de votre compte établissement.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Heure Connecté</th>
                        <th class="py-3 px-4">Heure Déconnecté</th>
                        <th class="py-3 px-4">Adresse IP</th>
                        <th class="py-3 px-4">Navigateur &amp; Appareil</th>
                        <th class="py-3 px-4">Nom et Prénom</th>
                        <th class="py-3 px-4 text-right">Statut Session</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($connexions as $conn)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-bold text-[#0D1B4B]">
                            {{ $conn->created_at ? $conn->created_at->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-[#1B3A8C]">
                            {{ $conn->created_at ? $conn->created_at->format('H:i:s') : '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500">
                            {{ $conn->deconnecte_at ? $conn->deconnecte_at->format('H:i:s') : ($conn->statut === 'succes' ? 'En session' : '-') }}
                        </td>
                        <!-- Colonne IP Séparée -->
                        <td class="py-3.5 px-4 font-mono text-slate-600 font-semibold">
                            {{ $conn->ip_address ?? '127.0.0.1' }}
                        </td>
                        <!-- Colonne Navigateur Séparée -->
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">{{ $conn->navigateur ?? 'Navigateur Web' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $conn->appareil ?? 'Ordinateur' }}</div>
                        </td>
                        <!-- Colonne Nom et Prénom -->
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-[#0D1B4B]">{{ $conn->nom ?? ($user->name ?? 'Responsable') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $conn->email ?? $user->email }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if($conn->statut === 'succes')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    Réussie
                                </span>
                            @elseif($conn->statut === 'deconnexion')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                    Déconnecté
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">
                                    Échec
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">Aucune activité enregistrée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.getElementById('sigle').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    function openForgotPasswordModal() {
        const modal = document.getElementById('forgot-password-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('modal-alert-box').className = 'hidden my-4 p-3.5 rounded-2xl text-xs font-semibold';
        document.getElementById('step-send-otp').classList.remove('hidden');
        document.getElementById('step-verify-otp').classList.add('hidden');
    }

    function closeForgotPasswordModal() {
        const modal = document.getElementById('forgot-password-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showModalAlert(type, message) {
        const box = document.getElementById('modal-alert-box');
        box.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-red-50', 'text-red-800', 'border-red-200');
        if (type === 'success') {
            box.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
        } else {
            box.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
        }
        box.innerHTML = message;
    }

    function handleSendOtp() {
        const btn = document.getElementById('btn-send-otp');
        const email = document.getElementById('reset-email').value;
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">&#8635;</span> Envoi du code en cours...';

        fetch("{{ route('ecole.parametres.password.send-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Envoyer le code de vérification';
            if (data.success) {
                showModalAlert('success', data.message);
                document.getElementById('step-send-otp').classList.add('hidden');
                document.getElementById('step-verify-otp').classList.remove('hidden');
            } else {
                showModalAlert('error', data.message || 'Une erreur est survenue.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = 'Envoyer le code de vérification';
            showModalAlert('error', 'Erreur de communication avec le serveur.');
        });
    }

    function handleVerifyOtp() {
        const btn = document.getElementById('btn-verify-otp');
        const code = document.getElementById('reset-otp-code').value.trim();
        const pwd = document.getElementById('reset-new-password').value;
        const confirm = document.getElementById('reset-new-password-confirm').value;

        if (!code || code.length !== 6) {
            showModalAlert('error', 'Veuillez saisir un code de vérification valide à 6 chiffres.');
            return;
        }
        if (!pwd || pwd.length < 6) {
            showModalAlert('error', 'Le nouveau mot de passe doit comporter au moins 6 caractères.');
            return;
        }
        if (pwd !== confirm) {
            showModalAlert('error', 'La confirmation du mot de passe ne correspond pas.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">&#8635;</span> Validation...';

        fetch("{{ route('ecole.parametres.password.verify-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
            },
            body: JSON.stringify({
                otp_code: code,
                new_password: pwd,
                new_password_confirmation: confirm
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Valider &amp; Réinitialiser';
            if (data.success) {
                showModalAlert('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showModalAlert('error', data.message || 'Le code ou les données sont invalides.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = 'Valider &amp; Réinitialiser';
            showModalAlert('error', 'Erreur de communication avec le serveur.');
        });
    }
</script>
@endsection
