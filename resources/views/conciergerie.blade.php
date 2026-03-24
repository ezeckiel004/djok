@extends('layouts.main')

@section('title', __('conciergerie.title'))

@section('content')
<!-- Message de succès - Style sobre -->
@if(session('success'))
<div id="success-alert" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-2xl">
    <div class="mx-4 p-4" style="background: #064e3b; border-left: 4px solid #10b981;">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" style="background: #047857;">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-white">{{ __('conciergerie.success_title') }}</h3>
                    <div class="mt-1 text-green-100">
                        <p>{{ session('success') }}</p>
                        @if(session('email'))
                        <p class="text-sm mt-1">
                            {!! __('conciergerie.confirmation_email', ['email' => session('email')]) !!}
                        </p>
                        @endif
                        <p class="text-sm mt-1">
                            {{ __('conciergerie.contact_soon') }}
                        </p>
                    </div>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove();"
                class="text-green-300 hover:text-white" aria-label="{{ __('conciergerie.close_alert') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }
    }, 10000);
</script>
@endif

<!-- Hero Section - Style sobre -->
<header class="relative min-h-screen flex items-center" style="background: #000;">
    <div class="absolute inset-0 bg-black">
        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
            alt="{{ __('conciergerie.hero_title') }}" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.7);"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 py-20 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-8" style="color: var(--gold);">
                {{ __('conciergerie.hero_title') }}
            </h1>

            <p class="text-lg md:text-xl text-gray-300 mb-12">
                {{ __('conciergerie.hero_description') }}
            </p>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#devis" class="w-full sm:w-auto px-8 py-3 font-semibold text-center transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ __('conciergerie.request_quote') }}
                </a>
                <a href="#contact"
                    class="w-full sm:w-auto px-8 py-3 font-semibold text-center border transition duration-300"
                    style="border-color: var(--gold); color: var(--gold);">
                    {{ __('conciergerie.call_back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
        <a href="#services" class="text-white transition duration-300 hover:text-var(--gold)"
            aria-label="{{ __('conciergerie.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Nos services - Style sobre -->
<section id="services" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.services_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
            ['service_transfers', 'fas fa-plane-arrival', 'service_transfers_desc'],
            ['service_car_rental', 'fas fa-car', 'service_car_rental_desc'],
            ['service_accommodation', 'fas fa-hotel', 'service_accommodation_desc'],
            ['service_guide', 'fas fa-map-marked-alt', 'service_guide_desc'],
            ['service_concierge', 'fas fa-concierge-bell', 'service_concierge_desc'],
            ['service_assistance', 'fas fa-hands-helping', 'service_assistance_desc'],
            ['service_business', 'fas fa-briefcase', 'service_business_desc'],
            ['service_vip', 'fas fa-crown', 'service_vip_desc']
            ] as $service)
            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 flex items-center justify-center rounded-lg"
                            style="background: var(--gold);">
                            <i class="{{ $service[1] }} text-black"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $service[0]) }}
                        </h4>
                        <p class="text-gray-400">{{ __('conciergerie.' . $service[2]) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Packs conciergerie - Style sobre -->
<section id="packs" class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.packs_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ __('conciergerie.packs_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
            ['pack_essential', 'pack_essential_desc', 'pack_essential_price', 'pack_essential_items'],
            ['pack_training', 'pack_training_desc', 'pack_training_price', 'pack_training_items'],
            ['pack_business', 'pack_business_desc', 'pack_business_price', 'pack_business_items'],
            ['pack_family', 'pack_family_desc', 'pack_family_price', 'pack_family_items']
            ] as $pack)
            <div class="p-6" style="background: #1a1a1a; border: 1px solid #333;">
                <h3 class="text-xl font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $pack[0]) }}</h3>
                <p class="text-gray-400 mb-4">{{ __('conciergerie.' . $pack[1]) }}</p>
                <ul class="space-y-2 mb-6">
                    @foreach(__('conciergerie.' . $pack[3]) as $item)
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: #ddd;">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="text-2xl font-bold mb-4" style="color: var(--gold);">{{ __('conciergerie.' . $pack[2]) }}
                </div>
                <a href="#devis"
                    class="inline-flex items-center justify-center w-full px-4 py-3 font-semibold rounded transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ __('conciergerie.choose_pack') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tableau des tarifs prévisionnels - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.pricing_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ __('conciergerie.pricing_description') }}
            </p>
            <div class="inline-flex items-center justify-center gap-3 mt-6 p-3 rounded"
                style="background: rgba(var(--gold-rgb), 0.1);">
                <i class="fas fa-info-circle" style="color: var(--gold);"></i>
                <span class="font-medium" style="color: var(--gold);">{{ __('conciergerie.indicative_prices') }}</span>
            </div>
        </div>

        <div class="overflow-x-auto rounded" style="background: #111; border: 1px solid #333;">
            <table class="w-full">
                <thead style="background: #1a1a1a;">
                    <tr>
                        <th class="py-4 px-6 text-left font-semibold" style="color: var(--gold);">{{
                            __('conciergerie.service') }}</th>
                        <th class="py-4 px-6 text-left font-semibold" style="color: var(--gold);">{{
                            __('conciergerie.description') }}</th>
                        <th class="py-4 px-6 text-center font-semibold" style="color: var(--gold);">{{
                            __('conciergerie.price') }}</th>
                        <th class="py-4 px-6 text-center font-semibold" style="color: var(--gold);">{{
                            __('conciergerie.period') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                    ['transfer_airport', 'transfer_airport_desc', 'transfer_airport_price', 'per_trip'],
                    ['car_eco', 'car_eco_desc', 'car_eco_price', 'per_day'],
                    ['car_prestige', 'car_prestige_desc', 'car_prestige_price', 'per_day'],
                    ['private_driver', 'private_driver_desc', 'private_driver_price', 'per_day'],
                    ['hotel_3', 'hotel_3_desc', 'hotel_3_price', 'per_night'],
                    ['apartment_studio', 'apartment_studio_desc', 'apartment_studio_price', 'per_month'],
                    ['guide_paris', 'guide_paris_desc', 'guide_paris_price', 'per_visit'],
                    ['pack_starter', 'pack_starter_desc', 'pack_starter_price', 'one_time'],
                    ['admin_assistance', 'admin_assistance_desc', 'admin_assistance_price', 'per_hour'],
                    ['interpreter', 'interpreter_desc', 'interpreter_price', 'per_hour']
                    ] as $row)
                    <tr class="border-t" style="border-color: #333;">
                        <td class="py-4 px-6 font-semibold" style="color: white;">{{ __('conciergerie.' . $row[0]) }}
                        </td>
                        <td class="py-4 px-6" style="color: #aaa;">{{ __('conciergerie.' . $row[1]) }}</td>
                        <td class="py-4 px-6 text-center font-bold" style="color: var(--gold);">{{ __('conciergerie.' .
                            $row[2]) }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm"
                                style="background: #222; color: #ccc;">
                                {{ __('conciergerie.' . $row[3]) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Informations importantes -->
        <div class="mt-8 p-6 rounded" style="background: #111; border: 1px solid #333;">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 flex items-center justify-center rounded"
                        style="background: rgba(var(--gold-rgb), 0.2);">
                        <i class="fas fa-exclamation-triangle" style="color: var(--gold);"></i>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-2" style="color: white;">{{ __('conciergerie.important_info') }}</h4>
                    <p class="text-gray-400">
                        {{ __('conciergerie.pricing_note') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cartes informatives -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
            ['no_hidden_fees', 'fas fa-percentage', 'no_hidden_fees_desc'],
            ['price_guarantee', 'fas fa-handshake', 'price_guarantee_desc'],
            ['quote_24h', 'fas fa-clock', 'quote_24h_desc']
            ] as $card)
            <div class="p-6 rounded" style="background: #111; border: 1px solid #333;">
                <div class="w-12 h-12 flex items-center justify-center rounded mb-4"
                    style="background: rgba(var(--gold-rgb), 0.1);">
                    <i class="{{ $card[1] }}" style="color: var(--gold);"></i>
                </div>
                <h4 class="font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $card[0]) }}</h4>
                <p class="text-gray-400">{{ __('conciergerie.' . $card[2]) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Location de voiture - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.car_rental_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach([
            ['eco_category', 'eco_models', 'eco_price'],
            ['business_category', 'business_models', 'business_price'],
            ['prestige_category', 'prestige_models', 'prestige_price'],
            ['van_category', 'van_models', 'van_price']
            ] as $vehicle)
            <div class="p-6 text-center" style="background: #1a1a1a; border: 1px solid #333;">
                <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $vehicle[0]) }}</h3>
                <p class="text-gray-400 mb-4">{{ __('conciergerie.' . $vehicle[1]) }}</p>
                <div class="text-xl font-bold mb-6" style="color: var(--gold);">{{ __('conciergerie.' . $vehicle[2]) }}
                </div>
                <a href="{{ route('location') }}"
                    class="inline-flex items-center justify-center w-full px-4 py-3 font-semibold rounded transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ __('conciergerie.book_now') }}
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-8 p-6 rounded" style="background: #1a1a1a; border: 1px solid #333;">
            <h3 class="text-lg font-bold mb-4" style="color: white;">{{ __('conciergerie.options_title') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                ['option_gps', 'fas fa-map-marker-alt'],
                ['option_baby_seat', 'fas fa-baby'],
                ['option_additional_driver', 'fas fa-user-plus'],
                ['option_charging', 'fas fa-charging-station']
                ] as $option)
                <div class="flex items-center">
                    <i class="{{ $option[1] }} mr-3" style="color: var(--gold);"></i>
                    <span style="color: #ddd;">{{ __('conciergerie.' . $option[0]) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Hébergements - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.accommodation_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ __('conciergerie.accommodation_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach([
            ['hotel_paris', 'hotel_paris_desc', 'hotel_paris_price'],
            ['apartment_studio_paris', 'apartment_studio_paris_desc', 'apartment_studio_paris_price'],
            ['apartment_2rooms', 'apartment_2rooms_desc', 'apartment_2rooms_price']
            ] as $hebergement)
            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $hebergement[0]) }}
                </h3>
                <p class="text-gray-400 mb-4">{{ __('conciergerie.' . $hebergement[1]) }}</p>
                <div class="text-xl font-bold" style="color: var(--gold);">{{ __('conciergerie.' . $hebergement[2]) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Guides & expériences - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.guides_title') }}</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ __('conciergerie.guides_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
            ['experience_paris', 'experience_paris_desc', 'experience_paris_price'],
            ['experience_versailles', 'experience_versailles_desc', 'experience_versailles_price'],
            ['experience_loire', 'experience_loire_desc', 'experience_loire_price'],
            ['experience_bordeaux', 'experience_bordeaux_desc', 'experience_bordeaux_price'],
            ['experience_cotedazur', 'experience_cotedazur_desc', 'experience_cotedazur_price']
            ] as $experience)
            <div class="p-6" style="background: #1a1a1a; border: 1px solid #333;">
                <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.' . $experience[0]) }}</h3>
                <p class="text-gray-400 mb-4">{{ __('conciergerie.' . $experience[1]) }}</p>
                <div class="text-xl font-bold" style="color: var(--gold);">{{ __('conciergerie.' . $experience[2]) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Packs installation - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                __('conciergerie.installation_packs_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
            ['pack_starter_name', 'pack_starter_price', 'pack_starter_items'],
            ['pack_study_name', 'pack_study_price', 'pack_study_items'],
            ['pack_pro_name', 'pack_pro_price', 'pack_pro_items']
            ] as $pack)
            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <h3 class="text-xl font-bold mb-4" style="color: white;">{{ __('conciergerie.' . $pack[0]) }}</h3>
                <ul class="space-y-3 mb-6">
                    @foreach(__('conciergerie.' . $pack[2]) as $item)
                    <li class="flex items-start">
                        <i class="fas fa-check mt-1 mr-3" style="color: var(--gold);"></i>
                        <span style="color: #ddd;">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="text-2xl font-bold" style="color: var(--gold);">{{ __('conciergerie.' . $pack[1]) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Formulaire devis - Style sobre -->
<section id="devis" class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-4xl mx-auto p-6 md:p-8" style="background: #1a1a1a; border: 1px solid #333;">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8" style="color: var(--gold);">{{
                __('conciergerie.quote_title') }}</h2>

            @if(session('success'))
            <div class="p-4 mb-6" style="background: #064e3b; border: 1px solid #047857;">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                        style="background: #047857;">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">{{ __('conciergerie.request_sent') }}</h4>
                        <p class="text-green-100">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                        style="background: #dc2626;">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">{{ __('conciergerie.error_correction') }}</h4>
                        <ul class="text-red-100 list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('conciergerie.store') }}" method="POST" id="conciergerieForm">
                @csrf

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('conciergerie.full_name')
                                }}</label>
                            <input type="text" name="nom" required
                                class="w-full px-4 py-3 rounded @error('nom') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;"
                                placeholder="{{ __('conciergerie.full_name_placeholder') }}" value="{{ old('nom') }}">
                            @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('conciergerie.email')
                                }}</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 rounded @error('email') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;"
                                placeholder="{{ __('conciergerie.email_placeholder') }}" value="{{ old('email') }}">
                            @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('conciergerie.phone')
                                }}</label>
                            <input type="tel" name="telephone" required
                                class="w-full px-4 py-3 rounded @error('telephone') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;"
                                placeholder="{{ __('conciergerie.phone_placeholder') }}" value="{{ old('telephone') }}">
                            @error('telephone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('conciergerie.travel_reason') }}</label>
                            <select name="motif_voyage" required
                                class="w-full px-4 py-3 rounded @error('motif_voyage') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;">
                                <option value="" style="color: #666;">{{ __('conciergerie.select_reason') }}</option>
                                @foreach([
                                'tourisme' => __('conciergerie.tourism'),
                                'affaires' => __('conciergerie.business'),
                                'formation' => __('conciergerie.training'),
                                'installation' => __('conciergerie.settlement'),
                                'familial' => __('conciergerie.family'),
                                'evenementiel' => __('conciergerie.events'),
                                'autre' => __('conciergerie.other')
                                ] as $value => $label)
                                <option value="{{ $value }}" {{ old('motif_voyage')==$value ? 'selected' : '' }}
                                    style="color: white;">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('motif_voyage')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('conciergerie.arrival_date') }}</label>
                            <input type="date" name="date_arrivee" required
                                class="w-full px-4 py-3 rounded @error('date_arrivee') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;"
                                value="{{ old('date_arrivee') }}">
                            @error('date_arrivee')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('conciergerie.stay_duration') }}</label>
                            <select name="duree_sejour" required
                                class="w-full px-4 py-3 rounded @error('duree_sejour') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;">
                                <option value="" style="color: #666;">{{ __('conciergerie.select_duration') }}</option>
                                @foreach([
                                '1-3' => __('conciergerie.duration_1_3'),
                                '4-7' => __('conciergerie.duration_4_7'),
                                '1-2' => __('conciergerie.duration_1_2_weeks'),
                                '3-4' => __('conciergerie.duration_3_4_weeks'),
                                '1-3' => __('conciergerie.duration_1_3_months'),
                                '3-6' => __('conciergerie.duration_3_6_months'),
                                '6+' => __('conciergerie.duration_6_plus')
                                ] as $value => $label)
                                <option value="{{ $value }}" {{ old('duree_sejour')==$value ? 'selected' : '' }}
                                    style="color: white;">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('duree_sejour')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('conciergerie.people_count') }}</label>
                            <select name="nombre_personnes" required
                                class="w-full px-4 py-3 rounded @error('nombre_personnes') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;">
                                <option value="" style="color: #666;">{{ __('conciergerie.select_people') }}</option>
                                @foreach([
                                '1' => __('conciergerie.1_person'),
                                '2' => __('conciergerie.2_people'),
                                '3-4' => __('conciergerie.3_4_people'),
                                '5-6' => __('conciergerie.5_6_people'),
                                '7+' => __('conciergerie.7_plus_people')
                                ] as $value => $label)
                                <option value="{{ $value }}" {{ old('nombre_personnes')==$value ? 'selected' : '' }}
                                    style="color: white;">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('nombre_personnes')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('conciergerie.budget')
                                }}</label>
                            <select name="budget"
                                class="w-full px-4 py-3 rounded @error('budget') border-red-500 @enderror"
                                style="background: #111; border: 1px solid #444; color: white;">
                                <option value="" style="color: #666;">{{ __('conciergerie.select_budget') }}</option>
                                @foreach([
                                '500-1000' => __('conciergerie.500_1000'),
                                '1000-2000' => __('conciergerie.1000_2000'),
                                '2000-5000' => __('conciergerie.2000_5000'),
                                '5000-10000' => __('conciergerie.5000_10000'),
                                '10000+' => __('conciergerie.10000_plus'),
                                'sur_devis' => __('conciergerie.quote_based')
                                ] as $value => $label)
                                <option value="{{ $value }}" {{ old('budget')==$value ? 'selected' : '' }}
                                    style="color: white;">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('budget')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium" style="color: #ddd;">{{
                            __('conciergerie.accompaniment_type') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php
                            $oldType = old('type_accompagnement');
                            @endphp
                            @foreach([
                            'chauffeur' => ['with_driver', 'with_driver_desc'],
                            'location' => ['car_rental_only', 'car_rental_only_desc'],
                            'mixte' => ['mixed', 'mixed_desc']
                            ] as $value => $labels)
                            <div class="flex items-center p-4 rounded cursor-pointer"
                                style="background: #111; border: 1px solid #444;">
                                <input type="radio" id="accomp_{{ $value }}" name="type_accompagnement"
                                    value="{{ $value }}" class="mr-3" {{ $oldType==$value ? 'checked' : '' }}
                                    onchange="showRedirectionInfo('{{ $value }}')">
                                <label for="accomp_{{ $value }}" class="cursor-pointer">
                                    <span class="font-medium" style="color: white;">{{ __('conciergerie.' . $labels[0])
                                        }}</span>
                                    <p class="text-sm mt-1" style="color: #aaa;">{{ __('conciergerie.' . $labels[1]) }}
                                    </p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('type_accompagnement')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div id="redirectionInfo" class="mt-4 p-4 rounded hidden"
                            style="background: rgba(var(--gold-rgb), 0.1); border: 1px solid rgba(var(--gold-rgb), 0.3);">
                            <p style="color: var(--gold);">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span id="redirectionText"></span>
                                <a id="redirectionLink" href="#" class="font-semibold underline ml-1"
                                    style="color: var(--gold);"></a>
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium" style="color: #ddd;">{{
                            __('conciergerie.services_desired') }}</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @php
                            $oldServices = old('services', []);
                            @endphp
                            @foreach([
                            'service_transfer_airport',
                            'service_car_rental_check',
                            'service_accommodation_check',
                            'service_tourist_guide',
                            'service_admin_assistance',
                            'service_business_check',
                            'service_settlement',
                            'service_arrival_shopping',
                            'service_interpreter',
                            'service_tickets',
                            'service_restaurants',
                            'service_cleaning'
                            ] as $service)
                            <label class="flex items-center p-2 rounded cursor-pointer"
                                style="background: #111; border: 1px solid #444;">
                                <input type="checkbox" name="services[]" value="{{ __('conciergerie.' . $service) }}"
                                    class="mr-2 rounded" style="border-color: #666;" {{ in_array(__('conciergerie.' .
                                    $service), $oldServices) ? 'checked' : '' }}>
                                <span class="text-sm" style="color: #ddd;">{{ __('conciergerie.' . $service) }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('services')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('conciergerie.message')
                            }}</label>
                        <textarea name="message" rows="5" required
                            class="w-full px-4 py-3 rounded @error('message') border-red-500 @enderror"
                            style="background: #111; border: 1px solid #444; color: white;"
                            placeholder="{{ __('conciergerie.message_placeholder') }}">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="relative">
                        <button type="submit" id="submit-btn"
                            class="w-full px-6 md:px-8 py-3 font-semibold rounded transition-all duration-300 flex items-center justify-center"
                            style="background: var(--gold); color: black;">
                            <span id="btn-text" class="flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-3"></i>
                                {{ __('conciergerie.submit_quote') }}
                            </span>
                            <span id="btn-loading" class="hidden flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin mr-3"></i>
                                {{ __('conciergerie.processing') }}
                            </span>
                        </button>
                    </div>

                    <p class="text-center text-sm mt-4" style="color: #888;">
                        <i class="fas fa-shield-alt mr-2"></i>
                        {{ __('conciergerie.processing_note') }}
                    </p>
                </div>
            </form>
        </div>

        <!-- Contact rapide - Style sobre -->
        <div id="contact" class="mt-12 max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: var(--gold);">
                        <i class="fas fa-phone-alt text-black"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.phone_label') }}</h3>
                    <a href="tel:0176380017" class="font-semibold hover:text-yellow-300" style="color: var(--gold);">06 99 16 44 55</a>
                </div>

                <div class="text-center p-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: #25D366;">
                        <i class="fab fa-whatsapp text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.whatsapp_label') }}
                    </h3>
                    <p class="font-semibold" style="color: #86efac;">{{ __('conciergerie.whatsapp_available') }}</p>
                </div>

                <div class="text-center p-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: var(--gold);">
                        <i class="fas fa-envelope text-black"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">{{ __('conciergerie.email_label') }}</h3>
                    <a href="mailto:contact@djokprestige.com" class="font-semibold hover:text-yellow-300"
                        style="color: var(--gold);">
                        contact@djokprestige.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide du message de succès
        const successMessage = document.getElementById('success-alert');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                successMessage.style.transition = 'opacity 0.5s ease';
                setTimeout(() => successMessage.remove(), 500);
            }, 8000);
        }

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

        // Gestion du formulaire
        const form = document.getElementById('conciergerieForm');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');

        if (form) {
            form.addEventListener('submit', function() {
                // Désactiver le bouton et montrer le loader
                submitBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
            });

            // Si retour avec erreurs, réactiver le bouton
            if (document.querySelector('[class*="border-red"]')) {
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }

        // Scroll vers le formulaire s'il y a des erreurs
        if (document.querySelector('[class*="border-red"]')) {
            setTimeout(() => {
                document.getElementById('devis').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 300);
        }

        // Définir la date minimum pour le champ date d'arrivée (aujourd'hui)
        const dateInput = document.querySelector('input[name="date_arrivee"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;

            // Si aucune valeur n'est définie, mettre la date d'aujourd'hui
            if (!dateInput.value) {
                dateInput.value = today;
            }
        }

        // Afficher la redirection si un type d'accompagnement est déjà sélectionné
        const selectedType = document.querySelector('input[name="type_accompagnement"]:checked');
        if (selectedType) {
            showRedirectionInfo(selectedType.value);
        }
    });

    function showRedirectionInfo(type) {
        const infoDiv = document.getElementById('redirectionInfo');
        const textSpan = document.getElementById('redirectionText');
        const link = document.getElementById('redirectionLink');

        infoDiv.classList.remove('hidden');

        switch(type) {
            case 'chauffeur':
                textSpan.textContent = "{{ __('conciergerie.redirection_driver') }}";
                link.textContent = "{{ __('conciergerie.vtc_transport') }}";
                link.href = "{{ route('vtc-transport') }}";
                link.target = '_blank';
                break;
            case 'location':
                textSpan.textContent = "{{ __('conciergerie.redirection_rental') }}";
                link.textContent = "{{ __('conciergerie.car_rental_page') }}";
                link.href = "{{ route('location') }}";
                link.target = '_blank';
                break;
            case 'mixte':
                textSpan.textContent = "{{ __('conciergerie.redirection_mixed') }}";
                link.textContent = "{{ __('conciergerie.vtc_rental') }}";
                link.href = "#";
                link.target = '_blank';
                link.onclick = function(e) {
                    e.preventDefault();
                    window.open("{{ route('vtc-transport') }}", '_blank');
                    setTimeout(() => {
                        window.open("{{ route('location') }}", '_blank');
                    }, 500);
                };
                break;
        }
    }
</script>
@endsection
