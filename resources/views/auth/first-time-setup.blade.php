@extends('layouts.app')

@section('title', 'Configuration Initiale du Compte - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0D1B4B] via-[#1B3A8C] to-[#0D1B4B] p-4 relative">
    
    <div class="relative z-10 w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl mx-auto flex items-center justify-center p-3 shadow-inner mb-4 border border-amber-200">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            
            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-[11px] font-bold uppercase tracking-wider mb-2">
                Première Connexion Obligatoire
            </span>
            
            <h1 class="text-2xl font-black text-[#0D1B4B] tracking-tight">Définir un Mot de Passe Sécurisé</h1>
            <p class="text-xs text-slate-500 mt-2 max-w-sm mx-auto">
                Pour des raisons de sécurité, vous devez obligatoirement renouveler votre mot de passe administrateur avant de continuer.
            </p>
        </div>

        <form method="POST" action="{{ route('first-time-setup.submit') }}" class="space-y-5">
            @csrf
            
            <!-- Ancien Mot de passe -->
            <div>
                <label for="old_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Mot de passe initial (actuel)
                </label>
                <input type="password" name="old_password" id="old_password"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       placeholder="Admin@2026" required autofocus>
            </div>

            <!-- Nouveau Mot de passe -->
            <div>
                <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Nouveau mot de passe
                </label>
                <input type="password" name="new_password" id="new_password"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       placeholder="Min. 8 caractères (Majuscule, Chiffre, Symbole)" required>
            </div>

            <!-- Confirmation Nouveau Mot de passe -->
            <div>
                <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Confirmer le nouveau mot de passe
                </label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       placeholder="Confirmez le nouveau mot de passe" required>
            </div>

            <!-- Critères de sécurité -->
            <div class="bg-blue-50/70 p-4 rounded-2xl border border-blue-100 text-xs text-slate-600 space-y-1.5">
                <p class="font-bold text-[#1B3A8C] mb-1">Exigences de sécurité :</p>
                <div class="flex items-center space-x-2">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Au moins 8 caractères</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Au moins 1 majuscule, 1 chiffre et 1 symbole (@$!%*?&)</span>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-[#1B3A8C] hover:bg-[#142B6B] text-white py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl hover:shadow-blue-900/30 flex items-center justify-center space-x-2">
                <span>Enregistrer & Accéder au Tableau de Bord</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection
