@extends('layouts.app')

@section('title', 'Vérification du Code - STAGILOG')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center relative p-4"
     style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
    
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-[#0D1B4B]/75 backdrop-blur-[3px]"></div>
    
    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#DC2626] to-[#991B1B] p-8 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-white mb-2">Code de Récupération</h2>
                <p class="text-sm text-red-100 font-medium">Saisissez le code reçu par email</p>
            </div>

            <div class="p-8">
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl">
                    <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                    <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('password.verify-code.post') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">
                            Code à 6 Chiffres <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               required 
                               maxlength="6"
                               pattern="[0-9]{6}"
                               inputmode="numeric"
                               autocomplete="off"
                               class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl text-center text-2xl font-mono font-bold tracking-widest focus:outline-none focus:ring-4 focus:ring-red-500/20 focus:border-red-500 transition @error('code') border-red-500 @enderror"
                               placeholder="000000"
                               autofocus>
                        @error('code')
                            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-amber-800 mb-1">Vérifiez votre boîte de réception</p>
                                <p class="text-xs text-amber-700 leading-relaxed">
                                    Un code à 6 chiffres a été envoyé à votre adresse email. Ce code est valable pendant 15 minutes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#DC2626] to-[#991B1B] hover:from-[#B91C1C] hover:to-[#7F1D1D] text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Vérifier le Code</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-slate-600 hover:text-red-600 transition">
                        ← Demander un nouveau code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
