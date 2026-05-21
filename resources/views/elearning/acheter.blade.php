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
                                <!-- Prix barré et gratuit -->
                                <div class="text-sm text-gray-500 line-through">{{ $forfait->formatted_price }}</div>
                                <div class="text-2xl font-bold" style="color: #46b94c;">{{ __('acheter.free') }}</div>
                                <div class="text-sm text-gray-400">{{ $forfait->duration_days }} {{ __('acheter.access_days') }}</div>
                            </div>
                        </div>

                        <!-- Badge GRATUIT -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full" style="background: #064e3b; color: #a7f3d0;">
                                <i class="mr-1 fas fa-gift"></i> {{ __('acheter.free_access_promo') }}
                            </span>
                        </div>

                        <!-- Badge mode de sélection -->
                        <div class="mb-4">
                            @if($forfait->include_all_cours && $forfait->include_all_qcms && $forfait->include_all_examens)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full" style="background: #064e3b; color: #a7f3d0;">
                                    <i class="mr-1 fas fa-layer-group"></i> {{ __('acheter.all_inclusive') }}
                                </span>
                                <p class="mt-2 text-sm text-gray-400">{{ __('acheter.all_inclusive_desc') }}</p>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full" style="background: #1e40af; color: #60a5fa;">
                                    <i class="mr-1 fas fa-check-double"></i> {{ __('acheter.custom_selection') }}
                                </span>
                                <p class="mt-2 text-sm text-gray-400">{{ __('acheter.custom_selection_desc') }}</p>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="mt-1 mr-3 fas fa-clock" style="color: #b89449;"></i>
                                <div>
                                    <h4 class="font-semibold text-white">{{ __('acheter.access_duration') }}</h4>
                                    <p class="text-gray-400">{{ $forfait->duration_days }} {{ __('acheter.from_purchase') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="mt-1 mr-3 fas fa-book" style="color: #b89449;"></i>
                                <div>
                                    <h4 class="font-semibold text-white">{{ __('acheter.included_content') }}</h4>
                                    @if($forfait->include_all_cours && $forfait->include_all_qcms && $forfait->include_all_examens)
                                        <p class="text-gray-400">{{ __('acheter.all_content') }}</p>
                                    @else
                                        <ul class="mt-1 space-y-1 text-sm text-gray-400">
                                            @php
                                                $coursCount = $forfait->include_all_cours ? 'Tous les cours' : count($forfait->selected_cours_ids ?? []) . ' ' . __('acheter.courses_selected');
                                                $qcmsCount = $forfait->include_all_qcms ? 'Tous les QCM' : count($forfait->selected_qcms_ids ?? []) . ' ' . __('acheter.qcms_selected');
                                                $examensCount = $forfait->include_all_examens ? 'Tous les examens' : count($forfait->selected_examens_ids ?? []) . ' ' . __('acheter.exams_selected');
                                            @endphp
                                            <li><i class="mr-2 fas fa-check-circle" style="color: #46b94c; font-size: 10px;"></i> {{ $coursCount }}</li>
                                            @if($forfait->includes_qcm)
                                            <li><i class="mr-2 fas fa-check-circle" style="color: #46b94c; font-size: 10px;"></i> {{ $qcmsCount }}</li>
                                            @endif
                                            @if($forfait->includes_examens_blancs)
                                            <li><i class="mr-2 fas fa-check-circle" style="color: #46b94c; font-size: 10px;"></i> {{ $examensCount }}</li>
                                            @endif
                                            @if($forfait->includes_certification)
                                            <li><i class="mr-2 fas fa-check-circle" style="color: #46b94c; font-size: 10px;"></i> {{ __('acheter.certificate_included') }}</li>
                                            @endif
                                        </ul>
                                    @endif
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
                                    <strong>{{ __('acheter.important_note') }} :</strong> {{ __('acheter.access_codes_info') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire INSCRIPTION GRATUITE -->
                <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="mb-6 text-xl font-bold text-white">{{ __('acheter.your_information') }}</h2>

                    @if($errors->any())
                    <div class="p-4 mb-6 rounded-lg" style="background: #7f1d1d; border: 1px solid #991b1b;">
                        <div class="flex items-start">
                            <i class="mr-3 text-red-300 fas fa-exclamation-triangle mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="mb-2 font-bold text-white">{{ __('acheter.validation_errors') }}</h4>
                                <ul class="space-y-1 text-sm text-red-200">
                                    @foreach($errors->all() as $error)
                                    <li class="flex items-start">
                                        <i class="mr-2 fas fa-times-circle mt-0.5 text-red-300 text-xs"></i>
                                        <span>{{ $error }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form id="accessForm" action="{{ route('elearning.process-free-access', $forfait->slug) }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{ __('acheter.first_name') }} <span class="text-red-400">*</span></label>
                                    <input type="text" name="prenom" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('prenom') border-red-500 @else border-gray-600 @enderror"
                                        style="background: #111; color: white;"
                                        value="{{ old('prenom') }}">
                                    @error('prenom')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{ __('acheter.last_name') }} <span class="text-red-400">*</span></label>
                                    <input type="text" name="nom" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('nom') border-red-500 @else border-gray-600 @enderror"
                                        style="background: #111; color: white;"
                                        value="{{ old('nom') }}">
                                    @error('nom')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{ __('acheter.email') }} <span class="text-red-400">*</span></label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('email') border-red-500 @else border-gray-600 @enderror"
                                    style="background: #111; color: white;"
                                    placeholder="{{ __('acheter.email_placeholder') }}"
                                    value="{{ old('email') }}">
                                @error('email')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @else
                                <p class="mt-1 text-xs text-gray-500">{{ __('acheter.email_info') }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium" style="color: #ddd;">{{ __('acheter.phone') }}</label>
                                <input type="tel" name="telephone"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('telephone') border-red-500 @else border-gray-600 @enderror"
                                    style="background: #111; color: white;"
                                    placeholder="{{ __('acheter.phone_placeholder') }}"
                                    value="{{ old('telephone') }}">
                                @error('telephone')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-6 border-t border-gray-700">
                                <!-- Affichage du prix gratuit -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-400">{{ __('acheter.original_price') }}</span>
                                        <span class="text-lg text-gray-500 line-through">{{ $forfait->formatted_price }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold" style="color: #46b94c;">{{ __('acheter.your_price') }}</span>
                                        <span class="text-2xl font-bold" style="color: #46b94c;">{{ __('acheter.free') }}</span>
                                    </div>
                                    <div class="mt-3 p-3 rounded text-center" style="background: #064e3b;">
                                        <p class="text-sm text-green-200">
                                            <i class="mr-1 fas fa-gift"></i>
                                            {{ __('acheter.free_access_promo') }}
                                        </p>
                                    </div>
                                </div>

                                <button type="submit" id="submitBtn"
                                    class="flex items-center justify-center w-full py-3 font-semibold transition-all duration-300 rounded-lg hover:bg-green-600"
                                    style="background: #46b94c; color: white;"
                                    onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-2\'></i>{{ __('acheter.processing') }}'; this.form.submit();">
                                    <i class="mr-2 fas fa-download"></i>
                                    {{ __('acheter.get_free_access') }}
                                </button>

                                <p class="mt-4 text-xs text-center text-gray-500">
                                    <i class="mr-1 fas fa-envelope"></i>
                                    {{ __('acheter.no_payment_info') }}
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
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.getElementById('accessForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>{{ __('acheter.processing') }}';
        return true;
    });
</script>
@endsection
