@extends('layouts.main')

@section('title', __('salle.page_title'))

@section('content')
<!-- Hero Section -->
<div class="py-12" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-2xl mx-auto text-center">
            <img src="{{ asset('DP2.webp') }}" alt="DJOK PRESTIGE" class="h-20 mx-auto mb-6">
            <h1 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">
                {{ __('salle.virtual_room_title') }}
            </h1>
            <p class="text-gray-400">
                {{ __('salle.virtual_room_subtitle') }}
            </p>
        </div>
    </div>
</div>

<!-- Login Section -->
<div class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-md mx-auto">
            @if(session('error'))
            <div class="mb-6 p-4 rounded" style="background: #2a0f0f; border: 1px solid #7f1d1d;">
                <div class="flex items-center">
                    <i class="mr-3 fas fa-exclamation-circle" style="color: #f56565;"></i>
                    <p class="text-red-100">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 rounded" style="background: #064e3b; border: 1px solid #047857;">
                <div class="flex items-center">
                    <i class="mr-3 fas fa-check-circle" style="color: #a7f3d0;"></i>
                    <p class="text-green-100">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Affichage des erreurs de validation -->
            @if($errors->any())
            <div class="mb-6 p-4 rounded" style="background: #2a0f0f; border: 1px solid #7f1d1d;">
                <div class="flex items-start">
                    <i class="mr-3 mt-0.5 fas fa-exclamation-circle" style="color: #f56565;"></i>
                    <div>
                        <p class="font-semibold text-red-100">Erreur de connexion :</p>
                        <ul class="mt-1 text-sm text-red-100">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                <h2 class="mb-6 text-lg font-bold text-white md:text-xl">{{ __('salle.login') }}</h2>

                <form action="{{ route('elearning.login') }}" method="POST">
                    @csrf

                    <div>
                        <label for="access_code" class="block mb-2 text-sm font-medium" style="color: #ddd;">
                            {{ __('salle.access_code') }}
                        </label>
                        <div class="relative">
                            <input id="access_code" name="access_code" type="password" required
                                class="w-full px-4 py-2 pr-10 {{ $errors->has('access_code') ? 'border-red-500' : 'border-gray-600' }}"
                                style="background: #111; border: 1px solid #444; color: white;"
                                placeholder="{{ __('salle.access_code_placeholder') }}">
                            <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-white focus:outline-none"
                                onclick="togglePasswordVisibility()">
                                <i id="togglePasswordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('access_code')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ __('salle.access_code_hint') }}</p>
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block mb-2 text-sm font-medium" style="color: #ddd;">
                            {{ __('salle.email') }}
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="w-full px-4 py-2 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }}"
                            style="background: #111; border: 1px solid #444; color: white;"
                            placeholder="{{ __('salle.email_placeholder') }}">
                        @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full py-2 font-medium transition-all duration-300 flex items-center justify-center rounded"
                            style="background: #b89449; color: black;">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('salle.login_button') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-700">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-exclamation-triangle" style="color: #b89449;"></i>
                            <p class="text-sm text-gray-400">
                                <strong>{{ __('salle.warning') }}</strong> {{ __('salle.single_connection_warning') }}
                            </p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-envelope" style="color: #b89449;"></i>
                            <p class="text-sm text-gray-400">
                                {{ __('salle.lost_code') }} {{ __('salle.contact_support') }}
                                <a href="mailto:support@djokprestige.com"
                                    class="font-semibold text-white hover:underline">{{ __('salle.support_email') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('elearning.index') }}" class="text-gray-400 hover:text-white transition">
                    <i class="mr-2 fas fa-arrow-left"></i>
                    {{ __('salle.back_to_packages') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('access_code');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Optionnel : Auto-focus sur le champ code d'accès
    document.addEventListener('DOMContentLoaded', function() {
        const accessCodeInput = document.getElementById('access_code');
        if (accessCodeInput) {
            accessCodeInput.focus();
        }
    });
</script>
@endsection
