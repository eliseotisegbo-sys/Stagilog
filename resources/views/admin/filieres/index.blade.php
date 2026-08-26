@extends('layouts.dashboard')

@section('title', 'Filières & Cycles - STAGILOG')
@section('header_title', 'Filières & Cycles de Formation')

@section('dashboard_content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Colonne Gauche : Liste des Filières (8 cols) -->
    <div class="lg:col-span-8 bg-white rounded-3xl shadow-card border border-slate-100 p-8 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-[#0D1B4B]">Filières Techniques</h3>
                <p class="text-xs font-medium text-slate-400">Domaines de formation ouverts aux stages chez TFG SARL</p>
            </div>
            <span class="text-xs font-bold text-[#1B3A8C] bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                {{ $filieres->count() }} filières configurées
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Filière & Sigle</th>
                        <th class="py-3.5 px-4">Description</th>
                        <th class="py-3.5 px-4 text-center">Dossiers</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($filieres as $filiere)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-[#0D1B4B]">{{ $filiere->nom_filiere }}</div>
                            @if($filiere->sigle)
                                <span class="text-[10px] font-mono font-bold text-[#1B3A8C] bg-blue-50 px-2 py-0.5 rounded-md mt-0.5 inline-block">{{ $filiere->sigle }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 text-[11px] max-w-xs">
                            {{ $filiere->description ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-xl text-slate-700">{{ $filiere->dossiers_count }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $filiere->actif ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                {{ $filiere->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <form action="{{ route('admin.filieres.toggle', $filiere->id_filiere) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold transition {{ $filiere->actif ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                                        {{ $filiere->actif ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.filieres.destroy', $filiere->id_filiere) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer définitivement cette filière ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold bg-red-50 text-[#E8001D] hover:bg-red-100 border border-red-200 transition">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Aucune filière configurée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Colonne Droite : Formulaires Ajout Filière & Cycles (4 cols) -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Formulaire Nouvelle Filière -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8">
            <h4 class="text-base font-extrabold text-[#0D1B4B] mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#1B3A8C]"></span>
                <span>Ajouter une Filière</span>
            </h4>
            
            <form method="POST" action="{{ route('admin.filieres.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nom_filiere" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nom de la filière <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="nom_filiere" id="nom_filiere" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Ex: Cybersécurité" required>
                </div>

                <div>
                    <label for="sigle_filiere" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Sigle (Optionnel)
                    </label>
                    <input type="text" name="sigle" id="sigle_filiere" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Ex: CS, GL, RT...">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Description / Spécialités
                    </label>
                    <textarea name="description" id="description" rows="2"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                              placeholder="Spécialités couvertes..."></textarea>
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-xl font-bold text-xs shadow-md transition">
                    Créer la Filière
                </button>
            </form>
        </div>

        <!-- Gestion des Cycles Académiques (Ajout & Suppression) -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between">
                <h4 class="text-base font-extrabold text-[#0D1B4B] flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Cycles Académiques</span>
                </h4>
                <span class="text-[11px] font-bold text-slate-400">{{ $cycles->count() }} cycles</span>
            </div>

            <!-- Liste des Cycles avec suppression -->
            <div class="space-y-2">
                @foreach($cycles as $cycle)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div>
                        <span class="font-bold text-[#0D1B4B]">{{ $cycle->nom_cycle }}</span>
                        <span class="text-[10px] text-slate-400 block">{{ $cycle->dossiers_count }} dossier(s)</span>
                    </div>

                    @if($cycle->dossiers_count == 0)
                    <form action="{{ route('admin.cycles.destroy', $cycle->id_cycle) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce cycle ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 text-slate-400 hover:text-[#E8001D] transition" title="Supprimer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Formulaire Ajout Rapide de Cycle -->
            <form method="POST" action="{{ route('admin.cycles.store') }}" class="pt-3 border-t border-slate-100 space-y-2">
                @csrf
                <label for="nom_cycle" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600">Ajouter un nouveau cycle</label>
                <div class="flex items-center space-x-2">
                    <input type="text" name="nom_cycle" id="nom_cycle" required
                           placeholder="Ex: Licence, Master, Ingénieur..."
                           class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" 
                            class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
