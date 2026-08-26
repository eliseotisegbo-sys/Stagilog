@extends('layouts.dashboard')

@section('title', 'Paramètres Administrateur - STAGILOG')
@section('header_title', 'Paramètres')

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- En-tête -->
    <div class="flex items-center space-x-4">
        <div class="w-14 h-14 rounded-2xl bg-[#1B3A8C] flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-[#0D1B4B]">Paramètres du Compte</h2>
            <p class="text-xs text-slate-500 mt-0.5">Gérez vos informations personnelles et votre mot de passe</p>
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

    <form method="POST" action="{{ route('admin.parametres.update') }}" class="space-y-6">
        @csrf

        <!-- Informations du profil -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
            <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-6 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#1B3A8C]"></span>
                <span>Informations du Compte</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom complet <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Votre nom complet">
                    @error('name') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse Email <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="admin@tfg-sarl.com">
                    @error('email') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
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
@endsection
