@extends('layouts.dashboard')

@php
    $codeDossier = $dossier->code_dossier ?? (auth()->user()->ecole?->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '');
@endphp

@section('title', 'Dossier ' . $codeDossier . ' - STAGILOG')
@section('header_title', 'Dossier ' . $codeDossier . ' - ' . $dossier->filiere)

@section('dashboard_content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('ecole.dossiers.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-[#1B3A8C] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour à mes dossiers</span>
        </a>

        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $dossier->statut === 'valide' ? 'bg-emerald-100 text-emerald-700' : ($dossier->statut === 'refuse' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                Validation TFG : {{ $dossier->statut === 'valide' ? 'Validé' : ($dossier->statut === 'refuse' ? 'Refusé' : 'En attente') }}
            </span>
        </div>
    </div>

    <!-- Alert Rejet si refusé -->
    @if($dossier->statut === 'refuse' && $dossier->motif_refus)
    <div class="p-6 bg-red-50 border border-red-200 rounded-3xl text-red-900">
        <h4 class="text-sm font-bold text-red-800 flex items-center space-x-2">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Dossier Non Retenu par TFG SARL</span>
        </h4>
        <p class="text-xs text-red-700 mt-2"><strong>Motif indiqué :</strong> {{ $dossier->motif_refus }}</p>
    </div>
    @endif

    <!-- Détails du Dossier -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 space-y-6">
        <div class="border-b border-slate-100 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="font-mono text-xs font-bold text-[#1B3A8C] uppercase tracking-wider">{{ $codeDossier }}</span>
                <h3 class="text-2xl font-black text-[#0D1B4B] mt-0.5">{{ $dossier->filiere }}</h3>
                <p class="text-xs text-slate-400 mt-1">Soumis le {{ $dossier->created_at ? $dossier->created_at->locale('fr')->isoFormat('ddd. D MMMM YYYY [à] HH:mm') : '-' }}</p>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                    Année : {{ $dossier->annee_academique }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Cycle</p>
                <p class="text-sm font-bold text-[#1B3A8C] mt-1">{{ $dossier->cycle->nom_cycle ?? 'Standard' }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Type de Stage</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $dossier->type_stage ?? 'Stage professionnel' }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-2">
                <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Période Définie</p>
                <p class="text-sm font-bold text-slate-800 mt-1 lowercase">
                    {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                    <span class="text-slate-400 mx-1">au</span>
                    {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Liste des Étudiants -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-[#0D1B4B]">Étudiants Enregistrés ({{ $dossier->etudiants->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Nom & Prénom</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Niveau & Date Naissance</th>
                        <th class="py-4 px-6">Curriculum Vitae</th>
                        <th class="py-4 px-6 text-right">Rapports & Documents</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($dossier->etudiants as $etudiant)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-6 font-bold text-[#0D1B4B]">
                            {{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $etudiant->email_etu }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            <div>{{ $etudiant->niveau_etude ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400">Né(e) le {{ $etudiant->date_naissance ? $etudiant->date_naissance->locale('fr')->isoFormat('D MMM YYYY') : '-' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($etudiant->cv)
                            <a href="{{ asset('uploads/cv/' . $etudiant->cv) }}" target="_blank" 
                               class="text-xs font-bold text-[#1B3A8C] hover:underline inline-flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Voir CV</span>
                            </a>
                            @else
                            <span class="text-slate-400">Non fourni</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if($etudiant->documents && $etudiant->documents->count() > 0)
                                <div class="space-y-1">
                                    @foreach($etudiant->documents as $doc)
                                    <a href="{{ asset('uploads/rapports/' . $doc->fichier) }}" target="_blank"
                                       class="inline-flex items-center space-x-1 text-xs font-bold text-[#1B3A8C] bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-xl transition">
                                        <span>{{ $doc->nom_document }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                    @endforeach
                                </div>
                            @elseif($etudiant->rapport)
                                <a href="{{ asset('uploads/rapports/' . $etudiant->rapport) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-1.5 rounded-xl transition">
                                    <span>Rapport Disponible</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic">En cours</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
