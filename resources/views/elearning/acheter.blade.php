@extends('layouts.main')

@section('title', __('acheter.title_prefix') . $forfait->name . __('acheter.title_suffix'))

@section('content')
<!-- Hero Section -->
<div class="py-12" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('elearning.index') }}"
                    class="inline-flex items-center text-gray-400 hover:text-white">
                    <i class="mr-2 fas fa-arrow-left"></i>
                    {{ __('acheter.back_to_packages') }}
                </a>
            </div>

            <h1 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">
                {{ __('acheter.title_prefix') }}: {{ $forfait->name }}
            </h1>
            <p class="text-gray-400">{{ __('acheter.finalize_purchase') }}</p>
        </div>
    </div>
</div>

<!-- Achat Section -->
<div class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Forfait details -->
                <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="mb-6 text-xl font-bold text-white">{{ __('acheter.your_selection') }}</h2>

                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">{{ $forfait->name }}</h3>
                            <div class="text-right">
                                <div class="text-2xl font-bold" style="color: #b89449;">{{ $forfait->formatted_price }}
                                </div>
                                <div class="text-sm text-gray-400">{{ $forfait->duration_days }} {{
                                    __('acheter.access_days') }}</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="mt-1 mr-3 fas fa-clock" style="color: #b89449;"></i>
                                <div>
                                    <h4 class="font-semibold text-white">{{ __('acheter.access_duration') }}</h4>
                                    <p class="text-gray-400">{{ $forfait->duration_days }} {{
                                        __('acheter.from_purchase') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="mt-1 mr-3 fas fa-book" style="color: #b89449;"></i>
                                <div>
                                    <h4 class="font-semibold text-white">{{ __('acheter.included_content') }}</h4>
                                    <p class="text-gray-400">{{ __('acheter.all_content') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="mt-1 mr-3 fas fa-lock" style="color: #b89449;"></i>
                                <div>
                                    <h4 class="font-semibold text-white">{{ __('acheter.security') }}</h4>
                                    <p class="text-gray-400">{{ __('acheter.single_connection') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AJOUT DU BOUTON POUR LA SALLE VIRTUELLE -->
                    <div class="mb-4">
                        <a href="{{ route('elearning.salle') }}"
                            class="flex items-center justify-center w-full py-3 font-semibold text-center transition-all duration-300 rounded-lg hover:bg-green-600"
                            style="background: #10b981; color: white;">
                            <i class="mr-2 fas fa-door-open"></i>
                            {{ __('acheter.virtual_room_access') }}
                        </a>
                    </div>

                    <div class="p-4 rounded" style="background: #064e3b; border: 1px solid #047857;">
                        <div class="flex items-center">
                            <i class="mr-3 fas fa-info-circle" style="color: #a7f3d0;"></i>
                            <div>
                                <p class="text-white">
                                    <strong>{{ __('acheter.important_note') }} :</strong> {{
                                    __('acheter.access_codes_info') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire SIMPLIFIÉ SANS AJAX -->
                <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="mb-6 text-xl font-bold text-white">{{ __('acheter.your_information') }}</h2>

                    <!-- Messages d'erreur -->
                    @if($errors->any())
                    <div class="p-4 mb-6 bg-red-800 border border-red-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="mr-3 text-red-300 fas fa-exclamation-triangle"></i>
                            <div>
                                <h4 class="mb-1 font-bold text-white">{{ __('acheter.validation_errors') }}</h4>
                                @foreach($errors->all() as $error)
                                <p class="text-sm text-red-200">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- VERSION SIMPLIFIÉE : formulaire standard -->
                    <form id="paymentForm" action="{{ route('elearning.process-payment', $forfait->slug) }}"
                        method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{
                                        __('acheter.first_name') }} *</label>
                                    <input type="text" name="prenom" required
                                        class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                        style="background: #111; color: white;" value="{{ old('prenom') }}">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{
                                        __('acheter.last_name') }} *</label>
                                    <input type="text" name="nom" required
                                        class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                        style="background: #111; color: white;" value="{{ old('nom') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{
                                    __('acheter.email') }} *</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    style="background: #111; color: white;"
                                    placeholder="{{ __('acheter.email_placeholder') }}" value="{{ old('email') }}">
                                <p class="mt-1 text-xs text-gray-500">{{ __('acheter.email_info') }}
                                </p>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{
                                    __('acheter.phone') }}</label>
                                <input type="tel" name="telephone"
                                    class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    style="background: #111; color: white;"
                                    placeholder="{{ __('acheter.phone_placeholder') }}" value="{{ old('telephone') }}">
                            </div>

                            <div class="pt-6 border-t border-gray-700">
                                <div class="flex items-center justify-between mb-6">
                                    <span class="text-gray-300">{{ __('acheter.total') }}</span>
                                    <span class="text-2xl font-bold" style="color: #b89449;">{{
                                        $forfait->formatted_price }}</span>
                                </div>

                                <button type="submit" id="submitBtn"
                                    class="flex items-center justify-center w-full py-3 font-semibold transition-all duration-300 rounded-lg hover:bg-yellow-600"
                                    style="background: #b89449; color: black;"
                                    onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i>{{ __('acheter.processing') }}'; this.form.submit();">
                                    <i class="mr-2 fas fa-lock"></i>
                                    {{ __('acheter.pay_now') }}
                                </button>

                                <p class="mt-4 text-xs text-center text-gray-500">
                                    <i class="mr-1 fas fa-shield-alt"></i>
                                    {{ __('acheter.secure_payment') }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informations complémentaires -->
<section class="py-12" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="mb-3 text-2xl fas fa-envelope" style="color: #b89449;"></i>
                    <h4 class="mb-2 font-bold text-white">{{ __('acheter.immediate_code') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('acheter.immediate_code_desc') }}</p>
                </div>
                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="mb-3 text-2xl fas fa-headset" style="color: #b89449;"></i>
                    <h4 class="mb-2 font-bold text-white">{{ __('acheter.support_included') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('acheter.support_included_desc') }}</p>
                </div>
                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="mb-3 text-2xl fas fa-sync" style="color: #b89449;"></i>
                    <h4 class="mb-2 font-bold text-white">{{ __('acheter.satisfaction_guarantee') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('acheter.satisfaction_guarantee_desc') }}</p>
                </div>
            </div>
        </div>
</section>
@endsection

@section('scripts')
<!-- Script minimal pour gérer le bouton -->
<script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>{{ __('acheter.processing') }}';

        // Le formulaire sera soumis normalement sans AJAX
        return true;
    });
</script>
@endsection
