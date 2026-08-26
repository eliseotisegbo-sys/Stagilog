@extends('layouts.dashboard')

@section('title', 'Nouvelle École - STAGILOG')
@section('header_title', 'Enregistrer un Établissement')

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
                <h3 class="text-xl font-black text-[#0D1B4B]">Informations de l'Établissement</h3>
                <p class="text-xs text-slate-500">Renseignez les coordonnées de l'école. Vous pourrez créer son compte d'accès depuis la liste.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ecoles.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom École -->
                <div class="sm:col-span-2">
                    <label for="nom_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom de l'Université / École <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_ecole" id="nom_ecole" value="{{ old('nom_ecole') }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Ex: École Supérieure Polytechnique (ESP)" required>
                </div>

                <!-- Sigle (REQUIS pour la nomenclature des dossiers) -->
                <div>
                    <label for="sigle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Sigle de l'Établissement <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="sigle" id="sigle" value="{{ old('sigle') }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition uppercase"
                           placeholder="Ex: ESP, UCAD, ISM, ESMT...">
                    <p class="text-[10px] text-slate-400 mt-1">Utilisé dans la numérotation automatique des dossiers (ex: ESP-260820261530)</p>
                </div>

                <!-- Email officiel de contact -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Email Officiel <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="contact@etablissement.edu" required>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Numéro de Téléphone
                    </label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="+221 33 000 00 00">
                </div>

                <!-- Adresse physique -->
                <div class="sm:col-span-2">
                    <label for="adresse_ecole" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse / Campus
                    </label>
                    <input type="text" name="adresse_ecole" id="adresse_ecole" value="{{ old('adresse_ecole') }}"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Ex: Avenue Cheikh Anta Diop, Dakar">
                </div>
            </div>

            <!-- Actions Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.ecoles.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                    Enregistrer l'Établissement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
    // Auto-uppercase le champ sigle
    document.getElementById('sigle').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
