@extends('layouts.main')

@section('title', __('success.page_title'))

@section('content')
<!-- Hero Section -->
<div class="py-12" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('elearning.index') }}"
                    class="inline-flex items-center text-gray-400 hover:text-white">
                    <i class="mr-2 fas fa-arrow-left"></i>
                    {{ __('success.back_to_packages') }}
                </a>
            </div>

            <h1 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">
                {{ __('success.purchase_confirmed') }}
            </h1>
            <p class="text-gray-400">{{ __('success.package_activated') }}</p>
        </div>
    </div>
</div>

<!-- Confirmation Section -->
<div class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-6xl mx-auto">
            <!-- Messages de succès -->
            @if(session('success'))
            <div class="mb-6 p-6 rounded-lg" style="background: #064e3b; border: 1px solid #047857;">
                <div class="flex items-center">
                    <i class="mr-4 text-2xl fas fa-check-circle" style="color: #a7f3d0;"></i>
                    <div>
                        <h3 class="mb-2 text-xl font-bold text-white">{{ __('success.payment_confirmed') }}</h3>
                        <p class="text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations paiement -->
                <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="mb-6 text-xl font-bold text-white">
                        <i class="mr-2 fas fa-receipt"></i> {{ __('success.payment_information') }}
                    </h2>

                    @if(isset($paiement))
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                            <span class="text-gray-300">{{ __('success.reference') }}</span>
                            <span class="font-bold text-white">{{ $paiement->reference }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                            <span class="text-gray-300">{{ __('success.amount') }}</span>
                            <span class="text-xl font-bold" style="color: #b89449;">
                                {{ number_format($paiement->amount, 2, ',', ' ') }} €
                            </span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                            <span class="text-gray-300">{{ __('success.date') }}</span>
                            <span class="font-bold text-white">{{ $paiement->paid_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                            <span class="text-gray-300">{{ __('success.status') }}</span>
                            <span class="px-3 py-1 text-sm font-bold rounded-full"
                                style="background: #064e3b; color: #a7f3d0;">
                                <i class="mr-1 fas fa-check-circle"></i> {{ __('success.confirmed') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">{{ __('success.payment_method') }}</span>
                            <span class="font-bold text-white">
                                <i class="mr-2 fab fa-cc-stripe"></i> {{ __('success.credit_card') }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="mt-8 p-4 rounded" style="background: #0c4a6e; border: 1px solid #075985;">
                        <div class="flex items-center">
                            <i class="mr-3 fas fa-shield-alt" style="color: #7dd3fc;"></i>
                            <div>
                                <p class="text-white text-sm">
                                    <strong>{{ __('success.secure_transaction') }}</strong> {{
                                    __('success.secure_transaction_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations accès -->
                <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="mb-6 text-xl font-bold text-white">
                        <i class="mr-2 fas fa-graduation-cap"></i> {{ __('success.access_information') }}
                    </h2>

                    @if(isset($acces))
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-user" style="color: #b89449;"></i>
                            <div>
                                <h4 class="font-semibold text-white">{{ __('success.student') }}</h4>
                                <p class="text-gray-400">{{ $acces->prenom }} {{ $acces->nom }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-envelope" style="color: #b89449;"></i>
                            <div>
                                <h4 class="font-semibold text-white">{{ __('success.email') }}</h4>
                                <p class="text-gray-400">{{ $acces->email }}</p>
                            </div>
                        </div>

                        @if($acces->telephone)
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-phone" style="color: #b89449;"></i>
                            <div>
                                <h4 class="font-semibold text-white">{{ __('success.phone') }}</h4>
                                <p class="text-gray-400">{{ $acces->telephone }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-book-open" style="color: #b89449;"></i>
                            <div>
                                <h4 class="font-semibold text-white">{{ __('success.package') }}</h4>
                                <p class="text-gray-400">{{ $acces->forfait->name ?? __('success.e_learning_package') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="mt-1 mr-3 fas fa-calendar" style="color: #b89449;"></i>
                            <div>
                                <h4 class="font-semibold text-white">{{ __('success.access_duration') }}</h4>
                                <p class="text-gray-400">{{ $acces->forfait->duration_days ?? 28 }} {{
                                    __('success.days') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message important -->
                    <div class="mt-8 p-4 rounded" style="background: #78350f; border: 1px solid #92400e;">
                        <div class="flex items-center">
                            <i class="mr-3 fas fa-info-circle" style="color: #fbbf24;"></i>
                            <div>
                                <h5 class="font-bold text-white mb-1">{{ __('success.important') }}</h5>
                                <p class="text-amber-200 text-sm">
                                    {{ __('success.access_codes_sent') }} <strong>{{ $acces->email }}</strong>
                                </p>
                                <p class="text-amber-200 text-sm mt-2">
                                    {{ __('success.check_spam') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('elearning.salle') }}"
                    class="flex items-center justify-center py-3 font-semibold transition-all duration-300 rounded-lg hover:bg-yellow-600"
                    style="background: #b89449; color: black;">
                    <i class="mr-2 fas fa-door-open"></i>
                    {{ __('success.access_virtual_room') }}
                </a>

                <a href="{{ route('elearning.index') }}"
                    class="flex items-center justify-center py-3 font-semibold transition-all duration-300 border rounded-lg hover:bg-gray-800"
                    style="border-color: #b89449; color: #b89449;">
                    <i class="mr-2 fas fa-list"></i>
                    {{ __('success.view_other_packages') }}
                </a>
            </div>

            <!-- Support -->
            <div class="mt-12 text-center">
                <div class="inline-block p-4 rounded-lg" style="background: #1a1a1a;">
                    <i class="block mb-2 text-2xl fas fa-headset" style="color: #b89449;"></i>
                    <h4 class="font-bold text-white mb-2">{{ __('success.need_help') }}</h4>
                    <p class="text-gray-400 text-sm">
                        {{ __('success.contact_support') }}
                        <a href="mailto:support@djokprestige.com" class="hover:text-white" style="color: #b89449;">
                            {{ __('success.support_email') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informations complémentaires -->
<section class="py-12" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="mb-8 text-2xl font-bold text-center" style="color: #b89449;">
                {{ __('success.about_your_access') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="text-2xl mb-3 fas fa-lock" style="color: #b89449;"></i>
                    <h4 class="font-bold text-white mb-2">{{ __('success.secure') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('success.secure_desc') }}</p>
                </div>

                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="text-2xl mb-3 fas fa-clock" style="color: #b89449;"></i>
                    <h4 class="font-bold text-white mb-2">{{ __('success.access_24_7') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('success.access_24_7_desc') }}</p>
                </div>

                <div class="p-4 text-center rounded-lg" style="background: #111;">
                    <i class="text-2xl mb-3 fas fa-mobile-alt" style="color: #b89449;"></i>
                    <h4 class="font-bold text-white mb-2">{{ __('success.mobile') }}</h4>
                    <p class="text-sm text-gray-400">{{ __('success.mobile_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
