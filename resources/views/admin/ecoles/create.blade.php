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
                <p class="text-xs text-slate-500">Renseignez les coordonnées de l'école et ajoutez son logo officiel (optionnel). Vous pourrez créer son compte d'accès depuis la liste.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ecoles.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Zone Upload Logo de l'École (Optionnel) -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-dashed border-slate-200 flex flex-col sm:flex-row items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm relative group">
                    <img id="logo-preview-img" src="" alt="Prévisualisation Logo" class="w-full h-full object-contain hidden p-1">
                    <div id="logo-placeholder" class="text-center p-2">
                        <svg class="w-7 h-7 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[9px] font-bold text-slate-400 block mt-0.5">Logo</span>
                    </div>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#0D1B4B] mb-1">
                        Logo Officiel de l'Établissement <span class="text-slate-400 text-[11px] font-normal lowercase">(optionnel)</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mb-3">Formats supportés : PNG, JPG, WEBP, SVG (Max 2 Mo). Vous pouvez l'ajouter maintenant ou laisser l'école le faire.</p>
                    <label for="logo" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-white border border-slate-200 hover:border-[#1B3A8C] text-[#1B3A8C] rounded-xl text-xs font-bold shadow-sm cursor-pointer hover:bg-blue-50/50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span id="logo-btn-text">Choisir un fichier</span>
                    </label>
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                </div>
            </div>

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

@push('scripts')
<script>
    // Auto-uppercase le champ sigle
    document.getElementById('sigle').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Prévisualisation du logo
    function previewLogo(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview-img');
                const placeholder = document.getElementById('logo-placeholder');
                const btnText = document.getElementById('logo-btn-text');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                btnText.textContent = input.files[0].name;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
