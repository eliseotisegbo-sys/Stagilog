<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F0F4FF]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'STAGILOG - Technology Forever Group SARL')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        tfg: {
                            navy: '#1B3A8C',
                            dark: '#0D1B4B',
                            red: '#E8001D',
                            redhover: '#C70019',
                            bluehover: '#142B6B',
                            light: '#F0F4FF',
                            card: '#FFFFFF',
                            muted: '#6B7AA1',
                            border: '#E2E8F0',
                            accent: '#3B82F6',
                            success: '#10B981',
                            warning: '#F59E0B',
                        }
                    },
                    boxShadow: {
                        'soft': '0 10px 30px 0 rgba(27, 58, 140, 0.06)',
                        'card': '0 4px 20px -2px rgba(27, 58, 140, 0.05)',
                        'hover': '0 20px 35px -5px rgba(27, 58, 140, 0.12)',
                    }
                }
            }
        }
    </script>
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Flatpickr Datepicker CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        html {
            scroll-behavior: smooth;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F0F4FF;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1B3A8C;
        }
        /* Ambient Image Background with Light Gradient Overlay */
        .flag-wave-ambient {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: 
                linear-gradient(135deg, rgba(255, 255, 255, 0.85) 0%, rgba(240, 244, 255, 0.92) 50%, rgba(27, 58, 140, 0.05) 100%),
                url('/images/d203f8a59f9618c83b358090aff71451.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-blend-mode: overlay;
        }

        /* Sidebar transition styles */
        .sidebar-expanded {
            width: 18rem; /* 288px */
        }
        .sidebar-collapsed {
            width: 5.25rem; /* 84px */
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-heading,
        .sidebar-collapsed .sidebar-user-details {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-brand {
            justify-content: center !important;
            padding: 0.5rem 0 !important;
        }
        .sidebar-collapsed .sidebar-link-item {
            justify-content: center !important;
            width: 3.25rem !important;
            height: 3.25rem !important;
            margin: 0.25rem auto !important;
            padding: 0 !important;
            border-radius: 1.125rem !important;
        }
        .sidebar-collapsed .sidebar-link-item svg {
            margin: 0 !important;
        }
        .sidebar-collapsed .sidebar-user-box {
            justify-content: center !important;
            padding: 0.5rem !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        .sidebar-collapsed .sidebar-user-box button {
            margin: 0 !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased bg-[#F0F4FF] relative selection:bg-[#1B3A8C] selection:text-white">
    <!-- Arrière-plan image avec dégradé -->
    <div class="flag-wave-ambient" aria-hidden="true"></div>
    
    <!-- Notifications Flash Toasts -->
    @if(session('success') || session('error') || session('status') || $errors->any())
    <div class="fixed top-5 right-5 z-50 flex flex-col space-y-3 max-w-md w-full px-4">
        @if(session('success'))
        <div class="flex items-center p-4 bg-white border-l-4 border-emerald-500 rounded-xl shadow-hover transition-all duration-300 transform translate-y-0">
            <div class="flex-shrink-0 text-emerald-500 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 text-sm font-medium text-slate-800">
                {{ session('success') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="flex items-center p-4 bg-white border-l-4 border-[#E8001D] rounded-xl shadow-hover transition-all duration-300">
            <div class="flex-shrink-0 text-[#E8001D] mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 text-sm font-medium text-slate-800">
                {{ session('error') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if(session('status'))
        <div class="flex items-center p-4 bg-white border-l-4 border-blue-500 rounded-xl shadow-hover transition-all duration-300">
            <div class="flex-shrink-0 text-blue-500 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 text-sm font-medium text-slate-800">
                {{ session('status') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-start p-4 bg-white border-l-4 border-[#E8001D] rounded-xl shadow-hover transition-all duration-300">
            <div class="flex-shrink-0 text-[#E8001D] mr-3 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1 text-sm text-slate-800">
                <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-slate-600">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    @yield('content')
    
    @stack('scripts')
</body>
</html>
