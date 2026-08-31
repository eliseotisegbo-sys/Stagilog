@extends('layouts.dashboard')

@section('title', 'Modifier le Dossier ' . ($dossier->code_dossier ?? '') . ' - STAGILOG')
@section('header_title', 'Modifier le Dossier (Brouillon)')

@section('dashboard_content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 sm:p-10">
        
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-slate-100">
            <a href="{{ route('ecole.dossiers.index') }}" class="p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h3 class="text-xl font-black text-[#0D1B4B]">Modification du Dossier {{ $dossier->code_dossier ?? '' }}</h3>
                <p class="text-xs text-slate-500">Mettez à jour les informations du stage ou soumettez-le directement à TFG SARL.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('ecole.dossiers.update', $dossier->id_dossier) }}" enctype="multipart/form-data" id="dossier-form" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- SECTION 1 : INFORMATIONS GÉNÉRALES DU STAGE -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#1B3A8C] mb-4">
                    1. Informations Générales &amp; Période Demandée
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Filière -->
                    <div>
                        <label for="id_filiere" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Filière / Domaine Technique <span class="text-[#E8001D]">*</span>
                        </label>
                        <select name="id_filiere" id="id_filiere" required onchange="updateSigleSuggestion(this)"
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                            <option value="">-- Sélectionner une filière --</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_filiere }}" 
                                        data-sigle="{{ $filiere->sigle }}"
                                        {{ (old('id_filiere', $dossier->id_filiere) == $filiere->id_filiere) ? 'selected' : '' }}>
                                    {{ $filiere->nom_filiere }} {{ $filiere->sigle ? '('.$filiere->sigle.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_filiere') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Cycle Académique -->
                    <div>
                        <label for="id_cycle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Cycle Académique <span class="text-[#E8001D]">*</span>
                        </label>
                        <select name="id_cycle" id="id_cycle" required
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                            <option value="">-- Sélectionner un cycle --</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id_cycle }}" {{ (old('id_cycle', $dossier->id_cycle) == $cycle->id_cycle) ? 'selected' : '' }}>
                                    {{ $cycle->nom_cycle }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cycle') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type de stage -->
                    <div>
                        <label for="type_stage" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Type de Stage <span class="text-[#E8001D]">*</span>
                        </label>
                        <select name="type_stage" id="type_stage" required
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                            <option value="">-- Sélectionner le type --</option>
                            @php $currType = old('type_stage', $dossier->type_stage); @endphp
                            <option value="Stage Académique / Fin d'études" {{ $currType == "Stage Académique / Fin d'études" ? 'selected' : '' }}>Stage Académique / Fin d'études</option>
                            <option value="Stage Pratique / Immersion" {{ $currType == 'Stage Pratique / Immersion' ? 'selected' : '' }}>Stage Pratique / Immersion</option>
                            <option value="Stage Professionnel" {{ $currType == 'Stage Professionnel' ? 'selected' : '' }}>Stage Professionnel</option>
                        </select>
                        @error('type_stage') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Année Académique -->
                    <div>
                        <label for="annee_academique" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Année Académique <span class="text-[#E8001D]">*</span>
                        </label>
                        <input type="text" name="annee_academique" id="annee_academique" 
                               value="{{ old('annee_academique', $dossier->annee_academique) }}" required
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                               placeholder="Ex: 2026-2027">
                        @error('annee_academique') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Note de Demande Officielle -->
                    <div class="sm:col-span-2">
                        <label for="note_demande_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Note de Demande Officielle de l'Établissement (Format PDF)
                            @if(!$dossier->note_demande) <span class="text-[#E8001D]">*</span> @endif
                        </label>
                        <input type="file" name="note_demande_file" id="note_demande_file" accept=".pdf"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                        @if($dossier->note_demande)
                            <p class="text-[11px] text-emerald-700 mt-1 font-semibold">
                                &check; Document actuellement enregistré : <a href="{{ asset('uploads/notes/' . $dossier->note_demande) }}" target="_blank" class="underline text-[#1B3A8C]">{{ $dossier->note_demande }}</a> (Laissez vide pour conserver)
                            </p>
                        @endif
                        @error('note_demande_file') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Période globale du dossier (Calendrier Harmonisé Flatpickr) -->
                    <div>
                        <label for="datedebut" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Date de Début Prévue (Dossier) <span class="text-[#E8001D]">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="datedebut" id="datedebut" 
                                   value="{{ old('datedebut', $dossier->datedebut ? $dossier->datedebut->format('Y-m-d') : '') }}" required
                                   placeholder="Sélectionner la date de début..."
                                   class="stagilog-datepicker w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        @error('datedebut') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="datefin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Date de Fin Prévue (Dossier) <span class="text-[#E8001D]">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="datefin" id="datefin" 
                                   value="{{ old('datefin', $dossier->datefin ? $dossier->datefin->format('Y-m-d') : '') }}" required
                                   placeholder="Sélectionner la date de fin..."
                                   class="stagilog-datepicker w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        @error('datefin') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Badge dynamique de calcul de durée de stage -->
                    <div class="sm:col-span-2" id="duration-badge-container" style="display: none;">
                        <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-2xl flex items-center space-x-3 text-xs text-[#1B3A8C] font-semibold">
                            <svg class="w-4 h-4 text-[#1B3A8C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="duration-badge-text">Période calculée...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2 : CANDIDATS / ÉTUDIANTS (AVEC PÉRIODE PAR ÉTUDIANT & SYNC GLOBALE) -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#1B3A8C]">
                            2. Liste des Candidats / Étudiants
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Renseignez les étudiants et leurs dates de stage respectives.</p>
                    </div>

                    <!-- Bouton pour appliquer la période globale à tous les étudiants -->
                    <button type="button" onclick="applyGlobalPeriodToAllStudents()" 
                            class="inline-flex items-center space-x-2 bg-blue-50 hover:bg-blue-100 text-[#1B3A8C] px-3.5 py-2 rounded-xl text-xs font-bold border border-blue-200 transition shadow-sm">
                        <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Appliquer la période du dossier à tous les étudiants</span>
                    </button>
                </div>

                <!-- Conteneur des cartes étudiants -->
                <div id="students-container" class="space-y-4">
                    @php
                        $etudiantsData = old('etudiants');
                        if (!$etudiantsData) {
                            $etudiantsData = $dossier->etudiants->map(function($e) use ($dossier) {
                                return [
                                    'nom' => $e->nom_etudiant,
                                    'prenom' => $e->prenom_etudiant,
                                    'email' => $e->email_etu,
                                    'niveau_etude' => $e->niveau_etude,
                                    'date_naissance' => $e->date_naissance ? $e->date_naissance->format('Y-m-d') : '',
                                    'datedebut_stage' => $e->datedebut_stage ? $e->datedebut_stage->format('Y-m-d') : ($dossier->datedebut ? $dossier->datedebut->format('Y-m-d') : ''),
                                    'datefin_stage' => $e->datefin_stage ? $e->datefin_stage->format('Y-m-d') : ($dossier->datefin ? $dossier->datefin->format('Y-m-d') : ''),
                                    'existing_cv' => $e->cv,
                                ];
                            })->toArray();
                        }
                    @endphp

                    @foreach($etudiantsData as $index => $etu)
                    <div class="student-card bg-slate-50/80 p-6 rounded-2xl border border-slate-200/80 relative transition-all duration-300" data-index="{{ $index }}">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="student-number text-xs font-extrabold text-[#0D1B4B] uppercase tracking-wider">
                                    Étudiant {{ $index + 1 }}
                                </span>
                                <span class="student-status-badge px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">
                                    En cours de saisie
                                </span>
                            </div>
                            <button type="button" onclick="removeStudentCard(this)" 
                                    class="btn-remove-student text-slate-400 hover:text-[#E8001D] text-xs font-bold flex items-center space-x-1 p-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Supprimer</span>
                            </button>
                        </div>

                        <input type="hidden" name="etudiants[{{ $index }}][existing_cv]" value="{{ $etu['existing_cv'] ?? '' }}">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Nom <span class="text-[#E8001D]">*</span>
                                </label>
                                <input type="text" name="etudiants[{{ $index }}][nom]" value="{{ $etu['nom'] ?? '' }}" required
                                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                                       placeholder="Nom de famille">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Prénom(s) <span class="text-[#E8001D]">*</span>
                                </label>
                                <input type="text" name="etudiants[{{ $index }}][prenom]" value="{{ $etu['prenom'] ?? '' }}" required
                                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                                       placeholder="Prénom(s)">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Niveau d'Étude <span class="text-[#E8001D]">*</span>
                                </label>
                                <select name="etudiants[{{ $index }}][niveau_etude]" required
                                        class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                                    <option value="">-- Niveau --</option>
                                    @php $niv = $etu['niveau_etude'] ?? ''; @endphp
                                    <option value="Licence 1" {{ $niv == 'Licence 1' ? 'selected' : '' }}>Licence 1 (L1)</option>
                                    <option value="Licence 2" {{ $niv == 'Licence 2' ? 'selected' : '' }}>Licence 2 (L2)</option>
                                    <option value="Licence 3" {{ $niv == 'Licence 3' ? 'selected' : '' }}>Licence 3 / Fin de cycle (L3)</option>
                                    <option value="Master 1" {{ $niv == 'Master 1' ? 'selected' : '' }}>Master 1 (M1)</option>
                                    <option value="Master 2" {{ $niv == 'Master 2' ? 'selected' : '' }}>Master 2 / Fin d'études (M2)</option>
                                    <option value="Ingénieur 1" {{ $niv == 'Ingénieur 1' ? 'selected' : '' }}>Ingénieur - 1ère Année</option>
                                    <option value="Ingénieur 2" {{ $niv == 'Ingénieur 2' ? 'selected' : '' }}>Ingénieur - 2ème Année</option>
                                    <option value="Ingénieur 3 (Fin d'études)" {{ $niv == "Ingénieur 3 (Fin d'études)" ? 'selected' : '' }}>Ingénieur - 3ème Année (Fin d'études)</option>
                                    <option value="DUT / BTS" {{ $niv == 'DUT / BTS' ? 'selected' : '' }}>DUT / BTS</option>
                                    <option value="Autre" {{ $niv == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Email Étudiant <span class="text-[#E8001D]">*</span>
                                </label>
                                <input type="email" name="etudiants[{{ $index }}][email]" value="{{ $etu['email'] ?? '' }}" required
                                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                                       placeholder="etudiant@domaine.com">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Date de Début du Stage
                                </label>
                                <input type="text" name="etudiants[{{ $index }}][datedebut_stage]" value="{{ $etu['datedebut_stage'] ?? '' }}"
                                       placeholder="Début du stage..."
                                       class="student-stage-start stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Date de Fin du Stage
                                </label>
                                <input type="text" name="etudiants[{{ $index }}][datefin_stage]" value="{{ $etu['datefin_stage'] ?? '' }}"
                                       placeholder="Fin du stage..."
                                       class="student-stage-end stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Date de Naissance (Optionnelle)
                                </label>
                                <input type="text" name="etudiants[{{ $index }}][date_naissance]" value="{{ $etu['date_naissance'] ?? '' }}"
                                       placeholder="Sélectionner la date..."
                                       class="birth-datepicker stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                                    Curriculum Vitae
                                </label>
                                <input type="file" name="etudiants[{{ $index }}][cv_file]" accept=".pdf,.doc,.docx"
                                       class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-xl text-[11px] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1B3A8C]">
                                @if(!empty($etu['existing_cv']))
                                    <p class="text-[10px] text-emerald-700 font-semibold mt-1">
                                        &check; CV joint : <a href="{{ asset('uploads/cv/' . $etu['existing_cv']) }}" target="_blank" class="underline text-[#1B3A8C]">{{ $etu['existing_cv'] }}</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- BOUTON AJOUTER UN ÉTUDIANT -->
                <div class="mt-5 flex items-center">
                    <button type="button" onclick="addStudentCard()" 
                            class="inline-flex items-center space-x-2 bg-[#EEF4FF] hover:bg-blue-100 text-[#1B3A8C] px-6 py-3 rounded-full text-xs font-black transition-all duration-200 shadow-sm border border-blue-200/60 hover:shadow-md transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajouter un étudiant</span>
                    </button>
                </div>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('ecole.dossiers.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                    Annuler
                </a>

                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <button type="submit" name="action" value="brouillon" formnovalidate
                            class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-slate-50 text-[#1B3A8C] border-2 border-[#1B3A8C] rounded-2xl font-bold text-xs shadow-sm transition">
                        Enregistrer les Modifications
                    </button>
                    <button type="submit" name="action" value="soumettre"
                            class="w-full sm:w-auto px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-xs shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                        Soumettre à TFG SARL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateSigleSuggestion(select) {
    const selectedOption = select.options[select.selectedIndex];
    const sigleValue = selectedOption.getAttribute('data-sigle');
    const sigleInput = document.getElementById('sigle');
    if (sigleInput && sigleValue && !sigleInput.value) {
        sigleInput.value = sigleValue;
    }
}

// Fonction unique et universelle pour initialiser les datepickers
function initStagilogDatepickers() {
    if (typeof flatpickr === 'undefined') return;
    flatpickr.localize(flatpickr.l10ns.fr);

    flatpickr("#datedebut", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: true,
        locale: "fr",
        onChange: function() {
            calculateDuration();
        }
    });

    flatpickr("#datefin", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: true,
        locale: "fr",
        onChange: function() {
            calculateDuration();
        }
    });

    // Date de naissance
    flatpickr(".birth-datepicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: true,
        maxDate: "today",
        locale: "fr"
    });

    // Période individuelle de stage par étudiant
    flatpickr(".student-stage-start, .student-stage-end", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: true,
        locale: "fr"
    });
}

function calculateDuration() {
    const d1Val = document.getElementById('datedebut').value;
    const d2Val = document.getElementById('datefin').value;
    const container = document.getElementById('duration-badge-container');
    const text = document.getElementById('duration-badge-text');

    if (d1Val && d2Val) {
        const d1 = new Date(d1Val);
        const d2 = new Date(d2Val);
        const diffTime = d2 - d1;
        const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 0) {
            const months = Math.round(diffDays / 30);
            const weeks = Math.round(diffDays / 7);
            text.innerHTML = `<strong>Durée estimée :</strong> ${diffDays} jours (~${months} mois / ${weeks} semaines) du ${d1.toLocaleDateString('fr-FR')} au ${d2.toLocaleDateString('fr-FR')}`;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    } else {
        container.style.display = 'none';
    }
}

function applyGlobalPeriodToAllStudents() {
    const globalStart = document.getElementById('datedebut').value;
    const globalEnd = document.getElementById('datefin').value;

    if (!globalStart || !globalEnd) {
        alert("Veuillez d'abord sélectionner la date de début et la date de fin prévues du dossier.");
        document.getElementById('datedebut').focus();
        return;
    }

    document.querySelectorAll('.student-card').forEach(card => {
        const startInput = card.querySelector('.student-stage-start');
        const endInput = card.querySelector('.student-stage-end');

        if (startInput) {
            startInput.value = globalStart;
            if (startInput._flatpickr) {
                startInput._flatpickr.setDate(globalStart, true);
            }
        }
        if (endInput) {
            endInput.value = globalEnd;
            if (endInput._flatpickr) {
                endInput._flatpickr.setDate(globalEnd, true);
            }
        }
    });

    checkAllCardsStatus();
}

function checkCardStatus(card) {
    const nom = card.querySelector('input[name*="[nom]"]')?.value.trim();
    const prenom = card.querySelector('input[name*="[prenom]"]')?.value.trim();
    const email = card.querySelector('input[name*="[email]"]')?.value.trim();
    const niveau = card.querySelector('select[name*="[niveau_etude]"]')?.value;
    const badge = card.querySelector('.student-status-badge');

    if (nom && prenom && email && niveau) {
        card.classList.remove('bg-slate-50/80', 'border-slate-200/80');
        card.classList.add('bg-emerald-50/40', 'border-emerald-400', 'shadow-sm');
        if (badge) {
            badge.className = 'student-status-badge px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700';
            badge.innerHTML = '&#10003; Complété';
        }
    } else {
        card.classList.remove('bg-emerald-50/40', 'border-emerald-400', 'shadow-sm');
        card.classList.add('bg-slate-50/80', 'border-slate-200/80');
        if (badge) {
            badge.className = 'student-status-badge px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600';
            badge.innerText = 'En cours de saisie';
        }
    }
}

function checkAllCardsStatus() {
    document.querySelectorAll('.student-card').forEach(checkCardStatus);
}

function attachCardListeners() {
    document.querySelectorAll('.student-card').forEach(card => {
        const inputs = card.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.removeEventListener('input', () => checkCardStatus(card));
            input.removeEventListener('change', () => checkCardStatus(card));
            input.addEventListener('input', () => checkCardStatus(card));
            input.addEventListener('change', () => checkCardStatus(card));
        });
    });
}

