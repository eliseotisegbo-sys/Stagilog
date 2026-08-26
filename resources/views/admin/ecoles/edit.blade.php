@extends('layouts.dashboard')

@section('title', 'Modifier ' . $ecole->nom_ecole . ' - STAGILOG')
@section('header_title', 'Modifier l\'Établissement')

@section('dashboard_content')
<div class="max-w-3xl mx-auto">
    
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 sm:p-10">
        
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-slate-100">
            <a href="{{ route('admin.ecoles.index') }}" class="p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-black text-[#0D1B4B]">Mise à Jour de l'Établissement</h3>
                <p class="text-xs text-slate-500">Modifiez les coordonnées et éventuellement le mot de passe du compte</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ecoles.update', $ecole->id_ecole) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom École -->
                <div class="sm:col-span-2">
                    <label for="nom_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom de l'Université / École <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_ecole" id="nom_ecole" value="{{ old('nom_ecole', $ecole->nom_ecole) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           required>
                </div>

                <!-- Email officiel -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Email Officiel <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $ecole->email ?? $ecole->mail) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           required>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Numéro de Téléphone
                    </label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $ecole->telephone ?? $ecole->num_ecole) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                </div>

                <!-- Nouveau mot de passe -->
                <div class="sm:col-span-2">
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nouveau mot de passe du compte (laisser vide pour ne pas changer)
                    </label>
                    <input type="text" name="new_password" id="new_password"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Entrez un nouveau mot de passe si réinitialisation">
                </div>

                <!-- Adresse physique -->
                <div class="sm:col-span-2">
                    <label for="adresse_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse / Campus
                    </label>
                    <input type="text" name="adresse_ecole" id="adresse_ecole" value="{{ old('adresse_ecole', $ecole->adresse_ecole) }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                </div>
            </div>

            <!-- Actions Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.ecoles.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
