@extends('layouts.app')

@section('title', 'Nouveau Mot de Passe - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0D1B4B] via-[#1B3A8C] to-[#0D1B4B] p-4 relative">
    
    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/20">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center p-3 shadow-inner mb-4 border border-blue-100">
                <svg class="w-8 h-8 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl font-black text-[#0D1B4B] tracking-tight">Nouveau Mot de Passe</h1>
            <p class="text-xs text-slate-500 mt-2">
                Choisissez un nouveau mot de passe sécurisé pour votre compte.
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Adresse Email
                </label>
                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       required readonly>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Nouveau Mot de passe
                </label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       placeholder="Min. 8 caractères" required autofocus>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Confirmer le Mot de passe
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                       placeholder="Confirmez le mot de passe" required>
            </div>

            <button type="submit" 
                    class="w-full bg-[#1B3A8C] hover:bg-[#142B6B] text-white py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl hover:shadow-blue-900/30 flex items-center justify-center space-x-2">
                <span>Mettre à Jour le Mot de Passe</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection
