@extends('layouts.main')

@section('title', trans('location.title'))
@section('meta_description', trans('location.meta_description'))

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

    /* Modal styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
        animation: fadeIn 0.3s ease-out;
    }

    .modal-container {
        background: #111;
        border: 1px solid #333;
        border-radius: 8px;
        width: 100%;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .modal-overlay.active .modal-container {
        transform: translateY(0);
        opacity: 1;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .vehicle-detail-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 4px;
    }

    .default-car-image {
        width: 100%;
        height: 12rem;
        position: relative;
        overflow: hidden;
    }

    .default-car-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
</style>

<!-- Hero Section - Style sobre -->
<header class="relative min-h-screen flex items-center" style="background: #000;">
    <div class="absolute inset-0 bg-black">
        <img src="{{ asset('VAN_Mercedes.jpg') }}"
            alt="{{ trans('location.hero_title') }}" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.8);"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 py-20 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-8" style="color: var(--gold);">
                {{ trans('location.hero_title') }}
            </h1>

            <p class="text-lg md:text-xl text-gray-300 mb-12 fade-in">
                {{ trans('location.hero_description') }}
            </p>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in" style="transition-delay: 0.2s;">
                <a href="#flotte"
                    class="w-full sm:w-auto px-6 md:px-8 py-3 font-semibold text-center transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('location.view_vehicles') }}
                </a>
                <a href="#devis"
                    class="w-full sm:w-auto px-6 md:px-8 py-3 font-semibold text-center border transition duration-300"
                    style="border-color: var(--gold); color: var(--gold);">
                    {{ trans('location.request_quote') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
        <a href="#offres" class="text-white transition duration-300 hover:text-var(--gold)"
            aria-label="{{ trans('location.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Nos offres de location - Style sobre -->
<section id="offres" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('location.offers_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('location.offers_description') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" style="background: #111; border: 1px solid #333;">
                <thead style="background: #000;">
                    <tr>
                        <th class="py-4 px-4 md:px-6 text-left text-white">{{ trans('location.formula') }}</th>
                        <th class="py-4 px-4 md:px-6 text-center text-white">{{ trans('location.duration') }}</th>
                        <th class="py-4 px-4 md:px-6 text-center text-white">{{ trans('location.target_audience') }}
                        </th>
                        <th class="py-4 px-4 md:px-6 text-center text-white">{{ trans('location.included') }}</th>
                        <th class="py-4 px-4 md:px-6 text-center text-white">{{ trans('location.price_ttc') }}</th>
                        <th class="py-4 px-4 md:px-6 text-center text-white">{{ trans('location.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                    [
                    'formule' => trans('location.short_term_rental'),
                    'duree' => trans('location.short_term_duration'),
                    'public' => trans('location.short_term_audience'),
                    'inclus' => trans('location.short_term_includes'),
                    'tarif' => trans('location.short_term_price'),
                    'action' => trans('location.book')
                    ],
                    [
                    'formule' => trans('location.medium_term_rental'),
                    'duree' => trans('location.medium_term_duration'),
                    'public' => trans('location.medium_term_audience'),
                    'inclus' => trans('location.medium_term_includes'),
                    'tarif' => trans('location.medium_term_price'),
                    'action' => trans('location.book')
                    ],
                    [
                    'formule' => trans('location.long_term_rental'),
                    'duree' => trans('location.long_term_duration'),
                    'public' => trans('location.long_term_audience'),
                    'inclus' => trans('location.long_term_includes'),
                    'tarif' => trans('location.long_term_price'),
                    'action' => trans('location.book')
                    ],
                    [
                    'formule' => trans('location.driver_rental'),
                    'duree' => trans('location.driver_duration'),
                    'public' => trans('location.driver_audience'),
                    'inclus' => trans('location.driver_includes'),
                    'tarif' => trans('location.driver_price'),
                    'action' => trans('location.request_quote_small')
                    ]
                    ] as $offer)
                    <tr class="border-b" style="border-color: #333; color: white;">
                        <td class="py-4 px-4 md:px-6 font-semibold">{{ $offer['formule'] }}</td>
                        <td class="py-4 px-4 md:px-6 text-center">{{ $offer['duree'] }}</td>
                        <td class="py-4 px-4 md:px-6 text-center">{{ $offer['public'] }}</td>
                        <td class="py-4 px-4 md:px-6 text-center">{{ $offer['inclus'] }}</td>
                        <td class="py-4 px-4 md:px-6 text-center font-bold" style="color: var(--gold);">{{
                            $offer['tarif'] }}</td>
                        <td class="py-4 px-4 md:px-6 text-center">
                            @if($offer['action'] === trans('location.book'))
                            <a href="#reservation"
                                class="inline-flex items-center px-4 md:px-6 py-2 text-sm font-semibold transition-all duration-300"
                                style="background: var(--gold); color: black;">
                                {{ trans('location.book') }}
                            </a>
                            @else
                            <a href="#devis"
                                class="inline-flex items-center px-4 md:px-6 py-2 text-sm font-semibold transition-all duration-300"
                                style="border: 1px solid var(--gold); color: var(--gold);">
                                {{ trans('location.request_quote_small') }}
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#reservation"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold text-center transition duration-300"
                style="background: var(--gold); color: black;">
                {{ trans('location.book_now') }}
            </a>
            <a href="#simulation"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold text-center border transition duration-300"
                style="border-color: var(--gold); color: var(--gold);">
                {{ trans('location.get_simulation') }}
            </a>
        </div>
    </div>
</section>

<!-- Notre flotte de véhicules premium - Version DYNAMIQUE -->
<section id="flotte" class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">
                {{ trans('location.fleet_title') }}
            </h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('location.fleet_description') }}
            </p>
        </div>

        @forelse($allCategories as $category)
        @php
        $categoryData = $vehiclesByCategory[$category->categorie] ?? null;
        $vehicles = $categoryData['vehicles'] ?? collect();
        @endphp

        <div class="mb-16">
            <!-- Titre et description dynamiques -->
            <h3 class="mb-8 text-2xl md:text-3xl font-semibold text-center" style="color: white;">
                {{ $category->display_name }}
            </h3>

            @if($category->description ?? false)
            <p class="text-center text-gray-400 mb-8 max-w-2xl mx-auto">
                {{ $category->description }}
            </p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($vehicles as $vehicle)
                <div class="p-4 md:p-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <div class="w-full h-48 md:h-56 overflow-hidden rounded mb-4">
                        @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->full_name }}"
                            class="w-full h-full object-cover">
                        @else
                        <!-- Image par défaut selon la catégorie (optionnel) -->
                        <div class="default-car-image">
                            <img src="{{ $category->default_image ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80' }}"
                                alt="{{ $category->display_name }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-lg md:text-xl font-bold mb-2" style="color: white;">
                            {{ $vehicle->full_name }}
                        </h4>
                        <p class="text-gray-400 mb-3 text-sm">
                            {{ $vehicle->category_fr }} • {{ $vehicle->fuel_type_fr }}
                        </p>

                        <div class="mb-4">
                            <span class="text-xl md:text-2xl font-bold" style="color: var(--gold);">
                                {{ $vehicle->weekly_rate_formatted }}
                            </span>
                            <span class="text-gray-500">{{ trans('location.weekly') }}</span>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="selectVehicle({{ $vehicle->id }}, '{{ addslashes($vehicle->full_name) }}')"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 md:py-3 font-semibold transition-all duration-300"
                                style="background: var(--gold); color: black;">
                                {{ trans('location.select') }}
                            </button>
                            <a href="{{ route('vehicle.details', $vehicle->id) }}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 md:py-3 font-semibold transition-all duration-300"
                                style="background: #111; color: white; border: 1px solid #333;">
                                {{ trans('location.details') }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                        style="background: #1a1a1a; border: 1px solid #333;">
                        <i class="fas fa-car" style="color: #666; font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="text-lg font-medium mb-2" style="color: white;">
                        {{ trans('location.no_vehicles') }}
                    </h4>
                    <p class="text-gray-400">
                        {{ trans('location.no_vehicles_category', ['category' => $category->display_name]) }}
                    </p>
                </div>
                @endforelse
            </div>
        </div>

        @empty
        <div class="text-center py-20">
            <p class="text-gray-400 text-lg">
                {{ trans('location.no_categories') }}
            </p>
        </div>
        @endforelse
    </div>
</section>

<!-- Services inclus - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('location.services_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('location.services_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach([
            [trans('location.full_insurance'), 'fas fa-shield-alt'],
            [trans('location.maintenance'), 'fas fa-tools'],
            [trans('location.assistance'), 'fas fa-headset'],
            [trans('location.replacement_vehicle'), 'fas fa-car'],
            [trans('location.cleaning'), 'fas fa-spray-can'],
            [trans('location.options'), 'fas fa-cogs']
            ] as $service)
            <div class="p-4 md:p-6" style="background: #111; border: 1px solid #333;">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded"
                            style="background: #1a1a1a;">
                            <i class="{{ $service[1] }}" style="color: var(--gold);"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold" style="color: white;">{{ $service[0] }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="#reservation"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition duration-300"
                style="background: var(--gold); color: black;">
                {{ trans('location.check_availability') }}
            </a>
        </div>
    </div>
</section>

<!-- Offres spéciales chauffeurs VTC - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('location.special_offers_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('location.special_offers_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <div class="p-6 md:p-8" style="background: #1a1a1a; border: 1px solid #333;">
                <h3 class="text-xl md:text-2xl font-bold mb-4" style="color: white;">{{
                    trans('location.start_pro_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ trans('location.start_pro_description') }}
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.start_pro_point1') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.start_pro_point2') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.start_pro_point3') }}</span>
                    </li>
                </ul>
                <a href="#devis"
                    class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('location.start_pro_button') }}
                </a>
            </div>

            <div class="p-6 md:p-8" style="background: #1a1a1a; border: 1px solid #333;">
                <h3 class="text-xl md:text-2xl font-bold mb-4" style="color: white;">{{ trans('location.full_vtc_title')
                    }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ trans('location.full_vtc_description') }}
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.full_vtc_point1') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.full_vtc_point2') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: white;">{{ trans('location.full_vtc_point3') }}</span>
                    </li>
                </ul>
                <a href="#devis"
                    class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('location.full_vtc_button') }}
                </a>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="#devis" class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition duration-300"
                style="background: var(--gold); color: black;">
                {{ trans('location.driver_quote') }}
            </a>
        </div>
    </div>
</section>

<!-- Conditions de location - Style sobre -->
<section id="conditions" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-12">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-6" style="color: var(--gold);">{{
                    trans('location.conditions_title') }}</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg md:text-xl font-semibold mb-3" style="color: white;">{{
                            trans('location.requirements_title') }}</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-file-alt text-sm mt-1 mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.id_card') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-id-card text-sm mt-1 mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.license') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-home text-sm mt-1 mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.address_proof') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-badge-check text-sm mt-1 mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.vtc_card') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-euro-sign text-sm mt-1 mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.deposit') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-6" style="color: var(--gold);">{{
                    trans('location.payment_title') }}</h2>
                <div class="space-y-6">
                    <div class="p-4 md:p-6 rounded" style="background: #111; border: 1px solid #333;">
                        <h3 class="text-lg font-semibold mb-3" style="color: white;">{{
                            trans('location.payment_methods_title') }}</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-credit-card mr-3" style="color: #60a5fa;"></i>
                                <span style="color: white;">{{ trans('location.credit_card') }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-university mr-3" style="color: #60a5fa;"></i>
                                <span style="color: white;">{{ trans('location.bank_transfer') }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-3" style="color: #60a5fa;"></i>
                                <span style="color: white;">{{ trans('location.cash') }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 md:p-6 rounded" style="background: #1a1a1a; border: 1px solid #333;">
                        <h3 class="text-lg font-semibold mb-3" style="color: white;">{{
                            trans('location.payment_schedule_title') }}</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-percentage mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.deposit_amount') }}</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-calendar-alt mr-3" style="color: var(--gold);"></i>
                                <span style="color: white;">{{ trans('location.installments') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulaire de réservation - Style sobre -->
<section id="reservation" class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                    trans('location.booking_title') }}</h2>
                <p class="text-gray-400">
                    {{ trans('location.booking_description') }}
                </p>
            </div>

            <!-- Messages d'erreur/succès -->
            @if(session('error'))
            <div class="mb-6 p-4 rounded fade-in" style="background: #7f1d1d; border: 1px solid #991b1b;">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle" style="color: #fca5a5;"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 rounded fade-in" style="background: #064e3b; border: 1px solid #047857;">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle" style="color: #a7f3d0;"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="p-6 md:p-8" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                    <!-- Colonne gauche : Formulaire -->
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold mb-4" style="color: white;">{{
                            trans('location.booking_form_title') }}</h3>
                        <p class="text-gray-400 mb-6">{{ trans('location.booking_form_description') }}</p>

                        <form action="{{ route('location.reservation.store') }}" method="POST" id="reservationForm">
                            @csrf

                            <!-- Véhicule sélectionné (caché) -->
                            <input type="hidden" name="vehicle_id" id="selected_vehicle_id"
                                value="{{ old('vehicle_id') }}">

                            <div class="space-y-4">
                                <!-- Champ Nom complet -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.full_name') }}</label>
                                    <input type="text" name="nom" required
                                        class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        value="{{ old('nom') }}">
                                    @error('nom')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champ Email -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.email') }}</label>
                                    <input type="email" name="email" required
                                        class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        value="{{ old('email') }}">
                                    @error('email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champ Téléphone -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.phone') }}</label>
                                    <input type="tel" name="telephone" required
                                        class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        value="{{ old('telephone') }}">
                                    @error('telephone')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champ Type de véhicule (affiché en lecture seule) -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.selected_vehicle') }}</label>
                                    <input type="text" id="vehicle_display" readonly required
                                        class="w-full px-4 py-3 rounded focus:outline-none cursor-not-allowed"
                                        style="background: #0a0a0a; border: 1px solid #333; color: #888;"
                                        value="{{ old('vehicle_model', trans('location.vehicle_placeholder')) }}">
                                    <p class="text-xs text-gray-500 mt-1">{{ trans('location.select_vehicle_hint') }}
                                    </p>
                                    @error('vehicle_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CHAMP DATE DE DÉBUT DE LOCATION -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.start_date') }}</label>
                                    <input type="date" name="date_debut" id="date_debut" required
                                        min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        value="{{ old('date_debut') }}">
                                    <p class="text-xs text-gray-500 mt-1">{{ trans('location.min_date_hint') }}</p>
                                    @error('date_debut')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CHAMP DATE DE FIN DE LOCATION -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.end_date') }}</label>
                                    <input type="date" name="date_fin" id="date_fin" required
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        value="{{ old('date_fin') }}">
                                    <p class="text-xs text-gray-500 mt-1">{{ trans('location.end_date_hint') }}</p>
                                    @error('date_fin')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Message supplémentaire -->
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        trans('location.message_optional') }}</label>
                                    <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded focus:outline-none"
                                        style="background: #111; border: 1px solid #444; color: white;"
                                        placeholder="{{ trans('location.message_placeholder') }}">{{ old('notes') }}</textarea>
                                </div>

                                <!-- Vérification de disponibilité -->
                                <div id="availability_check" class="hidden p-4 rounded"
                                    style="background: #111; border: 1px solid #444;">
                                    <div id="availability_result"></div>
                                </div>

                                <!-- Estimation de prix -->
                                <div id="price_estimation" class="hidden p-4 rounded"
                                    style="background: #1a1a1a; border: 1px solid #333;">
                                    <h4 class="font-bold mb-2" style="color: var(--gold);">{{
                                        trans('location.price_estimation') }}</h4>
                                    <div id="price_result"></div>
                                </div>

                                <!-- CGV -->
                                <div>
                                    <div class="flex items-start">
                                        <input type="checkbox" name="terms" id="terms" required
                                            class="mt-1 mr-3 h-5 w-5 rounded focus:outline-none"
                                            style="background: #111; border: 1px solid #444;">
                                        <label for="terms" class="text-gray-300 text-sm">
                                            {!! trans('location.accept_terms', [
                                            'conditions' => '<a href="' . route('cgv') . '" style="color: var(--gold);"
                                                class="hover:text-yellow-400">' . trans('location.terms_link') . '</a>',
                                            'privacy' => '<a href="' . route('rgpd') . '" style="color: var(--gold);"
                                                class="hover:text-yellow-400">' . trans('location.privacy_link') .
                                                '</a>'
                                            ]) !!}
                                        </label>
                                    </div>
                                    @error('terms')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bouton de soumission -->
                                <button type="submit" id="submit_btn"
                                    class="w-full mt-6 inline-flex items-center justify-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                                    style="background: var(--gold); color: black;">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    {{ trans('location.submit_booking') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Colonne droite : Contact et devis -->
                    <div id="devis">
                        <h3 class="text-xl md:text-2xl font-bold mb-4" style="color: white;">{{
                            trans('location.quote_title') }}</h3>
                        <p class="text-gray-400 mb-6">{{ trans('location.quote_description') }}</p>

                        <div class="space-y-6">
                            <div class="p-4 rounded" style="background: #111; border: 1px solid #333;">
                                <h4 class="font-bold mb-2" style="color: white;">{{ trans('location.quick_contact') }}
                                </h4>
                                <p class="text-gray-300">
                                    <i class="fas fa-phone-alt mr-2" style="color: var(--gold);"></i>
                                    {{ trans('location.phone_label') }}
                                    <a href="tel:06 99 16 44 55"
                                        class="font-semibold hover:text-yellow-400 transition duration-300"
                                        style="color: var(--gold);">06 99 16 44 55</a>
                                </p>
                                <p class="text-gray-300 mt-2">
                                    <i class="fas fa-envelope mr-2" style="color: var(--gold);"></i>
                                    {{ trans('location.email_label') }}
                                    <a href="mailto:contact@djokprestige.com"
                                        class="font-semibold hover:text-yellow-400 transition duration-300"
                                        style="color: var(--gold);">contact@djokprestige.com</a>
                                </p>
                            </div>

                            <div class="p-4 rounded" style="background: #111; border: 1px solid #333;">
                                <h4 class="font-bold mb-2" style="color: white;">{{
                                    trans('location.opening_hours_title') }}</h4>
                                <p class="text-gray-300">
                                    <i class="fas fa-clock mr-2" style="color: var(--gold);"></i>
                                    {{ trans('location.weekdays_hours') }}
                                </p>
                                <p class="text-gray-300 mt-2">
                                    <i class="fas fa-clock mr-2" style="color: var(--gold);"></i>
                                    {{ trans('location.saturday_hours') }}
                                </p>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('contact') }}"
                                    class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                                    style="background: #111; color: white; border: 1px solid #333;">
                                    <i class="fas fa-file-invoice-dollar mr-3"></i>{{
                                    trans('location.personalized_quote') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Véhicules récemment ajoutés - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('location.discover_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ trans('location.discover_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @php
            // Récupérer 4 véhicules au hasard pour montrer la variété
            $randomVehicles = collect([]);

            // Récupérer des véhicules de chaque catégorie
            if($ecoVehicles->count() > 0) {
            $randomVehicles = $randomVehicles->merge($ecoVehicles->random(min(1, $ecoVehicles->count())));
            }
            if($businessVehicles->count() > 0) {
            $randomVehicles = $randomVehicles->merge($businessVehicles->random(min(1, $businessVehicles->count())));
            }
            if($prestigeVehicles->count() > 0) {
            $randomVehicles = $randomVehicles->merge($prestigeVehicles->random(min(1, $prestigeVehicles->count())));
            }

            // Compléter avec d'autres véhicules si nécessaire
            $allVehicles = collect([]);
            if($ecoVehicles->count() > 0) $allVehicles = $allVehicles->merge($ecoVehicles);
            if($businessVehicles->count() > 0) $allVehicles = $allVehicles->merge($businessVehicles);
            if($prestigeVehicles->count() > 0) $allVehicles = $allVehicles->merge($prestigeVehicles);

            // Si on n'a pas assez de véhicules, compléter avec ceux déjà sélectionnés
            $vehicleIds = $randomVehicles->pluck('id')->toArray();
            $needed = 4 - $randomVehicles->count();

            if ($needed > 0 && $allVehicles->count() > $randomVehicles->count()) {
            $additionalVehicles = $allVehicles
            ->whereNotIn('id', $vehicleIds)
            ->take($needed);
            $randomVehicles = $randomVehicles->merge($additionalVehicles);
            }
            @endphp

            @foreach($randomVehicles->take(4) as $vehicle)
            <div class="p-4" style="background: #111; border: 1px solid #333;">
                <div class="mb-4">
                    <div class="w-full h-32 md:h-40 overflow-hidden rounded">
                        @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->full_name }}"
                            class="w-full h-full object-cover">
                        @else
                        <div class="default-car-image h-32 md:h-40">
                            <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=200&q=80"
                                alt="{{ $vehicle->full_name }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                    </div>
                </div>
                <h4 class="font-bold mb-2" style="color: white;">{{ $vehicle->full_name }}</h4>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold"
                        style="background: #1a1a1a; color: var(--gold);">
                        {{ $vehicle->category_fr }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $vehicle->fuel_type_fr }}</span>
                </div>
                <a href="{{ route('vehicle.details', $vehicle->id) }}"
                    class="block text-center w-full px-4 py-2 font-semibold transition-all duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('location.view_details') }}
                </a>
            </div>
            @endforeach

            @if($randomVehicles->count() == 0)
            <div class="col-span-4 text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                    style="background: #1a1a1a; border: 1px solid #333;">
                    <i class="fas fa-car" style="color: #666; font-size: 1.5rem;"></i>
                </div>
                <h4 class="text-lg font-medium mb-2" style="color: white;">{{ trans('location.no_vehicles') }}</h4>
                <p class="text-gray-400">{{ trans('location.loading') }}</p>
            </div>
            @endif
        </div>

        <div class="text-center mt-8">
            @php
            $totalCount = $allVehicles->count();
            @endphp
            @if($totalCount > 0)
            <p class="text-gray-400 mb-4">
                @php
                $plural = $totalCount > 1 ? 's' : '';
                @endphp
                {{ trans('location.vehicles_available', [
                'count' => $totalCount,
                'plural' => $plural
                ]) }}
            </p>
            @endif

            <a href="#flotte"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                style="background: #111; color: white; border: 1px solid #333;">
                <i class="fas fa-car mr-2"></i>
                {{ trans('location.see_all_fleet') }}
            </a>
        </div>
    </div>
</section>

<!-- CTA Final - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-6" style="color: var(--gold);">{{ trans('location.cta_title') }}
        </h2>
        <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
            {{ trans('location.cta_description') }}
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#flotte"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                style="background: var(--gold); color: black;">
                <i class="fas fa-car mr-3"></i>
                {{ trans('location.explore_fleet') }}
            </a>
            <a href="tel:0176380017"
                class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                style="background: #1a1a1a; color: white; border: 1px solid #333;">
                <i class="fas fa-phone mr-3"></i>
                {{ trans('location.call_us', ['phone' => '06 99 16 44 55']) }}
            </a>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
            @foreach([
            [trans('location.feature_insurance'), 'fas fa-shield-alt', trans('location.feature_insurance_desc')],
            [trans('location.feature_maintenance'), 'fas fa-tools', trans('location.feature_maintenance_desc')],
            [trans('location.feature_flexibility'), 'fas fa-calendar-check', trans('location.feature_flexibility_desc')]
            ] as $feature)
            <div class="p-4 rounded" style="background: #1a1a1a; border: 1px solid #333;">
                <i class="{{ $feature[1] }} mb-3" style="color: var(--gold); font-size: 1.5rem;"></i>
                <h4 class="font-bold mb-2" style="color: white;">{{ $feature[0] }}</h4>
                <p class="text-sm" style="color: #999;">{{ $feature[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                const href = this.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Gestion des dates
        const dateDebutInput = document.querySelector('#date_debut');
        const dateFinInput = document.querySelector('#date_fin');
        const vehicleIdInput = document.querySelector('#selected_vehicle_id');
        const availabilityCheck = document.querySelector('#availability_check');
        const availabilityResult = document.querySelector('#availability_result');
        const priceEstimation = document.querySelector('#price_estimation');
        const priceResult = document.querySelector('#price_result');
        const submitBtn = document.querySelector('#submit_btn');

        if (dateDebutInput && dateFinInput && vehicleIdInput) {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            // Formater les dates au format YYYY-MM-DD
            const formattedToday = today.toISOString().split('T')[0];
            const formattedTomorrow = tomorrow.toISOString().split('T')[0];

            // Définir les dates minimales
            dateDebutInput.min = formattedToday;
            dateFinInput.min = formattedTomorrow;

            // Mettre à jour la date minimale de fin lorsque la date de début change
            dateDebutInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const nextDay = new Date(selectedDate);
                nextDay.setDate(nextDay.getDate() + 1);

                const formattedNextDay = nextDay.toISOString().split('T')[0];
                dateFinInput.min = formattedNextDay;

                // Si la date de fin est antérieure à la nouvelle date minimale, réinitialiser
                if (dateFinInput.value && dateFinInput.value < formattedNextDay) {
                    dateFinInput.value = formattedNextDay;
                }

                checkAvailabilityAndPrice();
            });

            // Validation : date de fin doit être postérieure à date de début
            dateFinInput.addEventListener('change', function() {
                if (dateDebutInput.value && this.value) {
                    const dateDebut = new Date(dateDebutInput.value);
                    const dateFin = new Date(this.value);

                    if (dateFin <= dateDebut) {
                        alert("{{ trans('location.date_error') }}");
                        const nextDay = new Date(dateDebut);
                        nextDay.setDate(nextDay.getDate() + 1);
                        this.value = nextDay.toISOString().split('T')[0];
                    }
                }

                checkAvailabilityAndPrice();
            });

            // Vérifier la disponibilité et calculer le prix
            function checkAvailabilityAndPrice() {
                const vehicleId = vehicleIdInput.value;
                const dateDebut = dateDebutInput.value;
                const dateFin = dateFinInput.value;

                if (!vehicleId || !dateDebut || !dateFin) {
                    availabilityCheck.classList.add('hidden');
                    priceEstimation.classList.add('hidden');
                    return;
                }

                // Vérifier que la date de fin est après la date de début
                if (new Date(dateFin) <= new Date(dateDebut)) {
                    return;
                }

                // Afficher le loader
                availabilityResult.innerHTML = `
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-600 mr-2"></div>
                        <span class="text-white">{{ trans('location.checking_availability') }}</span>
                    </div>
                `;
                availabilityCheck.classList.remove('hidden');

                // Envoyer la requête AJAX
                fetch('{{ route("location.reservation.check.availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        date_debut: dateDebut,
                        date_fin: dateFin
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        availabilityResult.innerHTML = `
                            <div class="text-green-400">
                                <i class="fas fa-check-circle mr-2"></i>
                                ${data.message}
                            </div>
                        `;

                        // Déterminer le mot jour/jours en fonction de la durée
                        const dayWord = data.duree_jours > 1 ? '{{ trans('location.days') }}' : '{{ trans('location.day') }}';

                        // Afficher l'estimation de prix
                        priceResult.innerHTML = `
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-300">{{ trans('location.duration_days') }}</span>
                                    <span class="font-semibold text-white">${data.duree_jours} ${dayWord}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">{{ trans('location.rate_type') }}</span>
                                    <span class="font-semibold text-white">${data.tarif_type}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">{{ trans('location.estimated_amount') }}</span>
                                    <span class="font-bold text-lg" style="color: var(--gold);">${parseFloat(data.montant_estime).toFixed(2).replace('.', ',')} €</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ trans('location.price_indication') }}
                                </div>
                            </div>
                        `;
                        priceEstimation.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        availabilityResult.innerHTML = `
                            <div class="text-red-400">
                                <i class="fas fa-times-circle mr-2"></i>
                                ${data.message}
                            </div>
                        `;
                        priceEstimation.classList.add('hidden');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    availabilityResult.innerHTML = `
                        <div class="text-red-400">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ trans('location.availability_error') }}
                        </div>
                    `;
                    priceEstimation.classList.add('hidden');
                });
            }

            // Vérifier la disponibilité lorsque le véhicule change
            vehicleIdInput.addEventListener('change', checkAvailabilityAndPrice);
        }
    });

    // Fonction pour sélectionner un véhicule
    function selectVehicle(vehicleId, vehicleName) {
        document.getElementById('selected_vehicle_id').value = vehicleId;
        document.getElementById('vehicle_display').value = vehicleName;

        // Scroll vers le formulaire
        document.getElementById('reservation').scrollIntoView({ behavior: 'smooth' });

        // Vérifier la disponibilité si les dates sont déjà remplies
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;

        if (dateDebut && dateFin) {
            setTimeout(() => {
                // Appeler la fonction checkAvailabilityAndPrice si elle existe
                if (typeof checkAvailabilityAndPrice === 'function') {
                    checkAvailabilityAndPrice();
                }
            }, 500);
        }
    }
</script>
@endsection
