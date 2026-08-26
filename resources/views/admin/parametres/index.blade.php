@extends('layouts.dashboard')

@section('title', 'Paramètres Administrateur - STAGILOG')
@section('header_title', 'Paramètres')

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- En-tête avec Avatar Admin -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
        <div class="relative flex-shrink-0">
            @if($user->photo_profil)
                <img src="{{ asset('uploads/avatars/' . $user->photo_profil) }}" alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-3xl object-cover border-4 border-white shadow-xl bg-slate-100">
            @else
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-[#1B3A8C] to-[#0D1B4B] text-white flex items-center justify-center font-black text-3xl shadow-xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif
            <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-xl bg-emerald-500 border-2 border-white flex items-center justify-center shadow-md">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <div class="text-center sm:text-left">
            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 text-[#E8001D] mb-2">
                Super Administrateur
            </div>
            <h2 class="text-2xl font-black text-[#0D1B4B]">{{ $user->name }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }} &bull; Technology Forever Group SARL</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-semibold flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.parametres.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Informations du profil + Photo -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
            <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-6 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#1B3A8C]"></span>
                <span>Informations du Compte & Photo</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom complet -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nom complet <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Votre nom complet">
                    @error('name') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Adresse Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Adresse Email <span class="text-[#E8001D]">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="admin@tfg-sarl.com">
                    @error('email') <p class="text-xs text-[#E8001D] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo de Profil (Upload) -->
                <div class="sm:col-span-2">
                    <label for="photo_profil" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Photo de Profil Administrateur (PNG, JPG, WEBP - Max. 4MB)
                    </label>
                    <input type="file" name="photo_profil" id="photo_profil" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1B3A8C] file:text-white hover:file:bg-[#142B6B] transition">
                    <p class="text-[10px] text-slate-400 mt-1.5">Cette photo sera affichée sur votre tableau de bord ainsi que dans l'espace des écoles partenaires en tant que représentant TFG SARL.</p>
                </div>
            </div>
        </div>

        <!-- Changement de mot de passe -->
        <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
            <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#E8001D]"></span>
                <span>Changer le Mot de Passe</span>
            </h3>
            <p class="text-[11px] text-slate-400 mb-6">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Mot de passe actuel
                    </label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Votre mot de passe actuel">
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" name="new_password" id="new_password"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Minimum 6 caractères">
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B3A8C] focus:bg-white transition"
                           placeholder="Confirmer le nouveau mot de passe">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-8 py-3.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-blue-900/20 transition transform hover:-translate-y-0.5">
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <!-- Historique des connexions récentes -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-100 p-8">
        <h3 class="text-sm font-extrabold text-[#0D1B4B] uppercase tracking-wider mb-2 flex items-center space-x-2">
            <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Historique des Connexions Récentes</span>
        </h3>
        <p class="text-[11px] text-slate-400 mb-6">Journal des 10 dernières tentatives d'accès à votre compte administrateur.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Date & Heure</th>
                        <th class="py-3 px-4">Adresse IP</th>
                        <th class="py-3 px-4">Navigateur & Appareil</th>
                        <th class="py-3 px-4 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($connexions as $conn)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#0D1B4B]">
                            {{ $conn->created_at ? $conn->created_at->locale('fr')->isoFormat('ddd. D MMMM YYYY [à] HH:mm') : '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500">
                            {{ $conn->ip_address ?? '127.0.0.1' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">{{ $conn->navigateur ?? 'Navigateur Web' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $conn->appareil ?? 'Ordinateur' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if($conn->statut === 'succes')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    Connexion Réussie
                                </span>
                            @elseif($conn->statut === 'deconnexion')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                    Déconnexion
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">
                                    Échec / OTP
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-slate-400">Aucune activité enregistrée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
