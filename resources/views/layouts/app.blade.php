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
        /* Custom scrollbar - PLUS VISIBLE ET STYLÉE */
        ::-webkit-scrollbar {
            width: 10px !important;
            height: 10px !important;
        }
        ::-webkit-scrollbar-track {
            background: linear-gradient(135deg, #F0F4FF 0%, #E8EFFD 100%) !important;
            border-radius: 10px !important;
            border: 1px solid #E2E8F0 !important;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #94A3B8 0%, #64748B 100%) !important;
            border-radius: 10px !important;
            border: 2px solid #F0F4FF !important;
            transition: all 0.3s ease !important;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            border-color: #DBEAFE !important;
            transform: scale(1.1) !important;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.4) !important;
        }
        ::-webkit-scrollbar-thumb:active {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
        }
        /* Scrollbar en mode sombre */
        html.dark ::-webkit-scrollbar-track {
            background: linear-gradient(135deg, rgba(15, 29, 58, 0.9) 0%, rgba(11, 23, 52, 1) 100%) !important;
            border: 1px solid rgba(148, 163, 184, 0.3) !important;
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
            border: 2px solid rgba(15, 29, 58, 0.8) !important;
        }
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 100%) !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
            box-shadow: 0 0 12px rgba(96, 165, 250, 0.5) !important;
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

        /* ======================================================= */
        /* 1. CALENDRIER THEME 1 : PÉRIODES & DATES DE STAGE */
        /*    Sélection de plages - FRANÇAIS + Boutons ascenseur visibles */
        /* ======================================================= */
        .flatpickr-calendar,
        .flatpickr-calendar.flatpickr-range-theme {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border-radius: 24px !important;
            box-shadow: 0 24px 56px -12px rgba(15, 23, 42, 0.2), 0 0 0 1px rgba(15, 23, 42, 0.08) !important;
            border: 1px solid #E2E8F0 !important;
            padding: 24px !important;
            width: auto !important;
            min-width: 340px !important;
            background: #FFFFFF !important;
            color: #1E293B !important;
        }
        .flatpickr-calendar .flatpickr-months {
            background: transparent !important;
            padding: 0 6px 20px 6px !important;
            margin-bottom: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            position: relative !important;
            gap: 12px !important;
        }
        .flatpickr-calendar .flatpickr-months .flatpickr-month {
            color: #1E293B !important;
            fill: #1E293B !important;
            flex: 1 !important;
            height: auto !important;
        }
        .flatpickr-calendar .flatpickr-current-month {
            font-weight: 800 !important;
            font-size: 18px !important;
            color: #1E293B !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            width: 100% !important;
            position: static !important;
            left: 0 !important;
            transform: none !important;
        }
        .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 800 !important;
            color: #1E293B !important;
            background: #FFFFFF !important;
            border-radius: 12px !important;
            padding: 6px 12px !important;
            border: 1px solid #CBD5E1 !important;
            cursor: pointer !important;
            font-size: 16px !important;
            appearance: auto !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
            outline: none !important;
        }
        .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            border-color: #94A3B8 !important;
            background: #F8FAFC !important;
        }
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper {
            display: inline-flex !important;
            align-items: center !important;
            position: relative !important;
            background: #FFFFFF !important;
            border: 1px solid #CBD5E1 !important;
            border-radius: 12px !important;
            padding: 3px 22px 3px 8px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
        }
        .flatpickr-calendar .flatpickr-current-month input.cur-year {
            font-weight: 800 !important;
            color: #1E293B !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            width: 48px !important;
            font-size: 15px !important;
            outline: none !important;
            text-align: center !important;
            -moz-appearance: textfield !important;
        }
        .flatpickr-calendar .flatpickr-current-month input.cur-year::-webkit-outer-spin-button,
        .flatpickr-calendar .flatpickr-current-month input.cur-year::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        /* BOUTONS ASCENSEUR ANNÉE - PLACÉS PROPREMENT SANS CHEVAUCHEMENT */
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowUp,
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowDown {
            position: absolute !important;
            right: 3px !important;
            width: 16px !important;
            height: 12px !important;
            border: 1px solid #94A3B8 !important;
            background: #F1F5F9 !important;
            border-radius: 4px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowUp {
            top: 3px !important;
        }
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowDown {
            bottom: 3px !important;
        }
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowUp:hover:after,
        .flatpickr-calendar .flatpickr-current-month .numInputWrapper .arrowDown:hover:after {
            border-color: #FFFFFF !important;
        }
        .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
        .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
            position: static !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 12px !important;
            border: 1px solid #CBD5E1 !important;
            background: #FFFFFF !important;
            color: #475569 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
        }
        .flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover, 
        .flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover {
            background: #F1F5F9 !important;
            border-color: #94A3B8 !important;
            color: #1E293B !important;
            transform: scale(1.08) !important;
        }
        .flatpickr-calendar .flatpickr-months .flatpickr-prev-month svg, 
        .flatpickr-calendar .flatpickr-months .flatpickr-next-month svg {
            width: 14px !important;
            height: 14px !important;
            fill: currentColor !important;
        }
        .flatpickr-calendar .flatpickr-weekdays {
            margin-bottom: 12px !important;
            padding: 0 6px !important;
        }
        .flatpickr-calendar span.flatpickr-weekday {
            color: #94A3B8 !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.6px !important;
        }
        .flatpickr-calendar .flatpickr-days {
            padding: 0 6px !important;
        }
        .flatpickr-calendar .flatpickr-day {
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            color: #475569 !important;
            height: 42px !important;
            line-height: 42px !important;
            max-width: 42px !important;
            margin: 2px 0 !important;
            border: 1px solid transparent !important;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .flatpickr-calendar .flatpickr-day:hover {
            background: #EEF2FF !important;
            color: #312E81 !important;
            border-color: transparent !important;
            transform: scale(1.08) !important;
        }
        .flatpickr-calendar .flatpickr-day.today {
            border: 2.5px solid #6366F1 !important;
            color: #6366F1 !important;
            font-weight: 900 !important;
            background: transparent !important;
        }
        .flatpickr-calendar .flatpickr-day.selected, 
        .flatpickr-calendar .flatpickr-day.startRange, 
        .flatpickr-calendar .flatpickr-day.endRange {
            background: linear-gradient(135deg, #6366F1 0%, #818CF8 100%) !important;
            border-color: #6366F1 !important;
            color: #FFFFFF !important;
            font-weight: 900 !important;
            border-radius: 14px !important;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45) !important;
            transform: scale(1.08) !important;
        }
        .flatpickr-calendar .flatpickr-day.inRange {
            background: #E0E7FF !important;
            border-color: #C7D2FE !important;
            color: #4338CA !important;
            font-weight: 600 !important;
            border-radius: 0 !important;
            box-shadow: -5px 0 0 #E0E7FF, 5px 0 0 #E0E7FF !important;
        }
        .flatpickr-calendar .flatpickr-day.flatpickr-disabled, 
        .flatpickr-calendar .flatpickr-day.flatpickr-disabled:hover {
            color: #CBD5E1 !important;
            opacity: 0.35 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }
        .flatpickr-calendar .flatpickr-day.prevMonthDay,
        .flatpickr-calendar .flatpickr-day.nextMonthDay {
            color: #CBD5E1 !important;
            opacity: 0.5 !important;
        }

        /* ======================================================= */
        /* 2. CALENDRIER THEME 2 : DATE DE NAISSANCE ÉTUDIANT (IMAGE 2) */
        /*    Sélection simple avec mois et année séparés                  */
        /*    OPTIMISÉ pour sélection rapide de dates anciennes (1970-2010)*/
        /* ======================================================= */
        .flatpickr-calendar.flatpickr-birthdate-theme {
            border-radius: 20px !important;
            padding: 18px !important;
            width: 340px !important;
            border: 1px solid #CBD5E1 !important;
            box-shadow: 0 20px 48px -10px rgba(15, 23, 42, 0.18) !important;
            background: #FAFBFF !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months {
            padding: 0 6px 16px 6px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 12px !important;
        }
        /* Flèche précédente plus visible */
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-prev-month {
            width: 36px !important;
            height: 36px !important;
            border: 1.5px solid #CBD5E1 !important;
            background: #FFFFFF !important;
            color: #475569 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-prev-month:hover {
            background: #F1F5F9 !important;
            border-color: #94A3B8 !important;
            transform: scale(1.1) !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-prev-month svg {
            width: 14px !important;
            height: 14px !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-next-month {
            display: none !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            justify-content: flex-start !important;
            flex: 1 !important;
        }
        /* Dropdown mois plus grand et stylé */
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #FFFFFF !important;
            border: 1.5px solid #CBD5E1 !important;
            border-radius: 12px !important;
            padding: 6px 14px !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            color: #1E293B !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
            appearance: auto !important;
            cursor: pointer !important;
            min-width: 140px !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            border-color: #94A3B8 !important;
            background: #F8FAFC !important;
        }
        /* Input année PLUS GRAND avec scrollbar personnalisée */
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper {
            background: #FFFFFF !important;
            border: 1.5px solid #CBD5E1 !important;
            border-radius: 12px !important;
            padding: 4px 10px !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
            position: relative !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month input.cur-year {
            font-weight: 800 !important;
            font-size: 15px !important;
            color: #1E293B !important;
            width: 70px !important;
            padding: 2px 4px !important;
            border: none !important;
            background: transparent !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month input.cur-year:hover,
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month input.cur-year:focus {
            background: transparent !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper:hover {
            border-color: #94A3B8 !important;
            background: #F8FAFC !important;
        }
        /* Boutons ascenseur (up/down) BEAUCOUP PLUS VISIBLES */
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp,
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown {
            width: 18px !important;
            height: 16px !important;
            border: 1px solid #94A3B8 !important;
            background: linear-gradient(135deg, #FFFFFF 0%, #F1F5F9 100%) !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp:hover,
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown:hover {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            border-color: #3B82F6 !important;
            transform: scale(1.15) !important;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4) !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp:after,
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown:after {
            border-color: #475569 !important;
            border-width: 0 2px 2px 0 !important;
            padding: 2.5px !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp:hover:after,
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown:hover:after {
            border-color: #FFFFFF !important;
            border-width: 0 2.5px 2.5px 0 !important;
        }
        /* Grid des jours */
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day {
            border: 1px solid #E2E8F0 !important;
            border-radius: 0 !important;
            margin: 0 !important;
            height: 42px !important;
            line-height: 42px !important;
            font-weight: 600 !important;
            color: #475569 !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day.selected {
            background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%) !important;
            color: #FFFFFF !important;
            border-color: #0284C7 !important;
            font-weight: 900 !important;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35) !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day:hover {
            background: #DBEAFE !important;
            color: #0369A1 !important;
            border-color: #93C5FD !important;
            transform: scale(1.05) !important;
        }
        .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day.today {
            border: 2px solid #0284C7 !important;
            color: #0284C7 !important;
            font-weight: 800 !important;
        }

        /* ======================================================= */
        /* AMÉLIORATION GLOBALE DE LA LISIBILITÉ DES TABLEAUX      */
        /* ======================================================= */
        table {
            font-size: 0.75rem !important; /* 12px - taille normale */
        }
        thead th {
            font-size: 0.6875rem !important; /* 11px */
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            padding-top: 0.875rem !important;
            padding-bottom: 0.875rem !important;
        }
        tbody td {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            line-height: 1.4 !important;
        }

        /* ======================================================= */
        /* 3. PALETTE MODE SOMBRE OPTIMISÉE (WCAG AA+ CONFORME)    */
        /*    Ratio contraste minimum : 4.5:1 pour texte normal   */
        /*                            : 3:1 pour gros titres       */
        /* ======================================================= */
        html.dark {
            color-scheme: dark;
        }
        html.dark body {
            background-color: #0A1628 !important;
            color: #F1F5F9 !important;
        }
        html.dark .flag-wave-ambient {
            background-image: 
                linear-gradient(135deg, rgba(10,22,40,0.97) 0%, rgba(7,15,32,0.95) 45%, rgba(13,25,52,0.96) 100%),
                url('/images/d203f8a59f9618c83b358090aff71451.jpg') !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* HEADER & TOPBAR — Transition harmonieuse                */
        /* ─────────────────────────────────────────────────────── */
        html.dark header {
            background: linear-gradient(135deg, rgba(20, 36, 74, 0.96) 0%, rgba(15, 29, 62, 0.98) 100%) !important;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2) !important;
            backdrop-filter: blur(12px) !important;
        }
        html.dark header h2,
        html.dark header h1 {
            color: #FFFFFF !important;
            font-weight: 900 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }
        html.dark header .text-slate-500,
        html.dark header .text-slate-400 {
            color: #CBD5E1 !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* CARTES PRINCIPALES — Distinction nette du fond           */
        /* ─────────────────────────────────────────────────────── */
        html.dark .bg-white { 
            background: linear-gradient(135deg, rgba(26, 40, 71, 0.95) 0%, rgba(20, 35, 63, 0.98) 100%) !important; 
            color: #F1F5F9 !important; 
            border: 1px solid rgba(148, 163, 184, 0.25) !important;
        }
        html.dark .shadow-card { 
            box-shadow: 0 12px 48px -8px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(148, 163, 184, 0.15) !important; 
        }
        html.dark .shadow-hover:hover {
            box-shadow: 0 18px 60px -10px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(148, 163, 184, 0.22) !important;
        }
        html.dark .rounded-3xl {
            box-shadow: 0 8px 32px -4px rgba(0, 0, 0, 0.5) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* CONTENEURS SECONDAIRES — Blocs internes aux cartes      */
        /* ─────────────────────────────────────────────────────── */
        html.dark .bg-slate-50, 
        html.dark .bg-slate-50\/80, 
        html.dark .bg-slate-50\/70,
        html.dark .bg-slate-50\/40,
        html.dark .bg-[#F0F4FF],
        html.dark .bg-[#EEF4FF],
        html.dark .bg-slate-100,
        html.dark .bg-slate-100\/90,
        html.dark .bg-gray-50 {
            background: rgba(15, 29, 58, 0.85) !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }
        html.dark .bg-slate-200 {
            background-color: rgba(51, 65, 85, 0.6) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* TYPOGRAPHIE — Contraste élevé (WCAG AA+)                 */
        /* ─────────────────────────────────────────────────────── */
        
        /* Titres principaux et noms dans l'en-tête profil */
        html.dark h1,
        html.dark h2,
        html.dark h3,
        html.dark h4,
        html.dark h5,
        html.dark .text-[#0D1B4B], 
        html.dark .text-slate-900,
        html.dark .text-slate-800 { 
            color: #F8FAFC !important; 
            font-weight: 800 !important;
        }
        
        /* Titres de sections en majuscules — Lisibilité maximale */
        html.dark h3.uppercase,
        html.dark h4.uppercase,
        html.dark .uppercase.tracking-wider,
        html.dark .font-extrabold.uppercase {
            color: #FFFFFF !important;
            font-weight: 900 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4) !important;
        }

        /* Sous-titres et textes semi-importants */
        html.dark .text-slate-700 { 
            color: #E2E8F0 !important; 
        }
        html.dark .text-slate-600 { 
            color: #CBD5E1 !important; 
        }
        html.dark .text-slate-500 { 
            color: #A1B0C8 !important; 
        }
        html.dark .text-slate-400 { 
            color: #8A9AB3 !important; 
        }
        html.dark .text-slate-300 {
            color: #CBD5E1 !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* LIENS & ACCENTS BLEUS                                    */
        /* ─────────────────────────────────────────────────────── */
        html.dark .text-[#1B3A8C],
        html.dark .text-blue-600,
        html.dark .text-blue-700 { 
            color: #60A5FA !important; 
        }
        html.dark a.text-[#1B3A8C]:hover,
        html.dark .hover\:text-\[\#142B6B\]:hover,
        html.dark .hover\:text-\[\#1B3A8C\]:hover,
        html.dark a:hover {
            color: #93C5FD !important;
        }
        html.dark .bg-[#1B3A8C],
        html.dark .bg-blue-600 {
            background-color: #2563EB !important;
        }
        html.dark .hover\:bg-\[\#142B6B\]:hover,
        html.dark .bg-[#1B3A8C]:hover {
            background-color: #1D4ED8 !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* FORMULAIRES — Labels, Inputs, Focus                      */
        /* ─────────────────────────────────────────────────────── */
        html.dark label {
            color: #F1F5F9 !important;
            font-weight: 800 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        }
        html.dark input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="file"]),
        html.dark select,
        html.dark textarea {
            background: rgba(15, 29, 58, 0.9) !important;
            color: #F8FAFC !important;
            border: 1.5px solid rgba(148, 163, 184, 0.3) !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #94A3B8 !important;
        }
        html.dark input:focus,
        html.dark select:focus,
        html.dark textarea:focus {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: #60A5FA !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.25), 0 4px 12px rgba(0, 0, 0, 0.3) !important;
            outline: none !important;
        }
        html.dark input[readonly],
        html.dark input[readonly]:focus {
            background: rgba(15, 29, 58, 0.6) !important;
            cursor: pointer !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* BADGES & STATUTS                                         */
        /* ─────────────────────────────────────────────────────── */
        html.dark .bg-blue-50,
        html.dark .bg-blue-100 {
            background-color: rgba(59, 130, 246, 0.22) !important;
            color: #93C5FD !important;
            border: 1px solid rgba(59, 130, 246, 0.35) !important;
        }
        html.dark .bg-emerald-50,
        html.dark .bg-emerald-100,
        html.dark .bg-green-50 {
            background-color: rgba(16, 185, 129, 0.22) !important;
            color: #6EE7B7 !important;
            border: 1px solid rgba(16, 185, 129, 0.35) !important;
        }
        html.dark .bg-amber-50,
        html.dark .bg-amber-100,
        html.dark .bg-yellow-50 {
            background-color: rgba(245, 158, 11, 0.22) !important;
            color: #FCD34D !important;
            border: 1px solid rgba(245, 158, 11, 0.35) !important;
        }
        html.dark .bg-red-50,
        html.dark .bg-red-100 {
            background-color: rgba(239, 68, 68, 0.22) !important;
            color: #FCA5A5 !important;
            border: 1px solid rgba(239, 68, 68, 0.35) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* TABLEAUX — Augmentation taille texte + contraste         */
        /* ─────────────────────────────────────────────────────── */
        html.dark table {
            font-size: 0.9375rem !important; /* 15px au lieu de 12px */
        }
        html.dark thead {
            background-color: #0D1932 !important;
        }
        html.dark thead th {
            color: #E2E8F0 !important;
            font-weight: 800 !important;
            font-size: 0.875rem !important; /* 14px au lieu de 13px */
            letter-spacing: 0.05em !important;
            border-bottom: 1px solid rgba(148, 163, 184, 0.15) !important;
            padding-top: 1.125rem !important;
            padding-bottom: 1.125rem !important;
        }
        html.dark tbody td {
            color: #CBD5E1 !important;
            font-size: 0.9375rem !important; /* 15px */
            padding-top: 1.125rem !important;
            padding-bottom: 1.125rem !important;
            line-height: 1.6 !important;
        }
        html.dark tbody tr:hover,
        html.dark tr.hover\:bg-slate-50\/70:hover,
        html.dark tr.hover\:bg-slate-50:hover {
            background-color: rgba(255, 255, 255, 0.06) !important;
        }
        html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark .border-slate-100 {
            border-color: rgba(148, 163, 184, 0.12) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* MODALES                                                  */
        /* ─────────────────────────────────────────────────────── */
        html.dark .bg-white.rounded-3xl,
        html.dark #add-user-modal > div,
        html.dark #password-modal > div,
        html.dark #modal-logout-confirm > div,
        html.dark #modal-refus > div,
        html.dark #modal-modifier-periode > div {
            background-color: #1A2847 !important;
            border: 1px solid rgba(148, 163, 184, 0.2) !important;
        }

        /* Backdrop des modales */
        html.dark .bg-slate-900\/60,
        html.dark [class*="bg-slate-900"] {
            background-color: rgba(0, 0, 0, 0.75) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* FLATPICKR CALENDRIER EN MODE SOMBRE                     */
        /* ─────────────────────────────────────────────────────── */
        html.dark .flatpickr-calendar {
            background: linear-gradient(135deg, rgba(26, 40, 71, 0.98) 0%, rgba(20, 35, 63, 1) 100%) !important;
            border: 1px solid rgba(148, 163, 184, 0.25) !important;
            box-shadow: 0 28px 72px -12px rgba(0, 0, 0, 0.85), 0 0 0 1px rgba(148, 163, 184, 0.2) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month {
            color: #FFFFFF !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #FFFFFF !important;
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: rgba(148, 163, 184, 0.5) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month input.cur-year {
            color: #FFFFFF !important;
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month input.cur-year:hover,
        html.dark .flatpickr-calendar .flatpickr-current-month input.cur-year:focus {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: rgba(148, 163, 184, 0.5) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
            color: #F1F5F9 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover, 
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: #60A5FA !important;
            color: #FFFFFF !important;
        }
        html.dark .flatpickr-calendar span.flatpickr-weekday {
            color: #CBD5E1 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day {
            color: #F1F5F9 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day:hover {
            background: rgba(99, 102, 241, 0.25) !important;
            color: #FFFFFF !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.today {
            border-color: #60A5FA !important;
            color: #60A5FA !important;
            font-weight: 900 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.selected,
        html.dark .flatpickr-calendar .flatpickr-day.startRange,
        html.dark .flatpickr-calendar .flatpickr-day.endRange {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            border-color: #3B82F6 !important;
            color: #FFFFFF !important;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.inRange {
            background: rgba(99, 102, 241, 0.3) !important;
            color: #E0E7FF !important;
            box-shadow: -5px 0 0 rgba(99, 102, 241, 0.3), 5px 0 0 rgba(99, 102, 241, 0.3) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.prevMonthDay,
        html.dark .flatpickr-calendar .flatpickr-day.nextMonthDay {
            color: #64748B !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.flatpickr-disabled {
            color: #475569 !important;
        }

        /* Calendrier date de naissance en mode sombre */
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme {
            background: linear-gradient(135deg, rgba(26, 40, 71, 0.98) 0%, rgba(20, 35, 63, 1) 100%) !important;
            border-color: rgba(148, 163, 184, 0.3) !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-prev-month {
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.4) !important;
            color: #F1F5F9 !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-months .flatpickr-prev-month:hover {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: #60A5FA !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.4) !important;
            color: #F8FAFC !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: #60A5FA !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper {
            background: rgba(15, 29, 58, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.4) !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper:hover {
            background: rgba(20, 36, 74, 0.95) !important;
            border-color: #60A5FA !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month input.cur-year {
            color: #F8FAFC !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp,
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown {
            background: linear-gradient(135deg, rgba(15, 29, 58, 0.95) 0%, rgba(11, 23, 52, 1) 100%) !important;
            border-color: rgba(148, 163, 184, 0.5) !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowUp:hover,
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-current-month .numInputWrapper .arrowDown:hover {
            background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 100%) !important;
            border-color: #60A5FA !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day {
            color: #E2E8F0 !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day:hover {
            background: rgba(59, 130, 246, 0.25) !important;
            color: #FFFFFF !important;
            border-color: rgba(96, 165, 250, 0.5) !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day.selected {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            border-color: #3B82F6 !important;
        }
        html.dark .flatpickr-calendar.flatpickr-birthdate-theme .flatpickr-day.today {
            border-color: #60A5FA !important;
            color: #60A5FA !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* APEXCHARTS EN MODE SOMBRE                                */
        /* ─────────────────────────────────────────────────────── */
        html.dark .apexcharts-text tspan,
        html.dark .apexcharts-legend-text {
            fill: #CBD5E1 !important;
            color: #CBD5E1 !important;
        }
        html.dark .apexcharts-gridline {
            stroke: rgba(148, 163, 184, 0.12) !important;
        }

        /* ─────────────────────────────────────────────────────── */
        /* SIDEBAR EN MODE SOMBRE                                   */
        /* ─────────────────────────────────────────────────────── */
        html.dark aside,
        html.dark .sidebar {
            background: linear-gradient(180deg, rgba(15, 29, 62, 0.98) 0%, rgba(11, 23, 52, 1) 100%) !important;
            border-right: 1px solid rgba(148, 163, 184, 0.2) !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4) !important;
        }
        html.dark .sidebar a,
        html.dark .sidebar-link-item {
            color: #E2E8F0 !important;
        }
        html.dark .sidebar a:hover,
        html.dark .sidebar-link-item:hover {
            background: rgba(96, 165, 250, 0.18) !important;
            color: #FFFFFF !important;
        }
        html.dark .sidebar a.active,
        html.dark .sidebar-link-item.active {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
        }
        html.dark .sidebar .text-slate-400,
        html.dark .sidebar .text-slate-500 {
            color: #CBD5E1 !important;
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
