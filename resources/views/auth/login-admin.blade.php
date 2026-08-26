@extends('layouts.app')

@section('title', 'Administration Sécurisée - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0D1B4B] via-[#1B3A8C] to-[#0D1B4B] p-4 relative overflow-hidden">
    
    <!-- Decorative background glow -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#E8001D]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">
        
        <!-- Header / Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#F0F4FF] rounded-2xl mx-auto flex items-center justify-center p-2 shadow-inner mb-4 border border-blue-100">
                <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG Logo" class="w-full h-full object-contain">
            </div>
            
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-red-50 text-[#E8001D] rounded-full text-[11px] font-bold uppercase tracking-wider mb-2 border border-red-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Accès Restreint TFG</span>
            </div>
            
            <h1 class="text-2xl font-black text-[#0D1B4B] tracking-tight">Espace Administration</h1>
            <p class="text-xs text-slate-500 mt-1">Plateforme interne de supervision STAGILOG</p>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('login.admin.submit') }}" class="space-y-5">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Identifiant Administrateur
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="admin@tfg-sarl.com" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Mot de passe
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1B3A8C] hover:underline">
                        Récupération
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input type="password" name="password" id="password"
                           class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="••••••••" required>
                </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-[#1B3A8C] border-slate-300 rounded focus:ring-[#1B3A8C]">
                <label for="remember" class="ml-2 text-xs font-medium text-slate-600">Maintenir la session active</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-[#1B3A8C] hover:bg-[#142B6B] text-white py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl hover:shadow-blue-900/30 flex items-center justify-center space-x-2">
                <span>Accéder au Panneau d'Administration</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <a href="{{ route('welcome') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                &larr; Retour au portail public
            </a>
        </div>
    </div>
</div>
@endsection
