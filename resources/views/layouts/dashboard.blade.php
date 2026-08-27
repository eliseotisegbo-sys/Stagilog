@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom-datepicker.css') }}">
<style>
    /* Toast instantané flottant 5 secondes */
    .stagilog-toast {
        animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards, toastFadeOut 0.5s cubic-bezier(0.16, 1, 0.3, 1) 4.5s forwards;
    }
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes toastFadeOut {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to { opacity: 0; transform: translateY(-15px) scale(0.95); }
    }

    /* Tooltip pour sidebar réduite */
    .sidebar-collapsed .sidebar-link-item:hover::after {
        content: attr(data-title);
        position: absolute;
        left: 100%;
        margin-left: 0.75rem;
        padding: 0.35rem 0.75rem;
        background: #0D1B4B;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
        z-index: 9999;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<!-- Conteneur global avec position relative par-dessus l'animation de drapeau -->
<div class="min-h-screen flex relative z-10">
    
    <!-- Mobile Backdrop Overlay -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR GAUCHE (Deep Navy #1B3A8C avec toggle repliable) -->
    <aside id="main-sidebar" 
           class="sidebar-expanded bg-[#1B3A8C] text-white flex-shrink-0 flex flex-col justify-between p-5 shadow-2xl relative z-40 transition-all duration-300 fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0">
        
        <!-- Haut de la Sidebar -->
        <div>
            <!-- Brand / Logo TFG -->
            <div class="sidebar-brand flex items-center px-2 py-3 mb-6 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center p-1.5 shadow-md flex-shrink-0">
                        <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL" class="w-full h-full object-contain">
                    </div>
                    <div class="sidebar-text overflow-hidden">
                        <h1 class="text-base font-black tracking-wide text-white leading-tight">STAGILOG</h1>
                        <p class="text-[10px] text-blue-200 uppercase font-semibold tracking-wider truncate">TFG SARL</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links (Icônes parfaitement centrées) -->
            <nav class="space-y-1.5 flex flex-col items-stretch">
                @if(auth()->user()->isAdmin())
                    <!-- Liens Administrateur -->
                    <div class="sidebar-heading px-3 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-blue-300/80">Menu Principal</div>
                    
                    <a href="{{ route('dashboard.admin') }}" 
                       data-title="Tableau de Bord"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('dashboard.admin') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard.admin') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="sidebar-text truncate">Tableau de Bord</span>
                    </a>

                    <a href="{{ route('admin.ecoles.index') }}" 
                       data-title="Écoles Partenaires"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('admin.ecoles.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.ecoles.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="sidebar-text truncate">Écoles Partenaires</span>
                    </a>

                    <a href="{{ route('admin.dossiers.index') }}" 
                       data-title="Dossiers de Stage"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('admin.dossiers.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dossiers.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Dossiers de Stage</span>
                    </a>

                    <a href="{{ route('admin.rapports.index') }}" 
                       data-title="Rapports"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('admin.rapports.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.rapports.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Rapports</span>
                    </a>

                    <a href="{{ route('admin.filieres.index') }}" 
                       data-title="Filières & Cycles"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('admin.filieres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.filieres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="sidebar-text truncate">Filières &amp; Cycles</span>
                    </a>

                    <!-- Changé Paramètres par Profil -->
                    <a href="{{ route('admin.parametres.index') }}" 
                       data-title="Profil & Administrateurs"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('admin.parametres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.parametres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="sidebar-text truncate">Profil</span>
                    </a>

                @else
                    <!-- Liens École -->
                    <div class="sidebar-heading px-3 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-blue-300/80">Espace École</div>
                    
                    <a href="{{ route('dashboard.ecole') }}" 
                       data-title="Tableau de Bord"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('dashboard.ecole') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard.ecole') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="sidebar-text truncate">Tableau de Bord</span>
                    </a>

                    <a href="{{ route('ecole.dossiers.index') }}" 
                       data-title="Mes Dossiers de Stage"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('ecole.dossiers.index') || request()->routeIs('ecole.dossiers.show') || request()->routeIs('ecole.dossiers.edit') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.dossiers.index') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span class="sidebar-text truncate">Mes Dossiers</span>
                    </a>

                    <a href="{{ route('ecole.dossiers.create') }}" 
                       data-title="Déposer un Dossier"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('ecole.dossiers.create') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.dossiers.create') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sidebar-text truncate">Déposer un Dossier</span>
                    </a>

                    <a href="{{ route('ecole.rapports.index') }}" 
                       data-title="Rapports"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('ecole.rapports.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.rapports.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text truncate">Rapports</span>
                    </a>

                    <!-- Changé Paramètres par Profil -->
                    <a href="{{ route('ecole.parametres.index') }}" 
                       data-title="Mon Profil"
                       class="sidebar-link-item relative flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl font-semibold text-xs transition-all duration-200 {{ request()->routeIs('ecole.parametres.*') ? 'bg-white text-[#1B3A8C] shadow-lg font-bold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('ecole.parametres.*') ? 'text-[#E8001D]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="sidebar-text truncate">Profil</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Bas de la Sidebar (Photo de Profil + Nom de celui qui est connecté bien visibles) -->
        <div class="pt-4 border-t border-white/15">
            <div class="sidebar-user-box flex items-center justify-between p-2.5 bg-white/10 rounded-2xl border border-white/10">
                <div class="flex items-center space-x-3 min-w-0">
                    @if(auth()->user()->photo_profil)
                        <img src="{{ asset('uploads/avatars/' . auth()->user()->photo_profil) }}" alt="{{ auth()->user()->name }}"
                             class="w-10 h-10 rounded-2xl object-cover border-2 border-white/30 shadow-md flex-shrink-0">
                    @elseif(auth()->user()->ecole && auth()->user()->ecole->logo)
                        <img src="{{ asset('uploads/logos/' . auth()->user()->ecole->logo) }}" alt="{{ auth()->user()->ecole->nom_ecole }}"
                             class="w-10 h-10 rounded-2xl object-contain bg-white p-0.5 border-2 border-white/30 shadow-md flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#E8001D] to-orange-500 flex items-center justify-center text-white font-bold shadow-md flex-shrink-0 text-xs">
                            {{ strtoupper(substr(session('user_session_name', auth()->user()->name), 0, 2)) }}
                        </div>
                    @endif

                    <div class="sidebar-user-details truncate">
                        <p class="text-xs font-bold text-white truncate">{{ session('user_session_name', auth()->user()->name) }}</p>
                        <p class="text-[9px] text-blue-200 uppercase tracking-wider font-semibold truncate">
                            {{ auth()->user()->isAdmin() ? 'Super Admin' : (auth()->user()->ecole ? auth()->user()->ecole->nom_ecole : 'École') }}
                        </p>
                    </div>
                </div>
                
                <button type="button" onclick="openLogoutModal()" title="Se déconnecter" class="p-1.5 text-blue-200 hover:text-white hover:bg-white/15 rounded-xl transition flex-shrink-0 ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- ZONE PRINCIPALE DE CONTENU -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen relative">
        
        <!-- TOPBAR HEADER -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-20 border-b border-slate-100 px-6 sm:px-8 py-3.5 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- 3 Barres Hamburger Button Topbar (Mobile & Desktop) -->
                <button type="button" onclick="toggleSidebar()" 
                        class="p-2.5 rounded-2xl bg-slate-100 hover:bg-[#EEF4FF] text-[#0D1B4B] hover:text-[#1B3A8C] border border-slate-200/80 transition shadow-sm focus:outline-none"
                        title="Ouvrir / Rabattre le menu latéral">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h2 class="text-xl sm:text-2xl font-black text-[#0D1B4B] tracking-tight truncate">
                    @yield('header_title', 'Tableau de bord')
                </h2>
            </div>

            <!-- Actions Header (Notifications + Photo Profil & Nom connecté) -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Date badge -->
                <div class="hidden md:flex items-center space-x-2 text-xs font-semibold text-slate-500 bg-slate-100/90 px-3.5 py-2 rounded-xl border border-slate-200/60">
                    <svg class="w-4 h-4 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="capitalize">{{ now()->locale('fr')->isoFormat('ddd. D MMMM YYYY') }}</span>
                </div>

                <!-- Notifications Button with Interactive Dropdown -->
                <div class="relative" id="notif-dropdown-wrapper">
                    <button id="notif-btn" type="button" 
                            class="w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-slate-50 hover:text-[#1B3A8C] transition shadow-sm relative">
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

                <!-- Avatar Profile + Nom de celui qui est connecté (Image 3) -->
                <div class="flex items-center space-x-3 pl-2 border-l border-slate-200">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-[#0D1B4B] truncate max-w-[140px]">{{ session('user_session_name', auth()->user()->name) }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                            {{ auth()->user()->isAdmin() ? 'Super Admin' : (auth()->user()->ecole ? (auth()->user()->ecole->sigle ?? 'École') : 'École') }}
                        </p>
                    </div>

                    @if(auth()->user()->photo_profil)
                        <img src="{{ asset('uploads/avatars/' . auth()->user()->photo_profil) }}" alt="{{ auth()->user()->name }}"
                             class="w-10 h-10 rounded-2xl object-cover border-2 border-slate-200 shadow-md">
                    @elseif(auth()->user()->ecole && auth()->user()->ecole->logo)
                        <img src="{{ asset('uploads/logos/' . auth()->user()->ecole->logo) }}" alt="{{ auth()->user()->ecole->nom_ecole }}"
                             class="w-10 h-10 rounded-2xl object-contain bg-white p-0.5 border-2 border-slate-200 shadow-md">
                    @else
                        <div class="w-10 h-10 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center font-black text-sm shadow-md">
                            {{ strtoupper(substr(session('user_session_name', auth()->user()->name), 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- MAIN VIEW SLOT -->
        <main class="p-6 sm:p-8 flex-1">
            @yield('dashboard_content')
        </main>
    </div>
</div>

<!-- ======================================================= -->
<!-- CONTENEUR DES NOTIFICATIONS TOAST RAPIDES (5s)          -->
<!-- ======================================================= -->
<div id="toast-container" class="fixed bottom-6 right-6 z-[999999] flex flex-col space-y-3 pointer-events-none max-w-sm w-full px-4"></div>

<!-- ======================================================= -->
<!-- MODAL DE CONFIRMATION DE DÉCONNEXION                   -->
<!-- ======================================================= -->
<div id="modal-logout-confirm" class="hidden fixed inset-0 z-[99999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl border border-slate-100 p-6 text-center transform transition-all">
        <div class="w-14 h-14 rounded-2xl bg-red-50 text-[#E8001D] flex items-center justify-center mx-auto mb-4 border border-red-100">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>
        
        <h3 class="text-lg font-black text-[#0D1B4B]">Confirmation</h3>
        <p class="text-xs text-slate-500 mt-2 mb-6">Êtes-vous sûr de vouloir vous déconnecter de votre session STAGILOG ?</p>
        
        <div class="flex items-center justify-center space-x-3">
            <button type="button" onclick="closeLogoutModal()" 
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                Annuler
            </button>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#E8001D] hover:bg-red-700 text-white font-bold text-xs shadow-lg shadow-red-500/20 transition">
                    Oui, me déconnecter
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/custom-datepicker.js') }}"></script>
<script>
// Toggle sidebar collapse / expand (Desktop & Mobile)
function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('mobile-sidebar-backdrop');
    const isMobile = window.innerWidth < 1024;

    if (isMobile) {
        // Mobile drawer mode
        const isClosed = sidebar.classList.contains('-translate-x-full');
        if (isClosed) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    } else {
        // Desktop collapsed / expanded mode
        const isExpanded = sidebar.classList.contains('sidebar-expanded');
        if (isExpanded) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            localStorage.setItem('stagilog_sidebar_state', 'collapsed');
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            sidebar.classList.add('sidebar-expanded');
            localStorage.setItem('stagilog_sidebar_state', 'expanded');
        }
    }
}

// Initialiser l'état de la sidebar au chargement
document.addEventListener('DOMContentLoaded', function() {
    const savedState = localStorage.getItem('stagilog_sidebar_state');
    const sidebar = document.getElementById('main-sidebar');

    if (window.innerWidth >= 1024 && savedState === 'collapsed' && sidebar) {
        sidebar.classList.remove('sidebar-expanded');
        sidebar.classList.add('sidebar-collapsed');
    }
});

function openLogoutModal() {
    document.getElementById('modal-logout-confirm').classList.remove('hidden');
}

function closeLogoutModal() {
    document.getElementById('modal-logout-confirm').classList.add('hidden');
}

// Système de Toast Rapide 5 secondes
function showQuickToast(title, message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    let iconBg = 'bg-[#1B3A8C] text-white';
    let borderColor = 'border-blue-200';
    if (type === 'success' || type === 'dossier_valide') {
        iconBg = 'bg-emerald-600 text-white';
        borderColor = 'border-emerald-200';
    } else if (type === 'error' || type === 'dossier_refuse') {
        iconBg = 'bg-[#E8001D] text-white';
        borderColor = 'border-red-200';
    }

    const toast = document.createElement('div');
    toast.className = `stagilog-toast pointer-events-auto bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-2xl border ${borderColor} flex items-start space-x-3 transform transition-all duration-300`;
    toast.innerHTML = `
        <div class="w-8 h-8 rounded-xl ${iconBg} flex items-center justify-center flex-shrink-0 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h5 class="text-xs font-black text-[#0D1B4B]">${title}</h5>
            <p class="text-[11px] text-slate-600 font-medium line-clamp-2 mt-0.5">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 flex-shrink-0 p-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 5000);
}

// Gestionnaire interactif de notifications
document.addEventListener('DOMContentLoaded', function() {
    const notifBtn = document.getElementById('notif-btn');
    const notifPanel = document.getElementById('notif-panel');
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');

    let previousUnreadCount = null;

    function loadNotifications(isInitial = false) {
        fetch('{{ route("notifications.get") }}')
            .then(res => res.json())
            .then(data => {
                if (data.unread_count > 0) {
                    notifBadge.innerText = data.unread_count > 9 ? '9+' : data.unread_count;
                    notifBadge.classList.remove('hidden');
                } else {
                    notifBadge.classList.add('hidden');
                }

                if (previousUnreadCount !== null && data.unread_count > previousUnreadCount && data.notifications.length > 0) {
                    const latest = data.notifications[0];
                    showQuickToast(latest.titre, latest.message, latest.type);
                }
                previousUnreadCount = data.unread_count;

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

    loadNotifications(true);
    setInterval(() => loadNotifications(), 30000);

    if (notifBtn) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifPanel.classList.toggle('hidden');
            if (!notifPanel.classList.contains('hidden')) {
                loadNotifications();
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (notifPanel && !document.getElementById('notif-dropdown-wrapper')?.contains(e.target)) {
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
@endpush
@endsection