function addStudentCard() {
    const container = document.getElementById('students-container');
    const newIndex = Date.now();

    const globalStart = document.getElementById('datedebut').value || '';
    const globalEnd = document.getElementById('datefin').value || '';

    const card = document.createElement('div');
    card.className = 'student-card bg-slate-50/80 p-6 rounded-2xl border border-slate-200/80 relative transition-all duration-300';
    card.dataset.index = newIndex;
    
    card.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <span class="student-number text-xs font-extrabold text-[#0D1B4B] uppercase tracking-wider">
                    Étudiant
                </span>
                <span class="student-status-badge px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">
                    En cours de saisie
                </span>
            </div>
            <button type="button" onclick="removeStudentCard(this)" 
                    class="btn-remove-student text-slate-400 hover:text-[#E8001D] text-xs font-bold flex items-center space-x-1 p-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Supprimer</span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Nom <span class="text-[#E8001D]">*</span>
                </label>
                <input type="text" name="etudiants[${newIndex}][nom]" required
                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                       placeholder="Nom de famille">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Prénom(s) <span class="text-[#E8001D]">*</span>
                </label>
                <input type="text" name="etudiants[${newIndex}][prenom]" required
                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                       placeholder="Prénom(s)">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Niveau d'Étude <span class="text-[#E8001D]">*</span>
                </label>
                <select name="etudiants[${newIndex}][niveau_etude]" required
                        class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
                    <option value="">-- Niveau --</option>
                    <option value="Licence 1">Licence 1 (L1)</option>
                    <option value="Licence 2">Licence 2 (L2)</option>
                    <option value="Licence 3">Licence 3 / Fin de cycle (L3)</option>
                    <option value="Master 1">Master 1 (M1)</option>
                    <option value="Master 2">Master 2 / Fin d'études (M2)</option>
                    <option value="Ingénieur 1">Ingénieur - 1ère Année</option>
                    <option value="Ingénieur 2">Ingénieur - 2ème Année</option>
                    <option value="Ingénieur 3 (Fin d'études)">Ingénieur - 3ème Année (Fin d'études)</option>
                    <option value="DUT / BTS">DUT / BTS</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Email Étudiant <span class="text-[#E8001D]">*</span>
                </label>
                <input type="email" name="etudiants[${newIndex}][email]" required
                       class="student-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]"
                       placeholder="etudiant@domaine.com">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Date de Début du Stage
                </label>
                <input type="text" name="etudiants[${newIndex}][datedebut_stage]" value="${globalStart}"
                       placeholder="Début du stage..."
                       class="student-stage-start stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Date de Fin du Stage
                </label>
                <input type="text" name="etudiants[${newIndex}][datefin_stage]" value="${globalEnd}"
                       placeholder="Fin du stage..."
                       class="student-stage-end stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Date de Naissance (Optionnelle)
                </label>
                <input type="text" name="etudiants[${newIndex}][date_naissance]"
                       placeholder="Sélectionner la date..."
                       class="birth-datepicker stagilog-datepicker w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C]">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Curriculum Vitae (Optionnel)
                </label>
                <input type="file" name="etudiants[${newIndex}][cv_file]" accept=".pdf,.doc,.docx"
                       class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-xl text-[11px] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-[#1B3A8C]">
            </div>
        </div>
    `;

    container.appendChild(card);
    updateStudentNumbers();
    attachCardListeners();
    initStagilogDatepickers();
}

function removeStudentCard(btn) {
    const cards = document.querySelectorAll('.student-card');
    if (cards.length <= 1) {
        alert("Un dossier doit comporter au moins un étudiant.");
        return;
    }
    const card = btn.closest('.student-card');
    card.remove();
    updateStudentNumbers();
}

function updateStudentNumbers() {
    const cards = document.querySelectorAll('.student-card');
    cards.forEach((card, index) => {
        const numLabel = card.querySelector('.student-number');
        if (numLabel) {
            numLabel.innerText = 'Étudiant ' + (index + 1);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initStagilogDatepickers();
    attachCardListeners();
    checkAllCardsStatus();
    calculateDuration();
});
</script>
@endpush
@endsection
