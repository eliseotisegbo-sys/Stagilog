@extends('layouts.app')

@section('title', 'Vérification de Sécurité - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0D1B4B] via-[#1B3A8C] to-[#0A1232] px-4 py-12 relative overflow-hidden">
    
    <!-- Éléments d'ambiance visuelle en arrière-plan -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#E8001D]/15 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-md w-full relative z-10">
        
        <!-- Carte principale -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40 p-8 sm:p-10 text-center relative overflow-hidden">
            
            <!-- Bande rouge TFG en haut -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#1B3A8C] via-[#E8001D] to-[#1B3A8C]"></div>

            <!-- Logo TFG & Icône Sécurité -->
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 text-[#1B3A8C] mb-6 shadow-inner border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-black text-[#0D1B4B] tracking-tight">Vérification de Sécurité</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Un code à 6 chiffres a été envoyé par email à :<br>
                <span class="font-bold text-[#1B3A8C] font-mono text-sm">{{ $email }}</span>
            </p>

            <!-- Alerts -->
            @if(session('info'))
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs font-semibold text-left flex items-center space-x-2">
                <svg class="w-4 h-4 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold text-left flex items-center space-x-2">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error') || $errors->any())
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-semibold text-left flex items-center space-x-2">
                <svg class="w-4 h-4 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('error') ?? $errors->first() }}</span>
            </div>
            @endif

            <!-- Formulaire 6 Digits -->
            <form method="POST" action="{{ route('login.verify-code.submit') }}" id="otp-form" class="mt-8 space-y-6">
                @csrf

                <!-- Conteneur des 6 inputs -->
                <div class="flex justify-between items-center gap-2 max-w-xs mx-auto">
                    @for($i = 1; $i <= 6; $i++)
                    <input type="text" name="digit_{{ $i }}" id="digit_{{ $i }}" maxlength="1" inputmode="numeric" autocomplete="one-time-code" required
                           class="otp-input w-11 h-14 sm:w-12 sm:h-16 text-center font-mono text-2xl font-black text-[#0D1B4B] bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-[#1B3A8C] focus:bg-white focus:ring-4 focus:ring-[#1B3A8C]/10 focus:outline-none transition transform focus:scale-105 shadow-sm">
                    @endfor
                </div>

                <button type="submit" id="btn-submit-otp"
                        class="w-full py-4 bg-[#1B3A8C] hover:bg-[#142B6B] text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-blue-900/30 transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                    <span>Valider & Accéder</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Actions secondaires (Renvoi & Changement de compte) -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <form method="POST" action="{{ route('login.verify-code.resend') }}">
                    @csrf
                    <button type="submit" class="font-bold text-[#1B3A8C] hover:underline flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Renvoyer un code</span>
                    </button>
                </form>

                <a href="{{ $role === 'admin' ? route('login.admin') : route('login.ecole') }}" 
                   class="font-semibold text-slate-400 hover:text-slate-600 transition">
                    Utiliser un autre compte
                </a>
            </div>

        </div>

        <!-- Footer Sécurité -->
        <p class="text-center text-[11px] text-blue-200/60 mt-6">
            Protection sécurisée STAGILOG &bull; Technology Forever Group SARL
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-input');
    
    // Auto-focus le premier champ
    if (inputs.length > 0) {
        inputs[0].focus();
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            // Ne garder que les chiffres
            e.target.value = val.replace(/\D/g, '');
            
            if (e.target.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Auto-submit si 6 chiffres saisis
            let fullCode = Array.from(inputs).map(i => i.value).join('');
            if (fullCode.length === 6) {
                submitOtpForm();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        // Gestion du collage (Paste)
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
            const digits = pasteData.replace(/\D/g, '').slice(0, 6);
            
            digits.split('').forEach((char, i) => {
                if (inputs[i]) {
                    inputs[i].value = char;
                }
            });

            if (digits.length === 6) {
                submitOtpForm();
            } else if (digits.length > 0 && inputs[digits.length]) {
                inputs[digits.length].focus();
            }
        });
    });

    let formSubmitted = false;
    function submitOtpForm() {
        if (formSubmitted) return;
        formSubmitted = true;
        
        // Disable inputs to prevent further typing/submitting
        inputs.forEach(input => input.setAttribute('readonly', 'true'));
        
        const btn = document.getElementById('btn-submit-otp');
        if(btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Validation...';
        }

        document.getElementById('otp-form').submit();
    }

    // Handle form submit on manual button click
    const otpForm = document.getElementById('otp-form');
    if (otpForm) {
        otpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitOtpForm();
        });
    }

});
</script>
@endsection
