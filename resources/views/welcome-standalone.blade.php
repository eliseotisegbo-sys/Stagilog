<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>STAGILOG - Technology Forever Group SARL</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
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
                            light: '#F0F4FF',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-[#1B3A8C] selection:text-white">
    
    <!-- Navbar Header -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 left-0 right-0 z-50 border-b border-slate-100 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Brand -->
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center p-1.5 shadow-md border border-slate-100">
                    <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#1B3A8C] tracking-tight leading-none">STAGILOG</h1>
                    <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-1">Technology Forever Group SARL</p>
                </div>
            </div>

            <!-- Nav Items (ÉCOLES UNIQUEMENT - Aucun lien Admin public) -->
            <nav class="flex items-center space-x-6">
                <a href="#accueil" class="text-sm font-semibold text-slate-600 hover:text-[#1B3A8C] transition">Accueil</a>
                <a href="#services" class="text-sm font-semibold text-slate-600 hover:text-[#1B3A8C] transition">Nos Domaines</a>
                <a href="#apropos" class="text-sm font-semibold text-slate-600 hover:text-[#1B3A8C] transition">À propos</a>
                <a href="{{ route('login.ecole') }}" 
                   class="inline-flex items-center space-x-2.5 bg-[#1B3A8C] hover:bg-[#142B6B] text-white text-sm font-bold px-6 py-3 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Espace École</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="accueil" class="relative min-h-[85vh] flex items-center justify-center bg-cover bg-center overflow-hidden"
             style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
        <!-- Overlay dégradé TFG -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#0D1B4B]/90 via-[#1B3A8C]/80 to-[#0D1B4B]/90 backdrop-blur-[2px]"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white py-20">
            <h2 class="text-4xl sm:text-6xl font-black mb-6 tracking-tight leading-tight">
                Accélérez la Gestion de Vos Stages avec <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-blue-200 underline decoration-[#E8001D] decoration-4">STAGILOG</span>
            </h2>
            
            <p class="text-lg sm:text-xl text-blue-100/90 max-w-3xl mx-auto mb-10 font-normal leading-relaxed">
                Votre portail centralisé pour la soumission des dossiers d'étudiants, le suivi académique personnalisé et la remise officielle des rapports et procès-verbaux de stage par <strong>TFG SARL</strong>.
            </p>

            <!-- Bouton d'action clair, pro et visible sans emoji -->
            <div class="flex justify-center items-center">
                <a href="{{ route('login.ecole') }}" 
                   class="inline-flex items-center space-x-3 bg-white text-[#1B3A8C] hover:text-[#0D1B4B] hover:bg-slate-100 px-9 py-4 rounded-2xl font-bold text-base shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 text-[#1B3A8C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Accéder à l'Espace École</span>
                </a>
            </div>

            <!-- Features Quick Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto mt-16 text-left">
                <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-white text-sm mb-1">Dépôt Dématérialisé</h3>
                    <p class="text-xs text-blue-200">Transmission rapide des listes, notes de demande et CVs.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-white text-sm mb-1">Validation & Suivi</h3>
                    <p class="text-xs text-blue-200">Suivi du statut de validation en temps réel.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-white text-sm mb-1">Rapports & PV de Stage</h3>
                    <p class="text-xs text-blue-200">Téléchargement direct des évaluations et documents finaux.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Domaines d'Activité TFG SARL -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-black uppercase tracking-widest text-[#E8001D]">Nos Métiers & Spécialités</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0D1B4B] mt-2 mb-4 tracking-tight">
                    Domaines d'Accueil des Stagiaires
                </h2>
                <p class="text-slate-600 text-sm sm:text-base">
                    Technology Forever Group SARL encadre les étudiants des universités et instituts supérieurs dans des pôles d'excellence technique et opérationnelle.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Informatique -->
                <div class="p-8 rounded-3xl bg-[#F0F4FF] border border-blue-100 hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0D1B4B] mb-2">Génie Informatique & IA</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Développement web & mobile, intégration d'applications, architectures cloud et solutions d'intelligence artificielle.</p>
                </div>

                <!-- Réseaux & Télécoms -->
                <div class="p-8 rounded-3xl bg-[#F0F4FF] border border-blue-100 hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0M1.394 9.393a15 15 0 0121.213 0"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0D1B4B] mb-2">Télécoms & Réseaux</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Infrastructures réseaux, fibre optique, télécommunications et administration des systèmes sécurisés.</p>
                </div>

                <!-- Énergie & Électricité -->
                <div class="p-8 rounded-3xl bg-[#F0F4FF] border border-blue-100 hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0D1B4B] mb-2">Énergie & Électromécanique</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Installations électriques industrielles, énergie solaire, maintenance froid et climatisation.</p>
                </div>

                <!-- Génie Civil & BTP -->
                <div class="p-8 rounded-3xl bg-[#F0F4FF] border border-blue-100 hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-[#1B3A8C] text-white flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0D1B4B] mb-2">Génie Civil & Gestion</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Conception d'ouvrages, gestion de projets techniques, comptabilité et management opérationnel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À Propos TFG SARL -->
    <section id="apropos" class="py-24 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0D1B4B] mb-6 tracking-tight leading-tight">
                        Technology Forever Group SARL
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base mb-4 leading-relaxed">
                        <strong>TFG SARL</strong> est une société d'ingénierie et de services intervenant dans les différents secteurs industriels et technologiques. Dotée d'une équipe dynamique d'ingénieurs de conception et d'experts chevronnés, notre mission est d'accompagner l'innovation et l'excellence opérationnelle.
                    </p>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-8">
                        À travers la plateforme <strong>STAGILOG</strong>, nous renforçons le pont entre le monde académique et le milieu professionnel, offrant aux étudiants des opportunités de stages pratiques hautement formatrices.
                    </p>

                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-bold text-[#0D1B4B]">Encadrement Certifié</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#1B3A8C] flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-[#0D1B4B]">Plateforme Sécurisée</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-100 relative">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-[#E8001D]/10 rounded-full blur-2xl"></div>
                    <h3 class="text-xl font-bold text-[#0D1B4B] mb-6">Informations & Coordonnées</h3>
                    
                    <div class="space-y-4 text-sm text-slate-600">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-[#1B3A8C] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Dakar, Sénégal</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-[#1B3A8C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>contact@tfg-sarl.com</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-[#1B3A8C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+221 33 800 00 00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0D1B4B] text-white py-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1">
                    <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg">STAGILOG</span>
            </div>
            
            <p class="text-xs text-blue-200 text-center sm:text-right">
                &copy; {{ date('Y') }} Technology Forever Group SARL. Tous droits réservés.
            </p>
        </div>
    </footer>

</body>
</html>
