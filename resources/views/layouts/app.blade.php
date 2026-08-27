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
                linear-gradient(135deg, rgba(240, 244, 255, 0.92) 0%, rgba(255, 255, 255, 0.88) 45%, rgba(224, 235, 255, 0.90) 100%),
                url('/images/d203f8a59f9618c83b358090aff71451.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Modern Flatpickr Calendar Styling */
        .flatpickr-calendar {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border-radius: 24px !important;
            box-shadow: 0 24px 64px -10px rgba(13, 27, 75, 0.22), 0 0 0 1px rgba(27, 58, 140, 0.08) !important;
            border: 1px solid #E2E8F0 !important;
            overflow: hidden !important;
            padding: 16px !important;
            width: 324px !important;
            background: #FFFFFF !important;
        }
        .flatpickr-months {
            background: #0D1B4B !important;
            border-radius: 16px 16px 12px 12px !important;
            padding: 10px 8px !important;
            margin-bottom: 12px !important;
            display: flex !important;
            align-items: center !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
            flex: 1 !important;
        }
        .flatpickr-current-month {
            font-weight: 700 !important;
            font-size: 14px !important;
            color: #FFFFFF !important;
            padding-top: 2px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            width: 100% !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
            color: #FFFFFF !important;
            background: rgba(255,255,255,0.12) !important;
            border-radius: 8px !important;
            padding: 3px 8px !important;
            border: 1px solid rgba(255,255,255,0.15) !important;
            cursor: pointer !important;
            margin-right: 2px !important;
        }
        .flatpickr-current-month input.cur-year {
            font-weight: 800 !important;
            color: #93C5FD !important;
            background: rgba(255,255,255,0.08) !important;
            border-radius: 6px !important;
            padding: 2px 4px !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            width: 54px !important;
            text-align: center !important;
        }
        /* Year scroll arrows — always visible */
        .flatpickr-current-month .numInputWrapper {
            display: inline-flex !important;
            align-items: center !important;
            position: relative !important;
            margin-left: 2px !important;
        }
        .flatpickr-current-month .numInputWrapper span {
            opacity: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 18px !important;
            height: 18px !important;
            border-radius: 5px !important;
            background: rgba(255,255,255,0.15) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            right: -22px !important;
            position: absolute !important;
            cursor: pointer !important;
            transition: background 0.15s !important;
        }
        .flatpickr-current-month .numInputWrapper span:hover {
            background: rgba(255,255,255,0.30) !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowUp {
            top: -10px !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowDown {
            top: 10px !important;
        }
        .flatpickr-current-month .numInputWrapper span::after {
            border-color: #FFFFFF transparent !important;
        }
        .flatpickr-months .flatpickr-prev-month, 
        .flatpickr-months .flatpickr-next-month {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
            padding: 7px !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
            flex-shrink: 0 !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover, 
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.18) !important;
        }
        .flatpickr-months .flatpickr-prev-month svg, 
        .flatpickr-months .flatpickr-next-month svg {
            fill: #FFFFFF !important;
        }
        .flatpickr-weekdays {
            margin-top: 6px !important;
            margin-bottom: 4px !important;
        }
        span.flatpickr-weekday {
            color: #64748B !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        .flatpickr-day {
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            color: #1E293B !important;
            height: 36px !important;
            line-height: 36px !important;
            margin: 2px !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-day:hover {
            background: #EEF4FF !important;
            color: #1B3A8C !important;
            border-color: #BFDBFE !important;
        }
        .flatpickr-day.today {
            border-color: #E8001D !important;
            color: #E8001D !important;
            font-weight: 800 !important;
        }
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange {
            background: #1B3A8C !important;
            border-color: #1B3A8C !important;
            color: #FFFFFF !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 12px rgba(27, 58, 140, 0.35) !important;
        }
        .flatpickr-day.inRange {
            background: #EEF4FF !important;
            border-color: #EEF4FF !important;
            color: #1B3A8C !important;
        }
        .flatpickr-day.flatpickr-disabled, 
        .flatpickr-day.flatpickr-disabled:hover {
            color: #CBD5E1 !important;
        }

        /* Dark Mode Tokens */
        html.dark body {
            background-color: #0D1B4B !important;
            color: #E2E8F0 !important;
        }
        html.dark .flag-wave-ambient {
            background-image: 
                linear-gradient(135deg, rgba(13,27,75,0.94) 0%, rgba(4,9,30,0.91) 45%, rgba(18,37,100,0.93) 100%),
                url('/images/d203f8a59f9618c83b358090aff71451.jpg') !important;
        }
        html.dark .bg-white { background-color: #1A2A5E !important; color: #E2E8F0 !important; }
        html.dark .bg-slate-50, html.dark .bg-slate-50\/80, html.dark .bg-[#F0F4FF] {
            background-color: #142050 !important;
        }
        html.dark .text-slate-800, html.dark .text-[#0D1B4B] { color: #E2E8F0 !important; }
        html.dark .text-slate-600 { color: #A0AEC0 !important; }
        html.dark .text-slate-500 { color: #8B95B0 !important; }
        html.dark .text-slate-400 { color: #6B7A9A !important; }
        html.dark .border-slate-100, html.dark .border-slate-200 { border-color: rgba(255,255,255,0.08) !important; }
        html.dark .shadow-card { box-shadow: 0 4px 20px -2px rgba(0,0,0,0.3) !important; }

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
    <script>
        // Dark mode initialization — runs before paint to avoid flicker
        (function() {
            try {
                const theme = localStorage.getItem('stagilog_theme');
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch(e) {}
        })();
    </script>
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
    <script>
    // Auto-dismiss flash notifications after 5 seconds
    (function() {
        var toastContainer = document.querySelector('.fixed.top-5.right-5');
        if (toastContainer) {
            var toasts = toastContainer.children;
            Array.from(toasts).forEach(function(toast) {
                setTimeout(function() {
                    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-12px)';
                    setTimeout(function() { toast.remove(); }, 500);
                }, 5000);
            });
        }
    })();
    </script>
</body>
</html>
