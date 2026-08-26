@extends('layouts.dashboard')

@section('title', 'Paramètres - STAGILOG')
@section('header_title', 'Paramètres')

@section('dashboard_content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- En-tête profil école -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 flex items-center space-x-6">
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
            <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-xl bg-emerald-500 border-2 border-white flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <div>
            <h2 class="text-2xl font-black text-[#0D1B4B]">{{ $ecole->nom_ecole ?? 'Mon École' }}</h2>
            @if($ecole->sigle)
                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-[#1B3A8C] rounded-full text-xs font-mono font-bold border border-blue-100 mt-1">{{ $ecole->sigle }}</span>
            @endif
            <p class="text-xs text-slate-500 mt-1">{{ $user->email }}</p>
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
                <span>Informations de l'Établissement</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom responsable de la connexion -->
                <div class="sm:col-span-2">
                    <label for="nom_responsable" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Votre Nom (Responsable connecté) <span class="text-[#E8001D]">*</span>
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
                    @if($ecole->logo)
                    <p class="text-[10px] text-slate-400 mt-1">Logo actuel : <strong>{{ $ecole->logo }}</strong></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Changement de mot de passe -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
            <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#E8001D]"></span>
                <span>Changer le Mot de Passe</span>
            </h3>
            <p class="text-[11px] text-slate-400 mb-6">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Mot de passe actuel
                    </label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Votre mot de passe actuel">
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" name="new_password" id="new_password"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Minimum 6 caractères">
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Confirmer le nouveau mot de passe">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('sigle').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@endsection
