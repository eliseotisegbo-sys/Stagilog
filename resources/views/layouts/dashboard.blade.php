@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#F0F4FF]">
    
    <!-- SIDEBAR GAUCHE (Deep Navy #1B3A8C) -->
    <aside class="w-72 bg-[#1B3A8C] text-white flex-shrink-0 flex flex-col justify-between p-6 shadow-2xl relative z-30 transition-all duration-300">
        
        <!-- Haut de la Sidebar -->
        <div>
            <!-- Brand / Logo TFG -->
            <div class="flex items-center space-x-3.5 px-2 py-3 mb-8 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15">
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center p-1.5 shadow-md flex-shrink-0">
                    <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL" class="w-full h-full object-contain">
                </div>
                <div class="overflow-hidden">
                    <h1 class="text-lg font-extrabold tracking-wide text-white leading-tight">STAGILOG</h1>
                    <p class="text-[11px] text-blue-200 uppercase font-semibold tracking-wider truncate">TFG SARL Plateforme</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
                @if(auth()->user()->isAdmin())
                    <!-- Liens Administrateur -->
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-blue-300/80">Menu Principal</div>
                    
                    <a href="{{ route('dashboard.admin') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('dashboard.admin') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard.admin') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Tableau de Bord</span>
                    </a>

                    <a href="{{ route('admin.ecoles.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.ecoles.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.ecoles.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Écoles Partenaires</span>
                    </a>

                    <a href="{{ route('admin.dossiers.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.dossiers.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dossiers.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Dossiers de Stage</span>
                    </a>

                    <a href="{{ route('admin.rapports.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.rapports.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.rapports.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Rapports & PV</span>
                    </a>

                    <a href="{{ route('admin.filieres.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.filieres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.filieres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Filières & Cycles</span>
                    </a>

                    <a href="{{ route('admin.parametres.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('admin.parametres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.parametres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Paramètres</span>
                    </a>

                @else
                    <!-- Liens École -->
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-blue-300/80">Espace École</div>
                    
                    <a href="{{ route('dashboard.ecole') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('dashboard.ecole') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard.ecole') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Tableau de Bord</span>
                    </a>

                    <a href="{{ route('ecole.dossiers.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('ecole.dossiers.index') || request()->routeIs('ecole.dossiers.show') || request()->routeIs('ecole.dossiers.edit') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.dossiers.index') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Mes Dossiers de Stage</span>
                    </a>

                    <a href="{{ route('ecole.dossiers.create') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('ecole.dossiers.create') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.dossiers.create') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Déposer un Dossier</span>
                    </a>

                    <a href="{{ route('ecole.rapports.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('ecole.rapports.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.rapports.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Rapports des Étudiants</span>
                    </a>

                    <a href="{{ route('ecole.parametres.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-2xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('ecole.parametres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.parametres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Paramètres</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Bas de la Sidebar (Profil Utilisateur & Logout) -->
        <div class="pt-6 border-t border-white/15">
            <div class="flex items-center justify-between p-3 bg-white/10 rounded-2xl border border-white/10">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#E8001D] to-orange-500 flex items-center justify-center text-white font-bold shadow-md flex-shrink-0">
                        {{ strtoupper(substr(session('user_session_name', auth()->user()->name), 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white truncate">{{ session('user_session_name', auth()->user()->name) }}</p>
                        <p class="text-[10px] text-blue-200 uppercase tracking-wider font-semibold">
                            {{ auth()->user()->isAdmin() ? 'Super Admin' : (auth()->user()->ecole ? auth()->user()->ecole->nom_ecole : 'École') }}
                        </p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 ml-2">
                    @csrf
                    <button type="submit" title="Se déconnecter" class="p-2 text-blue-200 hover:text-white hover:bg-white/15 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ZONE PRINCIPALE DE CONTENU -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
        
        <!-- TOPBAR HEADER (Design Pro & Épuré) -->
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-20 border-b border-slate-100 px-8 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-[#0D1B4B] tracking-tight">
                    @yield('header_title', 'Tableau de bord')
                </h2>
            </div>

            <!-- Actions Header -->
            <div class="flex items-center space-x-4">
                <!-- Date badge -->
                <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-slate-500 bg-slate-100 px-3.5 py-2 rounded-xl border border-slate-200/60">
                    <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</span>
                </div>

                <!-- Notifications Button with Interactive Dropdown -->
                <div class="relative" id="notif-dropdown-wrapper">
                    <button id="notif-btn" type="button" 
                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-slate-50 hover:text-[#1B3A8C] transition shadow-sm relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notif-badge" class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-[#E8001D] text-white text-[10px] font-black rounded-full border-2 border-white flex items-center justify-center">0</span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div id="notif-panel" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-50 transition-all duration-200">
                        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#0D1B4B]">Centre de Notifications</h4>
                            <button type="button" onclick="markAllNotificationsRead()" class="text-[11px] text-[#1B3A8C] hover:underline font-bold">
                                Tout marquer comme lu
                            </button>
                        </div>

                        <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
                            <p class="text-slate-400 text-center py-6">Chargement...</p>
                        </div>
                    </div>
                </div>

                <!-- Avatar Profile -->
                <div class="flex items-center space-x-3 pl-2 border-l border-slate-200">
                    <div class="w-10 h-10 rounded-xl bg-[#1B3A8C] text-white flex items-center justify-center font-bold text-sm shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN VIEW SLOT -->
        <main class="p-8 flex-1">
            @yield('dashboard_content')
        </main>
    </div>
</div>

<script>
// Gestionnaire interactif de notifications
document.addEventListener('DOMContentLoaded', function() {
    const notifBtn = document.getElementById('notif-btn');
    const notifPanel = document.getElementById('notif-panel');
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');

    function loadNotifications() {
        fetch('{{ route("notifications.get") }}')
            .then(res => res.json())
            .then(data => {
                if (data.unread_count > 0) {
                    notifBadge.innerText = data.unread_count > 9 ? '9+' : data.unread_count;
                    notifBadge.classList.remove('hidden');
                } else {
                    notifBadge.classList.add('hidden');
                }

                if (data.notifications.length === 0) {
                    notifList.innerHTML = '<p class="text-slate-400 text-center py-8 text-xs">Aucune notification pour le moment.</p>';
                    return;
                }

                let html = '';
                data.notifications.forEach(n => {
                    let iconBg = 'bg-blue-50 text-[#1B3A8C]';
                    if (n.type === 'dossier_valide') iconBg = 'bg-emerald-100 text-emerald-700';
                    if (n.type === 'dossier_refuse') iconBg = 'bg-red-100 text-[#E8001D]';
                    if (n.type === 'rapport_depose') iconBg = 'bg-indigo-100 text-indigo-700';

                    const unreadClass = !n.lu ? 'bg-blue-50/40 font-semibold' : '';
                    const url = n.lien ? `/notifications/${n.id}/read` : '#';

                    html += `
                        <a href="${url}" class="p-4 flex items-start space-x-3 hover:bg-slate-50 transition block ${unreadClass}">
                            <div class="w-8 h-8 rounded-xl ${iconBg} flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#0D1B4B]">${n.titre}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">${n.message}</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">${new Date(n.created_at).toLocaleDateString('fr-FR', {day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            ${!n.lu ? '<span class="w-2 h-2 rounded-full bg-[#E8001D] flex-shrink-0 mt-1.5"></span>' : ''}
                        </a>
                    `;
                });
                notifList.innerHTML = html;
            })
            .catch(err => console.log('Erreur chargement notifications', err));
    }

    // Charger dès l'arrivée sur la page
    loadNotifications();

    // Toggle Dropdown
    notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifPanel.classList.toggle('hidden');
        if (!notifPanel.classList.contains('hidden')) {
            loadNotifications();
        }
    });

    // Fermer si clic ailleurs
    document.addEventListener('click', function(e) {
        if (!document.getElementById('notif-dropdown-wrapper').contains(e.target)) {
            notifPanel.classList.add('hidden');
        }
    });
});

function markAllNotificationsRead() {
    fetch('{{ route("notifications.markAllRead") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        document.getElementById('notif-badge').classList.add('hidden');
        const unreadItems = document.querySelectorAll('#notif-list .bg-blue-50\\/40');
        unreadItems.forEach(el => el.classList.remove('bg-blue-50/40', 'font-semibold'));
        const dots = document.querySelectorAll('#notif-list .bg-\\[\\#E8001D\\]');
        dots.forEach(d => d.remove());
    });
}
</script>
@endsection
