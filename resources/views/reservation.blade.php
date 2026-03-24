@extends('layouts.main')

@section('title', trans('reservation.title'))
@section('meta_description', trans('reservation.meta_description'))

@section('content')
<style>
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .pac-container {
        z-index: 1051 !important;
    }

    .price-calculation {
        background: #1a1a1a;
        border: 1px solid var(--gold);
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        display: none;
    }

    .price-calculation.show {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .price-detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #333;
    }

    .price-total {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--gold);
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid var(--gold);
    }

    .price-note {
        font-size: 0.875rem;
        color: #888;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .distance-info {
        background: #111;
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        border-left: 4px solid var(--gold);
    }

    .loading-indicator {
        display: none;
        color: var(--gold);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .required-field {
        border: 1px solid #444;
        transition: border-color 0.3s;
    }

    .required-field.error {
        border-color: #f87171;
    }

    .required-field.valid {
        border-color: #10b981;
    }

    .address-hint {
        font-size: 0.75rem;
        color: #888;
        margin-top: 0.25rem;
    }

    .payment-option {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 1rem;
        border: 2px solid #333;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #111;
    }

    .payment-option:hover {
        border-color: #555;
    }

    .payment-option.selected {
        border-color: var(--gold);
        background: rgba(212, 175, 55, 0.1);
    }

    .payment-option input[type="radio"] {
        margin-right: 1rem;
        accent-color: var(--gold);
    }

    .payment-option-content {
        flex: 1;
    }

    .payment-option-title {
        font-weight: bold;
        margin-bottom: 0.25rem;
        color: white;
    }

    .payment-option-description {
        font-size: 0.875rem;
        color: #aaa;
    }

    .price-badge {
        background: var(--gold);
        color: black;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.875rem;
    }

    .payment-processing {
        display: none;
        text-align: center;
        padding: 2rem;
    }

    .spinner {
        border: 3px solid rgba(255, 255, 255, 0.1);
        border-top: 3px solid var(--gold);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<!-- Google Maps API - NE PAS DUPLIQUER CETTE BALISE -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-rvuKIwW9A6E4V8ks-Bj0YoyXII-TsU8&libraries=places&language={{ app()->getLocale() }}&region=FR&callback=initAutocomplete">
</script>

<!-- Hero Section - Style sobre -->
<header class="relative min-h-screen flex items-center" style="background: #000;">
    <div class="absolute inset-0 bg-black">
        <img src="https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80"
            alt="{{ trans('reservation.hero_title') }}" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.7);"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 py-20 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-8" style="color: var(--gold);">
                {{ trans('reservation.hero_title') }}
            </h1>

            <p class="text-lg md:text-xl text-gray-300 mb-12">
                {{ trans('reservation.hero_description') }}
            </p>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#reservation"
                    class="w-full sm:w-auto px-6 md:px-8 py-3 font-semibold text-center transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('reservation.book_now') }}
                </a>
                <a href="#devis-entreprise"
                    class="w-full sm:w-auto px-6 md:px-8 py-3 font-semibold text-center border transition duration-300"
                    style="border-color: var(--gold); color: var(--gold);">
                    {{ trans('reservation.business_quote') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
        <a href="#services" class="text-white transition duration-300 hover:text-var(--gold)"
            aria-label="{{ trans('reservation.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Nos services de transport - Style sobre -->
<section id="services" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('reservation.services_title') }}</h2>
        </div>

        <!-- Transferts aéroports & gares -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center mb-12">
                <div>
                    <h3 class="text-2xl md:text-3xl font-semibold mb-6" style="color: white;">{{
                        trans('reservation.airport_transfers_title') }}</h3>
                    <p class="text-gray-400 mb-6">
                        {{ trans('reservation.airport_transfers_desc') }}
                    </p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.service_welcome') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.service_tracking') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.service_waiting') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.service_comfort') }}</span>
                        </li>
                    </ul>

                    <a href="#reservation-transferts"
                        class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                        style="background: var(--gold); color: black;">
                        {{ trans('reservation.book_now') }}
                    </a>
                </div>

                <div class="p-6 md:p-8" style="background: #111; border: 1px solid #333;">
                    <h4 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                        trans('reservation.popular_routes') }}</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 rounded"
                            style="background: #1a1a1a; color: white;">
                            <span class="font-medium">{{ trans('reservation.route_cdg') }}</span>
                            <span class="font-bold" style="color: var(--gold);">{{ trans('reservation.starting_from') }}
                                60 €</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded"
                            style="background: #1a1a1a; color: white;">
                            <span class="font-medium">{{ trans('reservation.route_orly') }}</span>
                            <span class="font-bold" style="color: var(--gold);">{{ trans('reservation.starting_from') }}
                                45 €</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded"
                            style="background: #1a1a1a; color: white;">
                            <span class="font-medium">{{ trans('reservation.route_nord') }}</span>
                            <span class="font-bold" style="color: var(--gold);">{{ trans('reservation.starting_from') }}
                                35 €</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Déplacements professionnels -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h3 class="text-2xl md:text-3xl font-semibold mb-6" style="color: white;">{{
                        trans('reservation.professional_title') }}</h3>
                    <p class="text-gray-400 mb-6">
                        {{ trans('reservation.professional_desc') }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-file-invoice-dollar mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.billing_simple') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.daily_packages') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-tie mr-3" style="color: var(--gold);"></i>
                            <span style="color: white;">{{ trans('reservation.vip_services') }}</span>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 p-6 md:p-8" style="background: #111; border: 1px solid #333;">
                    <h4 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                        trans('reservation.business_advantages') }}</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-building mr-3" style="color: #60a5fa;"></i>
                            <span style="color: white;">{{ trans('reservation.monthly_invoice') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-users mr-3" style="color: #60a5fa;"></i>
                            <span style="color: white;">{{ trans('reservation.multi_user') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chart-line mr-3" style="color: #60a5fa;"></i>
                            <span style="color: white;">{{ trans('reservation.usage_reports') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Événements privés & mariages -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <div>
                    <h3 class="text-2xl md:text-3xl font-semibold mb-6" style="color: white;">{{
                        trans('reservation.events_title') }}</h3>
                    <p class="text-gray-400 mb-6">
                        {{ trans('reservation.events_desc') }}
                    </p>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <i class="fas fa-car mt-1 mr-3" style="color: var(--gold);"></i>
                            <div>
                                <h4 class="font-semibold" style="color: white;">{{ trans('reservation.guests_transfer')
                                    }}</h4>
                                <p class="text-sm text-gray-400">{{ trans('reservation.guests_service') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock mt-1 mr-3" style="color: var(--gold);"></i>
                            <div>
                                <h4 class="font-semibold" style="color: white;">{{ trans('reservation.day_service') }}
                                </h4>
                                <p class="text-sm text-gray-400">{{ trans('reservation.dedicated_vehicle') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-gem mt-1 mr-3" style="color: var(--gold);"></i>
                            <div>
                                <h4 class="font-semibold" style="color: white;">{{
                                    trans('reservation.decorated_vehicle') }}</h4>
                                <p class="text-sm text-gray-400">{{ trans('reservation.custom_colors') }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="#devis-mariage"
                        class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                        style="background: var(--gold); color: black;">
                        {{ trans('reservation.get_wedding_quote') }}
                    </a>
                </div>

                <div class="p-6 md:p-8" style="background: #111; border: 1px solid #333;">
                    <h4 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                        trans('reservation.wedding_package') }}</h4>
                    <div class="space-y-4">
                        <div class="p-4 rounded" style="background: #1a1a1a; color: white;">
                            <h5 class="font-bold mb-2">{{ trans('reservation.prestige_package') }}</h5>
                            <p class="text-gray-400 text-sm mb-2">{{ trans('reservation.unforgettable_wedding') }}</p>
                            <div class="text-xl font-bold" style="color: var(--gold);">{{
                                trans('reservation.starting_from') }} 490 €</div>
                        </div>
                        <p class="text-sm text-gray-400">
                            {{ trans('reservation.package_includes') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mise à disposition horaire ou journalière -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h3 class="text-2xl md:text-3xl font-semibold mb-6" style="color: white;">{{
                        trans('reservation.hourly_title') }}</h3>
                    <p class="text-gray-400 mb-6">
                        {{ trans('reservation.hourly_desc') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 rounded" style="background: #111; border: 1px solid #333; color: white;">
                            <h4 class="font-bold mb-2">{{ trans('reservation.half_day') }}</h4>
                            <p class="text-gray-400 text-sm">{{ trans('reservation.hours_4') }}</p>
                            <div class="text-lg font-bold" style="color: var(--gold);">{{
                                trans('reservation.starting_from') }} 150 €</div>
                        </div>
                        <div class="p-4 rounded" style="background: #111; border: 1px solid #333; color: white;">
                            <h4 class="font-bold mb-2">{{ trans('reservation.full_day') }}</h4>
                            <p class="text-gray-400 text-sm">{{ trans('reservation.hours_8') }}</p>
                            <div class="text-lg font-bold" style="color: var(--gold);">{{
                                trans('reservation.starting_from') }} 280 €</div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 p-6 md:p-8" style="background: #111; border: 1px solid #333;">
                    <h4 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                        trans('reservation.ideal_for') }}</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-landmark mr-3" style="color: #10b981;"></i>
                            <span style="color: white;">{{ trans('reservation.tourism') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-shopping-bag mr-3" style="color: #10b981;"></i>
                            <span style="color: white;">{{ trans('reservation.shopping') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-briefcase mr-3" style="color: #10b981;"></i>
                            <span style="color: white;">{{ trans('reservation.client_rounds') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-road mr-3" style="color: #10b981;"></i>
                            <span style="color: white;">{{ trans('reservation.intercity') }}</span>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <a href="#devis-horaire"
                            class="inline-flex items-center px-6 py-2 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black;">
                            {{ trans('reservation.get_hourly_quote') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Options et confort à bord - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('reservation.options_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('reservation.options_desc') }}
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach([
            [trans('reservation.ac_individual'), 'fas fa-snowflake'],
            [trans('reservation.wifi_free'), 'fas fa-wifi'],
            [trans('reservation.chargers'), 'fas fa-charging-station'],
            [trans('reservation.water_wipes'), 'fas fa-wine-bottle'],
            [trans('reservation.baby_seat'), 'fas fa-baby'],
            [trans('reservation.payment_options'), 'fas fa-credit-card']
            ] as $option)
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-3 md:mb-4"
                    style="background: #1a1a1a; border: 1px solid #333;">
                    <i class="{{ $option[1] }}" style="color: var(--gold); font-size: 1.25rem;"></i>
                </div>
                <span class="text-xs md:text-sm font-medium" style="color: white;">{{ $option[0] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tarification transparente - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('reservation.pricing_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('reservation.pricing_desc') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" style="background: #111; border: 1px solid #333;">
                <thead>
                    <tr style="background: #000;">
                        <th class="py-3 px-4 md:px-6 text-left text-white">{{ trans('reservation.category') }}</th>
                        <th class="py-3 px-4 md:px-6 text-center text-white">{{ trans('reservation.pickup_fee') }}</th>
                        <th class="py-3 px-4 md:px-6 text-center text-white">{{ trans('reservation.price_per_km') }}
                        </th>
                        <th class="py-3 px-4 md:px-6 text-center text-white">{{ trans('reservation.minimum_price') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicleCategories as $category)
                    @if($category->actif)
                    <tr class="border-b" style="border-color: #333; color: white;">
                        <td class="py-3 px-4 md:px-6 font-semibold">{{ $category->display_name }}</td>
                        <td class="py-3 px-4 md:px-6 text-center font-bold" style="color: var(--gold);">
                            {{ number_format($category->prise_en_charge, 2, ',', ' ') }} €
                        </td>
                        <td class="py-3 px-4 md:px-6 text-center font-bold" style="color: var(--gold);">
                            {{ number_format($category->prix_au_km, 2, ',', ' ') }} €
                        </td>
                        <td class="py-3 px-4 md:px-6 text-center font-bold" style="color: var(--gold);">
                            {{ number_format($category->prix_minimum, 2, ',', ' ') }} €
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Sections réservation et devis - Style sobre -->
<section id="reservation" class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                <!-- Formulaire de réservation -->
                <div id="reservation-transferts" class="p-6 md:p-8"
                    style="background: #1a1a1a; border: 1px solid #333;">
                    <h2 class="text-2xl md:text-3xl font-bold mb-6" style="color: var(--gold);">{{
                        trans('reservation.booking_title') }}</h2>

                    @if(session('success'))
                    <div class="p-4 mb-6" style="background: #064e3b; border: 1px solid #047857;">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3" style="color: #a7f3d0;"></i>
                            <div>
                                <h4 class="font-bold text-white">{{ trans('reservation.success_title') }}</h4>
                                <p class="text-green-100">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3" style="color: #fca5a5;"></i>
                            <div>
                                <h4 class="font-bold text-white">{{ trans('reservation.error_title') }}</h4>
                                <p class="text-red-100">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3" style="color: #fca5a5;"></i>
                            <div>
                                <h4 class="font-bold text-white">{{ trans('reservation.error_correction') }}</h4>
                                <ul class="text-red-100 list-disc ml-4">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form id="reservation-form" action="{{ route('reservation.submit') }}" method="POST">
                        @csrf

                        <!-- CHAMP CACHÉ POUR start_date - CORRECTION DE L'ERREUR -->
                        <input type="hidden" name="start_date" id="start_date_hidden" value="{{ old('start_date') }}">

                        <div class="space-y-6">
                            <!-- Type de service -->
                            <div>
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                    trans('reservation.service_type') }}</label>
                                <select name="type_service" required class="w-full px-4 py-3 rounded required-field"
                                    style="background: #111; color: white;">
                                    <option value="" style="color: #666;">{{ trans('reservation.select_service') }}
                                    </option>
                                    <option value="transfert" {{ old('type_service')=='transfert' ? 'selected' : '' }}
                                        style="color: white;">
                                        {{ trans('reservation.transfer_option') }}
                                    </option>
                                    <option value="professionnel" {{ old('type_service')=='professionnel' ? 'selected'
                                        : '' }} style="color: white;">
                                        {{ trans('reservation.professional_option') }}
                                    </option>
                                    <option value="evenement" {{ old('type_service')=='evenement' ? 'selected' : '' }}
                                        style="color: white;">
                                        {{ trans('reservation.event_option') }}
                                    </option>
                                    <option value="mise_disposition" {{ old('type_service')=='mise_disposition'
                                        ? 'selected' : '' }} style="color: white;">
                                        {{ trans('reservation.rental_option') }}
                                    </option>
                                </select>
                            </div>

                            <!-- Informations du trajet -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.departure_location') }}</label>
                                    <input type="text" id="depart" name="depart" required value="{{ old('depart') }}"
                                        class="w-full px-4 py-3 rounded required-field autocomplete"
                                        style="background: #111; color: white;"
                                        placeholder="{{ trans('reservation.departure_placeholder') }}">
                                    <input type="hidden" id="depart_lat" name="depart_lat"
                                        value="{{ old('depart_lat') }}">
                                    <input type="hidden" id="depart_lng" name="depart_lng"
                                        value="{{ old('depart_lng') }}">
                                    <div class="address-hint">{{ trans('reservation.address_hint') }}</div>
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.arrival_location') }}</label>
                                    <input type="text" id="arrivee" name="arrivee" required value="{{ old('arrivee') }}"
                                        class="w-full px-4 py-3 rounded required-field autocomplete"
                                        style="background: #111; color: white;"
                                        placeholder="{{ trans('reservation.arrival_placeholder') }}">
                                    <input type="hidden" id="arrivee_lat" name="arrivee_lat"
                                        value="{{ old('arrivee_lat') }}">
                                    <input type="hidden" id="arrivee_lng" name="arrivee_lng"
                                        value="{{ old('arrivee_lng') }}">
                                    <div class="address-hint">{{ trans('reservation.address_hint') }}</div>
                                </div>

                                <!-- Information distance -->
                                <div id="distance-info" class="distance-info" style="display: none;">
                                    <div class="flex justify-between items-center">
                                        <span style="color: white;">{{ trans('reservation.estimated_distance') }}</span>
                                        <span id="distance-text" class="font-bold" style="color: var(--gold);"></span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span style="color: white;">{{ trans('reservation.estimated_duration') }}</span>
                                        <span id="duration-text" class="font-bold" style="color: var(--gold);"></span>
                                    </div>
                                    <div id="loading-distance" class="loading-indicator">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>{{ trans('reservation.calculating')
                                        }}
                                    </div>
                                </div>
                            </div>

                            <!-- Date et heure -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.date_label') }}</label>
                                    <input type="date" name="date" id="date_input" required value="{{ old('date') }}"
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;" min="{{ date('Y-m-d') }}">
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.time_label') }}</label>
                                    <input type="time" name="heure" id="time_input" required value="{{ old('heure') }}"
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;">
                                </div>
                            </div>

                            <!-- Véhicule et passagers -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.vehicle_type') }}</label>
                                    <select name="vehicle_category_id" id="vehicle_category_id" required
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;">
                                        <option value="" style="color: #666;">{{ trans('reservation.select_vehicle') }}
                                        </option>
                                        @foreach($vehicleCategories as $category)
                                        @if($category->actif)
                                        <option value="{{ $category->id }}"
                                            data-prise-charge="{{ $category->prise_en_charge }}"
                                            data-prix-km="{{ $category->prix_au_km }}"
                                            data-prix-min="{{ $category->prix_minimum }}" {{
                                            old('vehicle_category_id')==$category->id ? 'selected' : '' }}
                                            style="color: white;">
                                            {{ $category->display_name }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.passengers_count') }}</label>
                                    <select name="passagers" id="passagers" required
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;">
                                        <option value="" style="color: #666;">{{ trans('reservation.select_passengers')
                                            }}</option>
                                        @for($i = 1; $i <= 8; $i++) <option value="{{ $i }}" {{ old('passagers')==$i
                                            ? 'selected' : '' }} style="color: white;">
                                            {{ $i }} {{ $i == 1 ? trans('reservation.person') :
                                            trans('reservation.people') }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Simulation de prix -->
                            <div id="price-calculation" class="price-calculation">
                                <h4 class="font-bold mb-4" style="color: var(--gold);">{{
                                    trans('reservation.price_simulation') }}</h4>

                                <div class="mb-4 p-3 rounded" style="background: #111; border: 1px solid #333;">
                                    <div class="flex justify-between items-center mb-2">
                                        <span style="color: #ddd;">{{ trans('reservation.estimated_distance') }}</span>
                                        <span id="simulation-distance" style="color: white;">0,0 km</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span style="color: #ddd;">{{ trans('reservation.passengers_count') }}</span>
                                        <span id="simulation-passagers" style="color: white;">1</span>
                                    </div>
                                </div>

                                <div class="price-detail">
                                    <span style="color: #ddd;">{{ trans('reservation.pickup_fee_label') }}</span>
                                    <span id="prise-charge" style="color: white;">0,00 €</span>
                                </div>

                                <div class="price-detail">
                                    <span style="color: #ddd;">{{ trans('reservation.distance_price') }}</span>
                                    <span id="prix-distance" style="color: white;">0,00 €</span>
                                </div>

                                <div class="price-detail">
                                    <span style="color: #ddd;">{{ trans('reservation.subtotal_vat') }}</span>
                                    <span id="prix-ht" style="color: white;">0,00 €</span>
                                </div>

                                <div class="price-total">
                                    <span>{{ trans('reservation.total_incl_vat') }}</span>
                                    <span id="prix-ttc">0,00 €</span>
                                </div>

                                <div class="price-detail">
                                    <span style="color: #ddd;">{{ trans('reservation.guaranteed_minimum') }}</span>
                                    <span id="prix-minimum" style="color: var(--gold);">0,00 €</span>
                                </div>

                                <p class="price-note">
                                    {{ trans('reservation.price_note1') }}
                                    <br>
                                    {{ trans('reservation.price_note2') }}
                                </p>
                            </div>

                            <!-- Options de paiement -->
                            <div>
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                    trans('reservation.payment_option') }}</label>

                                <div class="space-y-3">
                                    <label class="payment-option" for="payment_option_demande">
                                        <input type="radio" id="payment_option_demande" name="payment_option"
                                            value="demande" {{ old('payment_option')=='demande' ? 'checked' : '' }}
                                            required>
                                        <div class="payment-option-content">
                                            <div class="payment-option-title">
                                                {{ trans('reservation.quote_request') }}
                                            </div>
                                            <div class="payment-option-description">
                                                {{ trans('reservation.quote_request_desc') }}
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-option" for="payment_option_pay_now">
                                        <input type="radio" id="payment_option_pay_now" name="payment_option"
                                            value="pay_now" {{ old('payment_option')=='pay_now' ? 'checked' : '' }}
                                            required>
                                        <div class="payment-option-content">
                                            <div class="payment-option-title">
                                                {{ trans('reservation.pay_now_option') }}
                                            </div>
                                            <div class="payment-option-description">
                                                {{ trans('reservation.pay_now_desc') }}
                                            </div>
                                        </div>
                                        <div class="price-badge" id="pay-now-price">0,00 €</div>
                                    </label>
                                </div>
                            </div>

                            <!-- Informations personnelles -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.full_name') }}</label>
                                    <input type="text" name="nom" required value="{{ old('nom') }}"
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;">
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('reservation.phone') }}</label>
                                    <input type="tel" name="telephone" required value="{{ old('telephone') }}"
                                        class="w-full px-4 py-3 rounded required-field"
                                        style="background: #111; color: white;">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{ trans('reservation.email')
                                    }}</label>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                    class="w-full px-4 py-3 rounded required-field"
                                    style="background: #111; color: white;">
                            </div>

                            <div>
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                    trans('reservation.instructions') }}</label>
                                <textarea name="instructions" rows="3" class="w-full px-4 py-3 rounded"
                                    style="background: #111; color: white;"
                                    placeholder="{{ trans('reservation.instructions_placeholder') }}">{{ old('instructions') }}</textarea>
                            </div>

                            <!-- Boutons de soumission -->
                            <div class="space-y-4">
                                <!-- Bouton pour demande de devis -->
                                <button type="submit" id="submit-quote"
                                    class="w-full inline-flex items-center justify-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                                    style="background: #111; color: white; border: 1px solid #333;">
                                    <i class="fas fa-envelope mr-3"></i>{{ trans('reservation.request_quote') }}
                                </button>

                                <!-- Bouton pour paiement (caché par défaut) -->
                                <button type="submit" id="submit-payment-main"
                                    class="w-full inline-flex items-center justify-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                                    style="background: var(--gold); color: black; display: none;">
                                    <i class="fas fa-credit-card mr-3"></i>{{ trans('reservation.pay_booking') }} <span
                                        id="payment-amount-main">0,00 €</span>
                                </button>
                            </div>

                            <!-- Indicateur de chargement -->
                            <div id="payment-processing" class="payment-processing">
                                <div class="spinner"></div>
                                <p style="color: white;">{{ trans('reservation.processing_payment') }}</p>
                            </div>

                            <p class="text-sm text-center mt-4" style="color: #888;">
                                {{ trans('reservation.terms_acceptance') }}
                                <a href="{{ route('cgu') }}" class="hover:text-gray-300" style="color: var(--gold);">{{
                                    trans('reservation.terms_link') }}</a>
                                {{ __('et notre') }}
                                <a href="{{ route('rgpd') }}" class="hover:text-gray-300" style="color: var(--gold);">{{
                                    trans('reservation.privacy_link') }}</a>.
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Devis et contact -->
                <div>
                    <!-- Devis entreprise -->
                    <div id="devis-entreprise" class="p-6 md:p-8 mb-8"
                        style="background: #1a1a1a; border: 1px solid #333;">
                        <h3 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                            trans('reservation.business_quote_title') }}</h3>
                        <p class="text-gray-400 mb-6">{{ trans('reservation.business_quote_desc') }}</p>
                        <a href="{{ route('contact') }}?type=entreprise"
                            class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                            style="background: #111; color: white; border: 1px solid #333;">
                            <i class="fas fa-building mr-3"></i>{{ trans('reservation.get_business_quote') }}
                        </a>
                    </div>

                    <!-- Devis mariage -->
                    <div id="devis-mariage" class="p-6 md:p-8 mb-8"
                        style="background: #1a1a1a; border: 1px solid #333;">
                        <h3 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                            trans('reservation.wedding_quote_title') }}</h3>
                        <p class="text-gray-400 mb-6">{{ trans('reservation.wedding_quote_desc') }}</p>
                        <a href="{{ route('contact') }}?type=mariage"
                            class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                            style="background: #111; color: white; border: 1px solid #333;">
                            <i class="fas fa-heart mr-3"></i>{{ trans('reservation.get_wedding_quote') }}
                        </a>
                    </div>

                    <!-- Devis horaire -->
                    <div id="devis-horaire" class="p-6 md:p-8 mb-8"
                        style="background: #1a1a1a; border: 1px solid #333;">
                        <h3 class="text-lg md:text-xl font-bold mb-4" style="color: white;">{{
                            trans('reservation.hourly_quote_title') }}</h3>
                        <p class="text-gray-400 mb-6">{{ trans('reservation.hourly_quote_desc') }}</p>
                        <a href="{{ route('contact') }}?type=horaire"
                            class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                            style="background: #111; color: white; border: 1px solid #333;">
                            <i class="fas fa-clock mr-3"></i>{{ trans('reservation.get_hourly_quote') }}
                        </a>
                    </div>

                    <!-- Contact -->
                    <div class="p-6 md:p-8" style="background: #000; border: 1px solid #333; color: white;">
                        <h3 class="text-lg md:text-xl font-bold mb-4">{{ trans('reservation.quick_contact') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-phone-alt mr-4" style="color: var(--gold);"></i>
                                <div>
                                    <p class="font-semibold">{{ trans('reservation.phone_label') }}</p>
                                    <a href="tel:0176380017" class="hover:text-yellow-400 transition duration-300">06 99 16 44 55</a>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-4" style="color: var(--gold);"></i>
                                <div>
                                    <p class="font-semibold">{{ trans('reservation.email_label') }}</p>
                                    <a href="mailto:contact@djokprestige.com"
                                        class="hover:text-yellow-400 transition duration-300">contact@djokprestige.com</a>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-4" style="color: var(--gold);"></i>
                                <div>
                                    <p class="font-semibold">{{ trans('reservation.hours_label') }}</p>
                                    <p>{{ trans('reservation.hours_value') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-4" style="color: var(--gold);"></i>
                                <div>
                                    <p class="font-semibold">{{ trans('reservation.coverage_area') }}</p>
                                    <p>{{ trans('reservation.coverage_value') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6" style="border-top: 1px solid #333;">
                            <h4 class="font-semibold mb-3">
                                {{ trans('reservation.payment_methods') }}
                            </h4>

                            <div class="flex items-center gap-4">
                                <!-- CB - Carte Bancaire -->
                                <img src="https://th.bing.com/th/id/OIP.yzt6QuqyUyZTJ28KOYmQUgHaE3?w=212&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="Carte Bancaire (CB)"
                                    class="h-8 w-auto object-contain">

                                {{-- <!-- VISA -->
                                <img src="https://www.bing.com/images/search?view=detailV2&ccid=QY6PwjWT&id=EC0C1D870641AD8AE5FF9CF870DF744B6F38D3C5&thid=OIP.QY6PwjWTVLphia6E69VB7AHaEK&mediaurl=https%3a%2f%2flogos-world.net%2fwp-content%2fuploads%2f2020%2f06%2fVisa-Logo-1992.png&cdnurl=https%3a%2f%2fth.bing.com%2fth%2fid%2fR.418e8fc2359354ba6189ae84ebd541ec%3frik%3dxdM4b0t033D4nA%26pid%3dImgRaw%26r%3d0&exph=2160&expw=3840&q=visa+logo&FORM=IRPRST&ck=5FC83BC5CEE7C51F6E487E52583112CA&selectedIndex=3&itb=1" alt="Visa"
                                    class="h-8 w-auto object-contain"> --}}

                                <!-- VISA -->
                                <img src="https://1000logos.net/wp-content/uploads/2017/06/VISA-Logo-2006.png" alt="VISA"
                                    class="h-8 w-auto object-contain">
                                <!-- MASTERCARD -->
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"
                                    class="h-8 w-auto object-contain">

                                <!-- AMERICAN EXPRESS -->
                                <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo.svg" alt="American Express"
                                    class="h-8 w-auto object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Variables globales
    let autocompleteDepart, autocompleteArrivee;
    let currentDistance = 0;
    let currentCategoryData = null;
    let currentPassengers = 1;
    let calculationTimeout = null;
    let isGoogleMapsLoaded = false;
    let currentPriceTTC = 0;

    // Fonction pour mettre à jour start_date avant soumission
    function updateStartDate() {
        const date = document.querySelector('input[name="date"]').value;
        const time = document.querySelector('input[name="heure"]').value;

        if (date && time) {
            const startDateTime = `${date} ${time}:00`;
            document.getElementById('start_date_hidden').value = startDateTime;
            console.log('start_date mis à jour:', startDateTime);
        }
    }

    function initAutocomplete() {
        console.log('Initialisation Google Maps...');

        try {
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                console.error('Google Maps API non chargée');
                return;
            }

            isGoogleMapsLoaded = true;

            // Options pour l'autocomplete (limité à la France)
            const options = {
                componentRestrictions: { country: 'fr' },
                types: ['geocode', 'establishment'],
                fields: ['formatted_address', 'geometry', 'name']
            };

            // Initialiser l'autocomplete pour le départ
            const departInput = document.getElementById('depart');
            if (departInput) {
                autocompleteDepart = new google.maps.places.Autocomplete(departInput, options);
                autocompleteDepart.addListener('place_changed', function() {
                    const place = autocompleteDepart.getPlace();
                    if (place.geometry) {
                        document.getElementById('depart_lat').value = place.geometry.location.lat();
                        document.getElementById('depart_lng').value = place.geometry.location.lng();
                        departInput.classList.add('valid');
                        triggerPriceCalculation();
                    }
                });
            }

            // Initialiser l'autocomplete pour l'arrivée
            const arriveeInput = document.getElementById('arrivee');
            if (arriveeInput) {
                autocompleteArrivee = new google.maps.places.Autocomplete(arriveeInput, options);
                autocompleteArrivee.addListener('place_changed', function() {
                    const place = autocompleteArrivee.getPlace();
                    if (place.geometry) {
                        document.getElementById('arrivee_lat').value = place.geometry.location.lat();
                        document.getElementById('arrivee_lng').value = place.geometry.location.lng();
                        arriveeInput.classList.add('valid');
                        triggerPriceCalculation();
                    }
                });
            }

            // Écouter les changements sur le sélecteur de véhicule
            const vehicleSelect = document.getElementById('vehicle_category_id');
            if (vehicleSelect) {
                vehicleSelect.addEventListener('change', function() {
                    this.classList.add('valid');
                    updateCategoryData();
                    triggerPriceCalculation();
                });
            }

            // Écouter les changements sur le nombre de passagers
            const passengersSelect = document.getElementById('passagers');
            if (passengersSelect) {
                passengersSelect.addEventListener('change', function() {
                    this.classList.add('valid');
                    currentPassengers = parseInt(this.value) || 1;
                    updatePassengersDisplay();
                });
            }

            // Écouter les changements sur date et heure
            document.getElementById('date_input').addEventListener('change', updateStartDate);
            document.getElementById('time_input').addEventListener('change', updateStartDate);

            // Écouter les changements sur les options de paiement
            document.querySelectorAll('input[name="payment_option"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'pay_now') {
                        document.getElementById('submit-quote').style.display = 'none';
                        document.getElementById('submit-payment-main').style.display = 'block';

                        // Mettre à jour le texte du bouton
                        const priceText = currentPriceTTC.toFixed(2).replace('.', ',') + ' €';
                        document.getElementById('payment-amount-main').textContent = priceText;
                    } else {
                        document.getElementById('submit-quote').style.display = 'block';
                        document.getElementById('submit-payment-main').style.display = 'none';
                    }
                });
            });

            // Initialiser les données de catégorie
            updateCategoryData();

            console.log('Google Maps Autocomplete initialisé avec succès');

        } catch (error) {
            console.error('Erreur lors de l\'initialisation de Google Maps:', error);
        }
    }

    function updateCategoryData() {
        const select = document.getElementById('vehicle_category_id');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption && selectedOption.value) {
            currentCategoryData = {
                priseCharge: parseFloat(selectedOption.getAttribute('data-prise-charge')) || 0,
                prixKm: parseFloat(selectedOption.getAttribute('data-prix-km')) || 0,
                prixMin: parseFloat(selectedOption.getAttribute('data-prix-min')) || 0
            };
        } else {
            currentCategoryData = null;
        }
    }

    function updatePassengersDisplay() {
        document.getElementById('simulation-passagers').textContent = currentPassengers;
    }

    function updatePaymentButtons() {
        const priceTTC = currentPriceTTC.toFixed(2).replace('.', ',');
        document.getElementById('pay-now-price').textContent = priceTTC + ' €';
        document.getElementById('payment-amount-main').textContent = priceTTC + ' €';
    }

    function triggerPriceCalculation() {
        // Annuler le timeout précédent s'il existe
        if (calculationTimeout) {
            clearTimeout(calculationTimeout);
        }

        // Définir un nouveau timeout pour éviter des calculs trop fréquents
        calculationTimeout = setTimeout(() => {
            if (validateFormForCalculation()) {
                calculateRoute();
            } else {
                hidePriceCalculation();
            }
        }, 800);
    }

    function validateFormForCalculation() {
        const depart = document.getElementById('depart').value;
        const arrivee = document.getElementById('arrivee').value;
        const vehicleSelect = document.getElementById('vehicle_category_id');

        return depart && depart.length > 3 &&
               arrivee && arrivee.length > 3 &&
               vehicleSelect && vehicleSelect.value;
    }

    function calculateRoute() {
        const departInput = document.getElementById('depart').value;
        const arriveeInput = document.getElementById('arrivee').value;
        const departLat = document.getElementById('depart_lat').value;
        const departLng = document.getElementById('depart_lng').value;
        const arriveeLat = document.getElementById('arrivee_lat').value;
        const arriveeLng = document.getElementById('arrivee_lng').value;

        // Afficher l'indicateur de chargement
        const loadingElement = document.getElementById('loading-distance');
        const distanceInfo = document.getElementById('distance-info');

        if (loadingElement) {
            loadingElement.style.display = 'block';
        }

        if (distanceInfo) {
            distanceInfo.style.display = 'block';
            distanceInfo.innerHTML = '<div id="loading-distance" class="loading-indicator"><i class="fas fa-spinner fa-spin mr-2"></i>{{ trans("reservation.calculating") }}</div>';
        }

        // Vérifier si Google Maps est disponible
        if (!isGoogleMapsLoaded || typeof google === 'undefined') {
            console.log('Google Maps non disponible, utilisation du calcul simplifié');
            setTimeout(() => {
                simulateDistanceCalculation(departInput, arriveeInput);
            }, 1000);
            return;
        }

        // Utiliser les coordonnées si disponibles, sinon utiliser les adresses textuelles
        let origin, destination;

        if (departLat && departLng) {
            origin = { lat: parseFloat(departLat), lng: parseFloat(departLng) };
        } else {
            origin = departInput + ', France';
        }

        if (arriveeLat && arriveeLng) {
            destination = { lat: parseFloat(arriveeLat), lng: parseFloat(arriveeLng) };
        } else {
            destination = arriveeInput + ', France';
        }

        // Initialiser le service Distance Matrix
        if (typeof google.maps.DistanceMatrixService === 'undefined') {
            simulateDistanceCalculation(departInput, arriveeInput);
            return;
        }

        const service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix({
            origins: [origin],
            destinations: [destination],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        }, function(response, status) {
            // Masquer l'indicateur de chargement
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }

            if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
                const distance = response.rows[0].elements[0].distance.value; // en mètres
                const duration = response.rows[0].elements[0].duration.text;

                currentDistance = distance;

                // Convertir en kilomètres
                const distanceKm = (distance / 1000).toFixed(1);

                // Mettre à jour l'affichage
                if (distanceInfo) {
                    distanceInfo.innerHTML = `
                        <div class="flex justify-between items-center">
                            <span style="color: white;">{{ trans("reservation.estimated_distance") }}</span>
                            <span id="distance-text" class="font-bold" style="color: var(--gold);">${distanceKm} km</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span style="color: white;">{{ trans("reservation.estimated_duration") }}</span>
                            <span id="duration-text" class="font-bold" style="color: var(--gold);">${duration}</span>
                        </div>
                    `;
                    distanceInfo.style.display = 'block';
                }

                document.getElementById('simulation-distance').textContent = distanceKm + ' km';

                // Calculer le prix
                calculatePrice();
            } else {
                console.warn('Erreur calcul d\'itinéraire:', status);

                // Essayer le calcul simplifié
                simulateDistanceCalculation(departInput, arriveeInput);
            }
        });
    }

    function simulateDistanceCalculation(depart, arrivee) {
        // Calcul simplifié basé sur des distances approximatives pour la France
        const loadingElement = document.getElementById('loading-distance');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }

        const distanceInfo = document.getElementById('distance-info');

        // Distance moyenne approximative pour un trajet en France
        const approxDistanceKm = 50; // Valeur par défaut
        const approxDuration = "1 heure";

        currentDistance = approxDistanceKm * 1000; // Convertir en mètres

        if (distanceInfo) {
            distanceInfo.innerHTML = `
                <div class="flex justify-between items-center">
                    <span style="color: white;">{{ trans("reservation.estimated_distance") }}</span>
                    <span id="distance-text" class="font-bold" style="color: var(--gold);">${approxDistanceKm} km</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span style="color: white;">{{ trans("reservation.estimated_duration") }}</span>
                    <span id="duration-text" class="font-bold" style="color: var(--gold);">${approxDuration}</span>
                </div>
                <div class="mt-2 text-xs" style="color: #888;">
                    <i class="fas fa-info-circle mr-1"></i>{{ trans("reservation.approximate_distance") }}
                </div>
            `;
            distanceInfo.style.display = 'block';
        }

        document.getElementById('simulation-distance').textContent = approxDistanceKm + ' km (approx.)';

        // Calculer le prix
        calculatePrice();
    }

    function calculatePrice() {
        if (!currentCategoryData || currentDistance === 0) {
            hidePriceCalculation();
            return;
        }

        // Convertir la distance en kilomètres
        const distanceKm = currentDistance / 1000;

        // Calcul du prix de base
        const priseCharge = currentCategoryData.priseCharge;
        const prixDistance = distanceKm * currentCategoryData.prixKm;
        let prixHTBase = priseCharge + prixDistance;

        // Appliquer le prix minimum
        if (prixHTBase < currentCategoryData.prixMin) {
            prixHTBase = currentCategoryData.prixMin;
        }

        // Le prix ne change pas en fonction du nombre de passagers
        const prixHTTotal = prixHTBase;

        // Calcul de la TVA (10%)
        const tva = prixHTTotal * 0.1;
        currentPriceTTC = prixHTTotal + tva;

        // Mettre à jour l'affichage avec un formatage approprié
        const formatPrice = (price) => {
            return price.toFixed(2).replace('.', ',') + ' €';
        };

        document.getElementById('prise-charge').textContent = formatPrice(priseCharge);
        document.getElementById('prix-distance').textContent = formatPrice(prixDistance);
        document.getElementById('prix-ht').textContent = formatPrice(prixHTBase);
        document.getElementById('prix-ttc').textContent = formatPrice(currentPriceTTC);
        document.getElementById('prix-minimum').textContent = formatPrice(currentCategoryData.prixMin);

        // Mettre à jour les boutons de paiement
        updatePaymentButtons();

        // Afficher la section de calcul
        const priceSection = document.getElementById('price-calculation');
        if (priceSection) {
            priceSection.classList.add('show');
        }

        // Ajouter les données calculées au formulaire (pour l'envoi)
        addCalculatedDataToForm(priseCharge, prixDistance, prixHTBase, prixHTTotal, tva, currentPriceTTC, distanceKm);
    }

    function addCalculatedDataToForm(priseCharge, prixDistance, prixHTBase, prixHTTotal, tva, prixTTC, distanceKm) {
        // Créer ou mettre à jour les champs cachés
        let fields = [
            { name: 'calculated_prise_charge', value: priseCharge.toFixed(2) },
            { name: 'calculated_distance_price', value: prixDistance.toFixed(2) },
            { name: 'calculated_price_ht_base', value: prixHTBase.toFixed(2) },
            { name: 'calculated_price_ht_total', value: prixHTTotal.toFixed(2) },
            { name: 'calculated_tva', value: tva.toFixed(2) },
            { name: 'calculated_price_ttc', value: prixTTC.toFixed(2) },
            { name: 'calculated_distance_km', value: distanceKm.toFixed(1) },
            { name: 'calculated_passengers', value: currentPassengers.toString() }
        ];

        const form = document.getElementById('reservation-form');
        if (!form) return;

        fields.forEach(field => {
            let input = document.querySelector(`input[name="${field.name}"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = field.name;
                form.appendChild(input);
            }
            input.value = field.value;
        });
    }

    function hidePriceCalculation() {
        const priceSection = document.getElementById('price-calculation');
        if (priceSection) {
            priceSection.classList.remove('show');
        }
    }

    // Initialisation lorsque la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page chargée, initialisation en cours...');

        // Mettre à jour start_date immédiatement
        updateStartDate();

        // Animation au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll pour les ancres
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Gérer la sélection des options de paiement
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                // Désélectionner toutes les options
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                // Sélectionner l'option cliquée
                this.classList.add('selected');

                // Cocher le radio button associé
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });

        // Set minimum date for date input
        const dateInput = document.querySelector('input[name="date"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);

            // Si aucune date n'est définie, mettre aujourd'hui par défaut
            if (!dateInput.value) {
                dateInput.value = today;
                updateStartDate();
            }
        }

        // Gérer la soumission du formulaire
        const form = document.getElementById('reservation-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Mettre à jour start_date avant soumission
                updateStartDate();

                // Vérifier l'option de paiement sélectionnée
                const selectedOption = document.querySelector('input[name="payment_option"]:checked');

                if (!selectedOption) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une option de paiement');
                    return;
                }

                // Si c'est un paiement, afficher l'indicateur de chargement
                if (selectedOption.value === 'pay_now') {
                    document.getElementById('submit-payment-main').disabled = true;
                    document.getElementById('payment-processing').style.display = 'block';
                }

                // Validation standard
                if (!validatePaymentForm()) {
                    e.preventDefault();

                    // Réactiver le bouton si validation échoue
                    if (selectedOption.value === 'pay_now') {
                        document.getElementById('submit-payment-main').disabled = false;
                        document.getElementById('payment-processing').style.display = 'none';
                    }

                    // Scroll to first error
                    const firstError = form.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
        }

        // Reset field styles on input
        if (form) {
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                    }
                    if (this.value.trim()) {
                        this.classList.add('valid');
                    }

                    // Pour les champs d'adresse, déclencher le calcul après un délai
                    if (this.id === 'depart' || this.id === 'arrivee') {
                        if (this.value.length > 5) {
                            triggerPriceCalculation();
                        }
                    }

                    // Pour date et heure, mettre à jour start_date
                    if (this.name === 'date' || this.name === 'heure') {
                        updateStartDate();
                    }
                });
            });
        }

        // Initialiser le nombre de passagers
        const passengersSelect = document.getElementById('passagers');
        if (passengersSelect && passengersSelect.value) {
            currentPassengers = parseInt(passengersSelect.value) || 1;
            updatePassengersDisplay();
        }

        // Vérifier si des valeurs existent déjà (après une erreur de soumission)
        setTimeout(() => {
            const departVal = document.getElementById('depart').value;
            const arriveeVal = document.getElementById('arrivee').value;
            const vehicleVal = document.getElementById('vehicle_category_id').value;
            const passengersVal = document.getElementById('passagers').value;

            if (departVal && arriveeVal && vehicleVal && passengersVal) {
                console.log('Valeurs existantes détectées, déclenchement du calcul...');
                triggerPriceCalculation();
            }
        }, 1500);
    });

    function validatePaymentForm() {
        let isValid = true;

        // Validation des champs requis
        const requiredFields = document.querySelectorAll('#reservation-form .required-field');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });

        // Validation spécifique pour l'email
        const emailField = document.querySelector('input[name="email"]');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                isValid = false;
                emailField.classList.add('error');
            }
        }

        // Validation de la date
        const dateField = document.querySelector('input[name="date"]');
        if (dateField && dateField.value) {
            const selectedDate = new Date(dateField.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                isValid = false;
                dateField.classList.add('error');
                alert('{{ trans("reservation.date_error") }}');
            }
        }

        return isValid;
    }
</script>
@endsection
