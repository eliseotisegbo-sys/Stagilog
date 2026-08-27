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
        /* Flag Wave & Silk Shimmer Ambient Animation */
        @keyframes flagWaveLight {
            0% {
                background-position: 0% 50%, 0% 0%, 0% 0%;
            }
            50% {
                background-position: 100% 50%, 50% 100%, 100% 50%;
            }
            100% {
                background-position: 0% 50%, 0% 0%, 0% 0%;
            }
        }

        @keyframes flagFlutter {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-8px) rotate(-0.5deg) scale(1.01);
            }
            50% {
                transform: translateY(4px) rotate(0.8deg) scale(0.995);
            }
            75% {
                transform: translateY(-5px) rotate(-0.3deg) scale(1.005);
            }
        }

        @keyframes waveGlow {
            0%, 100% {
                opacity: 0.45;
                filter: blur(60px);
            }
            50% {
                opacity: 0.75;
                filter: blur(80px);
            }
        }

        /* Ambient Flag Wave Background */
        .flag-wave-ambient {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
            background: 
                radial-gradient(ellipse 80% 50% at 20% 0%, rgba(27, 58, 140, 0.09) 0%, transparent 70%),
                radial-gradient(ellipse 60% 60% at 90% 20%, rgba(232, 0, 29, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse 90% 70% at 50% 100%, rgba(59, 130, 246, 0.08) 0%, transparent 70%),
                #F0F4FF;
        }

        .flag-wave-ambient::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: 
                repeating-linear-gradient(
                    -45deg,
                    rgba(255, 255, 255, 0.35) 0px,
                    rgba(240, 244, 255, 0.1) 60px,
                    rgba(27, 58, 140, 0.03) 120px,
                    rgba(255, 255, 255, 0.4) 180px,
                    rgba(232, 0, 29, 0.02) 240px,
                    rgba(255, 255, 255, 0.2) 300px
                );
            background-size: 400% 400%;
            animation: flagWaveLight 16s ease-in-out infinite, flagFlutter 12s ease-in-out infinite;
            filter: contrast(110%) brightness(102%);
            opacity: 0.85;
            mix-blend-mode: multiply;
        }

        .flag-wave-ambient::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.6) 0%, transparent 50%);
            animation: waveGlow 8s ease-in-out infinite;
        }

        /* Sidebar transition styles */
        .sidebar-expanded {
            width: 18rem; /* 288px */
        }
        .sidebar-collapsed {
            width: 5rem; /* 80px */
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-heading,
        .sidebar-collapsed .sidebar-user-details {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .sidebar-collapsed .sidebar-user-box {
            justify-content: center;
            padding: 0.5rem;
        }
        .sidebar-collapsed .sidebar-user-box button {
            margin-left: 0;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased bg-[#F0F4FF] relative selection:bg-[#1B3A8C] selection:text-white">
    <!-- Drapeau flottant animé en arrière-plan -->
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
