@extends('layouts.app')

@section('title', 'Réinitialisation de Mot de Passe - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0D1B4B] via-[#1B3A8C] to-[#0D1B4B] p-4 relative">
    
    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#F0F4FF] rounded-2xl mx-auto flex items-center justify-center p-3 shadow-inner mb-4 border border-blue-100">
                <svg class="w-8 h-8 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl font-black text-[#0D1B4B] tracking-tight">Mot de Passe Oublié</h1>
            <p class="text-xs text-slate-500 mt-2">
                Saisissez votre adresse email pour recevoir un lien de réinitialisation.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Adresse Email Associée
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="votre-email@etablissement.edu" required autofocus>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-[#1B3A8C] hover:bg-[#142B6B] text-white py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl hover:shadow-blue-900/30 flex items-center justify-center space-x-2">
                <span>Envoyer le Lien de Réinitialisation</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <a href="{{ route('login.ecole') }}" class="text-xs font-bold text-slate-500 hover:text-[#1B3A8C] transition">
                &larr; Retour à la page de connexion
            </a>
        </div>
    </div>
</div>
@endsection
