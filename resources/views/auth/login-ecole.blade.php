@extends('layouts.app')

@section('title', 'Connexion Espace École - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center relative p-4"
     style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
    
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-[#0D1B4B]/75 backdrop-blur-[3px]"></div>
    
    <div class="relative z-10 w-full max-w-5xl flex flex-col md:flex-row bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        
        <!-- Partie gauche : Message d'accueil (Image 1 Style) -->
        <div class="hidden md:flex md:w-1/2 p-12 flex-col justify-between text-white relative">
            <div>
                <div class="w-16 h-16 rounded-2xl bg-white p-2 shadow-lg mb-8 inline-block">
                    <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL" class="w-full h-full object-contain">
                </div>
                
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/15 shadow-inner">
                    <h1 class="text-3xl font-extrabold mb-3 leading-tight">
                        Bienvenue sur STAGILOG !
                    </h1>
                    <h2 class="text-xl font-medium text-blue-100 mb-6 leading-snug">
                        Gérez vos stages académiques en toute simplicité
                    </h2>
                    <p class="text-sm text-blue-200/90 leading-relaxed">
                        Accédez au portail sécurisé pour soumettre vos dossiers d'étudiants, suivre l'état de validation et récupérer les rapports de stage validés par <strong>TFG SARL</strong>.
                    </p>
                </div>
            </div>

        </div>
        
        <!-- Partie droite : Formulaire de connexion -->
        <div class="w-full md:w-1/2 bg-white p-8 sm:p-12 flex flex-col justify-center">
            
            <div class="text-center mb-8">
                <div class="md:hidden flex justify-center mb-4">
                    <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG" class="h-12">
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-[#0D1B4B] tracking-tight">Se connecter</h3>
                <p class="text-sm text-slate-500 mt-1">Accédez à votre espace école partenaire</p>
            </div>
            
            <form method="POST" action="{{ route('login.ecole.submit') }}" class="space-y-5">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                               placeholder="admin@votre-ecole.edu" required autofocus>
                    </div>
                </div>
                
                <!-- Mot de passe -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Mot de passe
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1B3A8C] hover:underline">
                            Mot de passe oublié ?
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
                    <label for="remember" class="ml-2 text-xs font-medium text-slate-600">Se souvenir de moi sur cet appareil</label>
                </div>
                
                <!-- Bouton Connexion Vert / TFG -->
                <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-emerald-600/30 flex items-center justify-center space-x-2">
                    <span>Se connecter</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
            
            <!-- Retour Accueil -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('welcome') }}" 
                   class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-[#1B3A8C] transition group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1.5 transition-transform duration-300" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
