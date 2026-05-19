@extends('layouts.main')

@section('title', __('home.title'))

@section('content')
<!-- Header avec bannières superposées -->
<header class="relative bg-black">
    <!-- Conteneur des bannières -->
    <div class="relative">
        <!-- Bannière 1 - Formation VTC avec opacité corrigée -->
        <section class="hero">
            <!-- Fond avec opacité ajustée -->
            <div class="absolute inset-0 bg-black">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1600&q=80"
                    alt="{{ __('home.vtc_training') }}" class="object-cover w-full h-full opacity-50">
                <!-- Overlay doré avec opacité réduite pour correspondre à la bannière 2 -->
                <div class="absolute inset-0" style="background: rgba(182, 146, 70, 0.85); mix-blend-mode: multiply;">
                </div>
                <!-- Overlay noir supplémentaire pour correspondre à l'opacité de la bannière 2 -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-black/70 to-black/50"></div>
            </div>

            <div class="hero-left relative z-10">
                <h1>{{ __('home.vtc_training') }}</h1>
                <p class="hero-subtitle">{{ __('home.become_vtc_driver') }}</p>
                <p class="hero-description">
                    {{ __('home.vtc_description') }}
                </p>
            </div>

            <div class="hero-right relative z-10">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide slide">
                            <div>
                                <h2>{{ __('home.slide1_title') }}</h2>
                                <p>{{ __('home.slide1_subtitle') }}</p>
                                <ul class="document-list">
                                    <li>{{ __('home.document1') }}</li>
                                    <li>{{ __('home.document2') }}</li>
                                    <li>{{ __('home.document3') }}</li>
                                    <li>{{ __('home.document4') }}</li>
                                    <li>{{ __('home.document5') }}</li>
                                </ul>
                            </div>
                            <div>
                                <a href="{{ route('formation') }}" class="btn">{{ __('home.discover_exam') }}</a>
                                <a href="{{ route('contact') }}" class="btn">{{ __('home.more_info') }}</a>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide slide">
                            <div>
                                <h2>{{ __('home.slide2_title') }}</h2>
                                <p>{{ __('home.slide2_text') }}</p>
                                <div class="objective">
                                    <strong>{{ __('home.objective') }}</strong>
                                </div>
                                <p class="question">{{ __('home.exam_dates') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('formation') }}" class="btn">{{ __('home.theoretical_training') }}</a>
                                <a href="{{ route('contact') }}" class="btn">{{ __('home.request_quote') }}</a>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide slide">
                            <div>
                                <h2>{{ __('home.slide3_title') }}</h2>
                                <p>{{ __('home.slide3_text') }}</p>
                                <div class="objective">
                                    <strong>{{ __('home.objective') }}</strong>
                                </div>
                                <p class="question">{{ __('home.exam_dates') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('formation') }}" class="btn">{{ __('home.practice_training') }}</a>
                                <a href="{{ route('location') }}" class="btn">{{ __('home.rent_vehicle') }}</a>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="swiper-slide slide">
                            <div>
                                <h2>{{ __('home.slide4_title') }}</h2>
                                <p>{{ __('home.slide4_text') }}</p>
                                <p class="question">{{ __('home.question1') }}</p>
                                <p class="question">{{ __('home.question2') }}</p>
                                <p class="question">{{ __('home.question3') }}</p>
                                <p class="question">{{ __('home.question4') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('formation') }}" class="btn">{{ __('home.continuous_training') }}</a>
                                <a href="{{ route('formation') }}" class="btn">{{ __('home.my_procedures') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- BOUTONS DE PAGINATION -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <!-- BOUTON DEVIS -->
            <a href="{{ route('contact') }}" class="devis-btn">{{ __('home.quote_btn') }}</a>

            <!-- ICÔNES FLOTTANTES -->
            <div class="floating-icons">
                <a href="https://www.google.com/maps/place//data=!4m3!3m2!1s0x47e613e5ed89e9fb:0xf5ac01ba78653a2b!12e1"
                    class="icon-item">
                    <i class="fa-solid fa-location-dot"></i><span>{{ __('home.find_center') }}</span>
                </a>
                <a href="{{ route('formation') }}" class="icon-item">
                    <i class="fa-solid fa-book-open"></i><span>{{ __('home.our_trainings') }}</span>
                </a>
                <a href="{{ route('contact') }}" class="icon-item">
                    <i class="fa-solid fa-envelope"></i><span>{{ __('home.contact') }}</span>
                </a>
                <a href="tel:+33123456789" class="icon-item">
                    <i class="fa-solid fa-phone"></i><span>{{ __('home.call') }}</span>
                </a>
            </div>

            <!-- Indicateur de défilement -->
            <div class="absolute z-20 transform -translate-x-1/2 bottom-10 left-1/2 animate-bounce">
                <a href="#banner-2" class="text-white hover:text-var(--gold) transition"
                    aria-label="{{ __('home.scroll_down') }}">
                    <i class="text-3xl fas fa-chevron-down"></i>
                </a>
            </div>
        </section>

        <!-- Bannière 2 - Location VTC -->
        <section id="banner-2"
            class="relative flex items-center justify-center min-h-screen overflow-hidden banner-section">
            <!-- Séparateur décoratif -->
            <div class="absolute top-0 left-0 right-0 h-1">
                <div class="w-48 h-full mx-auto"
                    style="background: linear-gradient(90deg, transparent, #b69246, transparent);"></div>
            </div>

            <div class="absolute inset-0 bg-black">
                <img src="https://images.unsplash.com/photo-1555212697-194d092e3b8f" alt="{{ __('home.rental_tag') }}"
                    class="object-cover w-full h-full opacity-50">
                <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-black/70 to-black/50"></div>
            </div>

            <div class="relative z-10 w-full px-4 py-20 mx-auto text-center text-white">
                <div class="mb-6">
                    <span
                        class="inline-block px-4 py-2 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full"
                        style="background: rgba(182, 146, 70, 0.3); color: #b69246;">
                        {{ __('home.rental_tag') }}
                    </span>
                </div>

                <h1 class="mb-8 text-4xl font-bold md:text-5xl lg:text-6xl" style="color: #b69246;">
                    {!! __('home.rental_title') !!}
                </h1>

                <p class="max-w-3xl mx-auto mb-16 text-lg leading-relaxed md:text-xl lg:text-2xl">
                    {{ __('home.rental_description') }}
                </p>

                <!-- Nouvelles cartes avec images -->
                <div class="grid max-w-6xl grid-cols-1 gap-8 mx-auto mb-16 md:grid-cols-3">
                    <!-- Véhicule Électrique -->
                    <div class="relative overflow-hidden transition-all duration-300 border group rounded-xl border-white/10 bg-white/5 backdrop-blur-sm hover:transform hover:scale-105 hover:border-b69246/30 mx-auto w-full max-w-md md:max-w-none">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('v_electrique.webp') }}" alt="{{ __('home.electric_vehicle') }}"
                                class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute flex items-center justify-between bottom-4 left-4 right-4">
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-users" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">3 {{ __('home.passengers') }}</span>
                                </div>
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-suitcase" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">3 {{ __('home.luggage') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="mb-3 text-xl font-bold">{{ __('home.electric_vehicle') }}</h3>
                            <p class="mb-4 text-sm text-gray-300">{{ __('home.electric_description') }}</p>
                            <div class="flex items-center text-sm">
                                <i class="mr-2 fas fa-bolt" style="color: #b69246;"></i>
                                <span>{{ __('home.fully_electric') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Véhicule Berline -->
                    <div class="relative overflow-hidden transition-all duration-300 border group rounded-xl border-white/10 bg-white/5 backdrop-blur-sm hover:transform hover:scale-105 hover:border-b69246/30 mx-auto w-full max-w-md md:max-w-none">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('v_berline.webp') }}" alt="{{ __('home.premium_sedan') }}"
                                class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute flex items-center justify-between bottom-4 left-4 right-4">
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-users" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">3 {{ __('home.passengers') }}</span>
                                </div>
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-suitcase" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">3 {{ __('home.luggage') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="mb-3 text-xl font-bold">{{ __('home.premium_sedan') }}</h3>
                            <p class="mb-4 text-sm text-gray-300">{{ __('home.sedan_description') }}</p>
                            <div class="flex items-center text-sm">
                                <i class="mr-2 fas fa-star" style="color: #b69246;"></i>
                                <span>{{ __('home.business_class') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Véhicule VAN -->
                    <div class="relative overflow-hidden transition-all duration-300 border group rounded-xl border-white/10 bg-white/5 backdrop-blur-sm hover:transform hover:scale-105 hover:border-b69246/30 mx-auto w-full max-w-md md:max-w-none">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('v_VAN.webp') }}" alt="{{ __('home.van_7seats') }}"
                                class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute flex items-center justify-between bottom-4 left-4 right-4">
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-users" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">7 {{ __('home.passengers') }}</span>
                                </div>
                                <div class="flex items-center px-3 py-1 space-x-2 rounded-full bg-black/70">
                                    <i class="text-sm fas fa-suitcase" style="color: #b69246;"></i>
                                    <span class="text-sm font-semibold">7 {{ __('home.luggage') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="mb-3 text-xl font-bold">{{ __('home.van_7seats') }}</h3>
                            <p class="mb-4 text-sm text-gray-300">{{ __('home.van_description') }}</p>
                            <div class="flex items-center text-sm">
                                <i class="mr-2 fas fa-shield-alt" style="color: #b69246;"></i>
                                <span>{{ __('home.group_comfort') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avantages additionnels -->
                <div class="grid max-w-3xl grid-cols-2 gap-4 mx-auto mb-12 md:grid-cols-4">
                    <div class="p-4 text-center">
                        <i class="mb-3 text-2xl fas fa-tools" style="color: #b69246;"></i>
                        <p class="text-sm font-semibold">{{ __('home.maintenance_included') }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <i class="mb-3 text-2xl fas fa-euro-sign" style="color: #b69246;"></i>
                        <p class="text-sm font-semibold">{{ __('home.competitive_prices') }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <i class="mb-3 text-2xl fas fa-calendar-alt" style="color: #b69246;"></i>
                        <p class="text-sm font-semibold">{{ __('home.total_flexibility') }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <i class="mb-3 text-2xl fas fa-shield-alt" style="color: #b69246;"></i>
                        <p class="text-sm font-semibold">{{ __('home.full_insurance') }}</p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <a href="{{route('location')}}">
                        <button class="px-8 py-4 text-lg font-semibold transition-all duration-300 rounded-lg"
                            style="background: #b69246; color: white; border: 2px solid #b69246;"
                            onmouseover="this.style.background='transparent'; this.style.color='#b69246';"
                            onmouseout="this.style.background='#b69246'; this.style.color='white';">
                            <i class="mr-2 fas fa-search"></i> {{ __('home.view_vehicles') }}
                        </button>
                    </a>
                    <a href="{{ route('location') }}">
                        <button class="px-8 py-4 text-lg font-semibold transition-all duration-300 rounded-lg"
                            style="border: 2px solid #b69246; color: #b69246; background: transparent;"
                            onmouseover="this.style.background='#b69246'; this.style.color='white';"
                            onmouseout="this.style.background='transparent'; this.style.color='#b69246';">
                            <i class="mr-2 fas fa-calendar-check"></i> {{ __('home.book_now') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- Indicateur de défilement -->
            <div class="absolute z-20 transform -translate-x-1/2 bottom-10 left-1/2 animate-bounce">
                <a href="#services" class="text-white transition hover:text-b69246"
                    aria-label="{{ __('home.scroll_down') }}">
                    <i class="text-3xl fas fa-chevron-down"></i>
                </a>
            </div>
        </section>
    </div>
</header>

<!-- SERVICES SECTION -->
<section id="services" class="py-20 bg-black">
    <div class="px-4 mx-auto max-w-7xl">
        <h2 class="mb-4 text-4xl font-bold text-center" style="color: var(--gold);">{{ __('home.our_services') }}</h2>
        <p class="max-w-3xl mx-auto mt-4 text-lg text-center text-gray-300">
            {{ __('home.services_description') }}
        </p>

        <div class="grid grid-cols-1 gap-10 mt-16 md:grid-cols-2 lg:grid-cols-3 justify-items-center">
            <!-- Formations -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg"
                        alt="{{ __('home.trainings') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.trainings') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.trainings_description') }}</p>
                    <a href="{{ route('formation') }}">
                        <button class="self-center px-8 py-3 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black; border: 2px solid black;"
                            onmouseover="this.style.background='black'; this.style.color='var(--gold)'; this.style.borderColor='var(--gold)'"
                            onmouseout="this.style.background='var(--gold)'; this.style.color='black'; this.style.borderColor='black'">
                            {{ __('home.view_trainings') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- Formation Internationale -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/210182/pexels-photo-210182.jpeg"
                        alt="{{ __('home.international_training') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.international_training') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.international_description') }}</p>
                    <a href="{{ route('formation.international') }}">
                        <button class="self-center px-8 py-3 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black; border: 2px solid black;"
                            onmouseover="this.style.background='black'; this.style.color='var(--gold)'; this.style.borderColor='var(--gold)'"
                            onmouseout="this.style.background='var(--gold)'; this.style.color='black'; this.style.borderColor='black'">
                            {{ __('home.discover') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- VTC & Location -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/125779/pexels-photo-125779.jpeg"
                        alt="{{ __('home.vtc_reservation') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.vtc_reservation') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.vtc_reservation_description') }}</p>
                    <a href="{{ route('reservation') }}">
                        <button class="self-center px-8 py-3 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black; border: 2px solid black;"
                            onmouseover="this.style.background='black'; this.style.color='var(--gold)'; this.style.borderColor='var(--gold)'"
                            onmouseout="this.style.background='var(--gold)'; this.style.color='black'; this.style.borderColor='black'">
                            {{ __('home.reserve_now') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- Location -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/164634/pexels-photo-164634.jpeg"
                        alt="{{ __('home.rental') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.rental') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.rental_description') }}</p>
                    <a href="{{ route('location') }}">
                        <button class="self-center px-8 py-3 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black; border: 2px solid black;"
                            onmouseover="this.style.background='black'; this.style.color='var(--gold)'; this.style.borderColor='var(--gold)'"
                            onmouseout="this.style.background='var(--gold)'; this.style.color='black'; this.style.borderColor='black'">
                            {{ __('home.rent_vehicle_btn') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- Conciergerie -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/5273464/pexels-photo-5273464.jpeg"
                        alt="{{ __('home.concierge') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.concierge') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.concierge_description') }}</p>
                    <a href="{{ route('conciergerie') }}">
                        <button class="self-center px-8 py-3 font-semibold transition-all duration-300"
                            style="background: var(--gold); color: black; border: 2px solid black;"
                            onmouseover="this.style.background='black'; this.style.color='var(--gold)'; this.style.borderColor='var(--gold)'"
                            onmouseout="this.style.background='var(--gold)'; this.style.color='black'; this.style.borderColor='black'">
                            {{ __('home.discover_services') }}
                        </button>
                    </a>
                </div>
            </div>

            <!-- Espace Client -->
            <div class="flex flex-col bg-white text-black min-h-[280px] border-2 w-full max-w-md md:max-w-none"
                style="border-color: var(--gold);">
                <div class="h-48">
                    <img src="https://images.pexels.com/photos/3768916/pexels-photo-3768916.jpeg"
                        alt="{{ __('home.client_space') }}" class="object-cover w-full h-full">
                </div>
                <div class="flex flex-col justify-center flex-grow p-6 text-center">
                    <h3 class="mb-3 text-2xl font-bold">{{ __('home.client_space') }}</h3>
                    <p class="mb-6 text-gray-700">{{ __('home.client_space_description') }}</p>
                    <a href="{{ route('espaceclient') }}">
                        <button
                            class="self-center px-8 py-3 font-semibold text-white transition-all duration-300 bg-black border-2 border-black"
                            onmouseover="this.style.background='white'; this.style.color='black'; this.style.borderColor='black'"
                            onmouseout="this.style.background='black'; this.style.color='white'; this.style.borderColor='black'">
                            {{ __('home.login') }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section id="stats" class="py-20 text-white bg-dark">
    <div class="px-4 mx-auto max-w-7xl">
        <h2 class="mb-4 text-4xl font-bold text-center" style="color: #b69246;">{{ __('home.numbers_title') }}</h2>
        <p class="mt-4 text-center text-gray-300">{{ __('home.numbers_subtitle') }}</p>

        <div class="grid grid-cols-1 gap-12 mt-16 md:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-col items-center text-center group">
                <div class="flex items-center justify-center w-20 h-20 mb-6 transition-all duration-300 rounded-full bg-gradient-to-br from-amber-600/20 to-amber-800/20 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-amber-900/30">
                    <i class="text-3xl fas fa-handshake" style="color: #b69246;"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold leading-tight md:text-2xl" style="color: #b69246;">{{ __('home.experts_network') }}</h3>
                <p class="max-w-xs text-sm text-gray-300 md:text-base">{{ __('home.experts_network_desc') }}</p>
            </div>

            <div class="flex flex-col items-center text-center group">
                <div class="flex items-center justify-center w-20 h-20 mb-6 transition-all duration-300 rounded-full bg-gradient-to-br from-amber-600/20 to-amber-800/20 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-amber-900/30">
                    <i class="text-3xl fas fa-chalkboard-teacher" style="color: #b69246;"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold leading-tight md:text-2xl" style="color: #b69246;">{{ __('home.practical_trainings') }}</h3>
                <p class="max-w-xs text-sm text-gray-300 md:text-base">{{ __('home.practical_trainings_desc') }}</p>
            </div>

            <div class="flex flex-col items-center text-center group">
                <div class="flex items-center justify-center w-20 h-20 mb-6 transition-all duration-300 rounded-full bg-gradient-to-br from-amber-600/20 to-amber-800/20 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-amber-900/30">
                    <i class="text-3xl fas fa-user-check" style="color: #b69246;"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold leading-tight md:text-2xl" style="color: #b69246;">{{ __('home.tailored_support') }}</h3>
                <p class="max-w-xs text-sm text-gray-300 md:text-base">{{ __('home.tailored_support_desc') }}</p>
            </div>

            <div class="flex flex-col items-center text-center group">
                <div class="flex items-center justify-center w-20 h-20 mb-6 transition-all duration-300 rounded-full bg-gradient-to-br from-amber-600/20 to-amber-800/20 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-amber-900/30">
                    <i class="text-3xl fas fa-users" style="color: #b69246;"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold leading-tight md:text-2xl" style="color: #b69246;">{{ __('home.growing_community') }}</h3>
                <p class="max-w-xs text-sm text-gray-300 md:text-base">{{ __('home.growing_community_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CERTIFICATIONS SECTION                                        -->
<!-- ============================================================ -->
<section class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-6xl mx-auto">

            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">
                    {{ __('home.certifications_title') }}
                </h2>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                    {{ __('home.certifications_subtitle') }}
                </p>
            </div>

            <!-- BANNIÈRE QUALIOPI HORIZONTALE -->
            <div class="qualiopi-banner">

                <!-- BLOC GAUCHE : image tout-en-un -->
                <div class="qualiopi-left">
                    <img src="{{ asset('qualiopi3.jpeg') }}"
                         alt="Qualiopi processus certifié - République Française"
                         class="qualiopi-logo-img">
                </div>

                <!-- SÉPARATEUR VERTICAL -->
                <div class="qualiopi-separator"></div>

                <!-- BLOC DROIT : téléchargement -->
                <div class="qualiopi-right">
                    <p class="download-title">Téléchargez notre certificat</p>
                    <a href="{{ asset('Agrément_Qualiopi.pdf') }}" target="_blank" class="download-btn-qualiopi">
                        Certificat Qualiopi &nbsp;<i class="fas fa-download"></i>
                    </a>
                </div>
            </div>

            <!-- Card Préfectoral - Format horizontal -->
            <div class="mt-8">
                <a href="{{ asset('Agrément_préfecture.pdf') }}" target="_blank" class="block no-underline">
                    <div class="p-8 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col md:flex-row items-center gap-6"
                        style="background: #111; border: 1px solid #333;">
                        <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center rounded-full overflow-hidden"
                            style="background: var(--gold);">
                            <img src="{{ asset('ac2.PNG') }}" alt="Arrêté Préfectoral"
                                class="object-cover w-full h-full rounded-full">
                        </div>
                        <div class="flex-grow text-left">
                            <h3 class="text-xl font-bold mb-2" style="color: white;">{{ __('home.prefectoral') }}</h3>
                            <p class="text-gray-400 mb-1">{{ __('home.prefectoral_description') }}</p>
                            <p class="text-sm text-gray-500">{{ __('home.prefectoral_detail') }}</p>
                        </div>
                        <div class="flex-shrink-0 text-center">
                            <div class="text-sm mb-1" style="color: var(--gold);">
                                <i class="fas fa-file-pdf mr-2"></i>
                                <span>{{ __('home.prefectoral') }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('home.pdf_label') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Datadock - Format horizontal (corrigé) -->
            <div class="mt-8">
                <a href="{{ route('pdf.arrete-modificatif') }}" target="_blank" class="block no-underline">
                    <div class="p-8 transition-all duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col md:flex-row items-center gap-6"
                        style="background: #111; border: 1px solid #333;">
                        <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center rounded-full overflow-hidden transition-all duration-300 hover:scale-110 hover:rotate-12"
                            style="background: var(--gold);">
                            <img src="{{ asset('ac3.JPG') }}" alt="Datadock Certification"
                                class="object-cover w-full h-full rounded-full">
                        </div>
                        <div class="flex-grow text-left">
                            <h3 class="text-xl font-bold mb-2" style="color: white;">{{ __('home.datadock') }}</h3>
                            <p class="text-gray-400 mb-1">{{ __('home.datadock_description') }}</p>
                            <p class="text-sm text-gray-500">{{ __('home.datadock_detail') }}</p>
                        </div>
                        <div class="flex-shrink-0 text-center">
                            <div class="text-sm mb-1" style="color: var(--gold);">
                                <i class="fas fa-file-pdf mr-2"></i>
                                <span>{{ __('home.view_arrete_pdf') }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('home.pdf_label') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- SESSIONS DE FORMATION À VENIR -->
@if(isset($upcomingSessions) && $upcomingSessions->count() > 0)
<section class="py-20" style="background: linear-gradient(135deg, #0a0a0a 0%, #111 100%);">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-3xl font-bold md:text-4xl" style="color: #b69246;">
                Sessions de formation à venir
            </h2>
            <p class="max-w-2xl mx-auto text-lg text-gray-400">
                Inscrivez-vous dès maintenant aux prochaines sessions de formation
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($upcomingSessions as $session)
            <div class="group relative overflow-hidden transition-all duration-300 rounded-xl hover:transform hover:scale-105"
                style="background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%); border: 1px solid rgba(182, 146, 70, 0.3);">

                <div class="absolute z-20 session-badge" style="top: 16px; right: 16px;">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full whitespace-normal break-words text-center"
                        style="background: rgba(182, 146, 70, 0.2); color: #b69246; backdrop-filter: blur(4px); max-width: 180px; line-height: 1.3;">
                        {{ $session->formation->title }}
                    </span>
                </div>

                <div class="p-6 relative z-10">
                    <h3 class="text-xl font-bold text-white mb-3 session-title" style="line-height: 1.3;">
                        {{ $session->name }}
                    </h3>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-calendar-alt w-5 flex-shrink-0" style="color: #b69246;"></i>
                            <span class="ml-3 text-sm break-words">{{ $session->formatted_dates }}</span>
                        </div>
                        @if($session->formatted_schedule)
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-clock w-5 flex-shrink-0" style="color: #b69246;"></i>
                            <span class="ml-3 text-sm">{{ $session->formatted_schedule }}</span>
                        </div>
                        @endif
                        @if($session->location)
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-map-marker-alt w-5 flex-shrink-0" style="color: #b69246;"></i>
                            <span class="ml-3 text-sm break-words">{{ $session->location }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-4 mt-2 border-t border-gray-800">
                        <div>
                            <span class="text-2xl font-bold" style="color: #b69246;">{{ $session->formatted_price }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Places restantes</span>
                            <div class="text-lg font-semibold text-white">
                                {{ $session->available_places }} / {{ $session->max_places }}
                            </div>
                        </div>
                    </div>

                    @if($session->available_places <= 0)
                    <button disabled
                            class="w-full mt-6 py-3 rounded-lg font-semibold cursor-not-allowed transition-all duration-300"
                            style="background: #4a5568; color: #a0aec0;">
                        <i class="fas fa-times-circle mr-2"></i>COMPLET
                    </button>
                    @else
                    <a href="{{ route('formation.inscrire.presentiel', $session->formation_id) }}?session_id={{ $session->id }}"
                       class="block w-full mt-6 py-3 text-center font-semibold rounded-lg transition-all duration-300 hover:scale-105"
                       style="background: #b69246; color: black;">
                        <i class="fas fa-user-plus mr-2"></i>S'inscrire
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($upcomingSessions->count() > 6)
        <div class="text-center mt-12">
            <a href="{{ route('formation') }}"
               class="inline-flex items-center px-6 py-3 font-semibold rounded-lg transition-all duration-300 hover:scale-105"
               style="background: transparent; border: 1px solid #b69246; color: #b69246;">
                Voir toutes les sessions
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
@elseif(isset($upcomingSessions))
<section class="py-20" style="background: linear-gradient(135deg, #0a0a0a 0%, #111 100%);">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-3xl font-bold md:text-4xl" style="color: #b69246;">
                Sessions de formation à venir
            </h2>
            <p class="max-w-2xl mx-auto text-lg text-gray-400">
                Inscrivez-vous dès maintenant aux prochaines sessions de formation
            </p>
        </div>
        <div class="text-center py-12">
            <div class="text-gray-400 mb-3">
                <i class="fas fa-calendar-alt text-5xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Aucune session programmée pour le moment</h3>
            <p class="text-gray-400">Consultez notre catalogue de formations ou contactez-nous pour plus d'informations.</p>
            <a href="{{ route('formation') }}" class="inline-block mt-4 px-6 py-2 rounded-lg" style="background: #b69246; color: black;">
                Voir nos formations
            </a>
        </div>
    </div>
</section>
@endif

<!-- AVIS CLIENTS -->
<section id="testimonials" class="py-20 bg-black">
    <div class="px-4 mx-auto max-w-7xl">
        <div class="flex flex-col items-start gap-10 lg:flex-row">
            <!-- Carte entreprise fixe -->
            <div class="w-full lg:w-1/4">
                <div class="flex flex-col items-center gap-4 company-card md:flex-row md:items-start">
                    <div class="flex items-center justify-center w-16 h-16 company-avatar rounded-xl"
                        style="background: #4a6cf7;">
                        <i class="text-2xl text-white fas fa-user"></i>
                    </div>
                    <div class="company-info text-center md:text-left">
                        <h3 class="mb-2 text-2xl font-bold">{{ __('home.company_name') }}</h3>
                        <div class="mb-1 text-2xl stars" style="color: #b69246;">★★★★★</div>
                        <small class="text-sm text-gray-400">{{ __('home.google_reviews') }}</small>
                        <br>
                        <a href="https://www.google.com/maps/place//data=!4m3!3m2!1s0x47e613e5ed89e9fb:0xf5ac01ba78653a2b!12e1"
                            target="_blank" rel="noopener noreferrer" class="inline-block mt-3">
                            <button
                                class="px-6 py-2 text-white transition-all duration-300 bg-transparent border border-white btn-review hover:bg-white hover:text-black">
                                {{ __('home.write_review') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Zone slider avec avis -->
            <div class="relative w-full lg:w-3/4">
                <div class="arrow left absolute top-1/2 -left-5 transform -translate-y-1/2 z-10 w-10 h-10 rounded-full border border-var(--gold) bg-black/80 flex items-center justify-center cursor-pointer hover:bg-b69246 hover:text-black transition-all duration-300 hidden md:flex"
                    style="color: #b69246;" onclick="scrollReviews(-1)">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="arrow right absolute top-1/2 -right-5 transform -translate-y-1/2 z-10 w-10 h-10 rounded-full border border-var(--gold) bg-black/80 flex items-center justify-center cursor-pointer hover:bg-b69246 hover:text-black transition-all duration-300 hidden md:flex"
                    style="color: #b69246;" onclick="scrollReviews(1)">
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="flex gap-6 pb-4 overflow-x-auto reviews-slider scroll-smooth" id="reviewsSlider"
                    style="scrollbar-width: none;">
                    @php
                        $reviews = [
                            ['name' => 'Lalla Guindo', 'date' => 'il y a 2 ans', 'text' => 'Entreprise au top ! Service impeccable et professionnel.'],
                            ['name' => 'Bruno Bouet', 'date' => 'il y a 2 ans', 'text' => 'Directrice très expérimentée et très compétente. Je recommande !'],
                            ['name' => 'Aminta B.', 'date' => 'il y a 2 ans', 'text' => 'J\'ai fait appel à Djok Prestige pour une prestation ! Je recommande fortement !!'],
                            ['name' => 'Marie Dubois', 'date' => 'il y a 1 an', 'text' => 'Service de VTC excellent, ponctuel et très professionnel.'],
                            ['name' => 'Thomas Martin', 'date' => 'il y a 8 mois', 'text' => 'Formation VTC de qualité, équipe pédagogique compétente et à l\'écoute.'],
                            ['name' => 'Sophie Laurent', 'date' => 'il y a 6 mois', 'text' => 'Location de véhicule sans souci, entretien parfait et tarif compétitif.'],
                            ['name' => 'Karim S.', 'date' => 'il y a 3 mois', 'text' => 'Accompagnement entrepreneurial exceptionnel pour mon projet en Afrique.'],
                            ['name' => 'Jean Dupont', 'date' => 'il y a 1 mois', 'text' => 'Service conciergerie parfait pour mon arrivée en France. Très professionnel.'],
                            ['name' => 'Alice R.', 'date' => 'il y a 2 semaines', 'text' => 'Chauffeur VTC très courtois, voiture propre et trajet en toute sécurité.'],
                            ['name' => 'Paul G.', 'date' => 'il y a 1 semaine', 'text' => 'Excellent rapport qualité-prix, je reviendrai certainement pour mes futurs besoins.'],
                        ];
                    @endphp

                    @foreach($reviews as $review)
                    <div class="review min-w-[280px] bg-white/5 p-6 rounded-xl border border-white/10 mx-2 md:mx-0">
                        <div class="flex items-center gap-3 mb-3 review-header">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full avatar"
                                style="background: #b8b2a8;">
                                <span class="font-bold">{{ substr($review['name'], 0, 1) }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold">{{ $review['name'] }}</h4>
                                <small class="text-sm text-gray-400">{{ $review['date'] }}</small>
                            </div>
                            <i class="ml-auto fab fa-google" style="color: #4285F4;"></i>
                        </div>
                        <div class="mb-3 review-stars">
                            <span class="text-xl" style="color: #b69246;">★★★★★</span>
                            <i class="ml-1 text-sm fas fa-check-circle" style="color: #4285F4;"></i>
                        </div>
                        <p class="text-gray-300">{{ $review['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ===================== HERO ===================== */
    .hero {
        min-height: 100vh;
        position: relative;
        display: flex;
        align-items: center;
        padding: 60px;
        gap: 40px;
        overflow: hidden;
    }
    .hero-left { flex: 1; color: #fff; position: relative; z-index: 10; }
    .hero-left h1 { font-size: 48px; font-weight: 700; color: #fff; margin-bottom: 15px; }
    .hero-subtitle { font-size: 28px; font-weight: 600; color: #fff; margin-bottom: 10px; }
    .hero-description { font-size: 18px; line-height: 1.6; color: rgba(255,255,255,0.9); max-width: 500px; }

    .devis-btn {
        position: absolute; left: -40px; bottom: 120px;
        background: #b69246; color: #fff; text-decoration: none;
        padding: 18px 60px; font-size: 15px; font-weight: 500;
        border-radius: 0 6px 6px 0; z-index: 20; transition: 0.3s;
    }
    .devis-btn:hover { padding-left: 80px; background: #d4af37; }

    .hero-right {
        width: 400px; margin-right: 120px;
        background: #fff; border-radius: 28px;
        padding: 32px 28px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        position: relative; z-index: 10;
    }

    /* ===================== SWIPER ===================== */
    .swiper { width: 100%; height: 100%; overflow: hidden; display: flex; flex-direction: column; }
    .swiper-wrapper { flex: 1; }
    .swiper-slide { display: flex; flex-direction: column; justify-content: space-between; padding: 0 8px 12px; }
    .slide h2 { text-align: center; font-size: 20px; margin-bottom: 20px; line-height: 1.3; color: #333; }
    .slide p { font-size: 14px; line-height: 1.7; color: #333; margin-bottom: 8px; }
    .question { font-weight: 500; margin: 4px 0; color: #333; }
    .document-list { padding-left: 20px; margin: 12px 0; }
    .document-list li { font-size: 14px; color: #333; line-height: 1.6; margin-bottom: 4px; }
    .objective { background: #f8f4e8; padding: 12px; border-radius: 8px; margin: 12px 0; border-left: 4px solid #b69246; }
    .objective strong { color: #333; }
    .slide .btn {
        display: block; text-align: center; background: #b69246; color: #fff;
        text-decoration: none; padding: 14px 20px; border-radius: 35px;
        font-weight: 600; margin: 12px 0 8px; transition: 0.3s;
        font-size: 13px; text-transform: uppercase;
    }
    .slide .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); background: #d4af37; }
    .swiper-pagination { position: relative; margin: 12px 0 4px; text-align: center; }
    .swiper-pagination-bullet { background: #ccc; opacity: 1; width: 9px; height: 9px; margin: 0 5px !important; }
    .swiper-pagination-bullet-active { background: #111; }

    /* ===================== FLOATING ICONS ===================== */
    .floating-icons {
        position: absolute; right: 30px; top: 50%; transform: translateY(-50%);
        display: flex; flex-direction: column; gap: 16px; z-index: 20; align-items: flex-end;
    }
    .icon-item {
        display: flex; align-items: center; justify-content: flex-start;
        width: 52px; height: 52px; border-radius: 14px; background: #b69246;
        cursor: pointer; overflow: hidden; transition: width 0.4s ease, box-shadow 0.4s ease;
        position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.15); text-decoration: none;
    }
    .icon-item i { font-size: 20px; color: #fff; width: 52px; min-width: 52px; text-align: center; line-height: 52px; flex-shrink: 0; z-index: 2; position: relative; }
    .icon-item span { color: #fff; font-size: 14px; font-weight: 500; padding: 0 18px 0 12px; opacity: 0; transform: translateX(-10px); transition: opacity 0.3s ease, transform 0.3s ease; white-space: nowrap; z-index: 1; position: relative; }
    .icon-item:hover { width: 240px; box-shadow: 0 10px 30px rgba(0,0,0,0.35); background: #d4af37; }
    .icon-item:hover span { opacity: 1; transform: translateX(0); }

/* ===================== QUALIOPI BANNER ===================== */
.qualiopi-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    padding: 40px 60px;
    gap: 40px;
    border-radius: 4px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.qualiopi-left {
    display: flex;
    flex-direction: column;
    gap: 20px;
    flex: 1;
}
.qualiopi-logo-img {
    max-width: 420px;
    width: 100%;
    height: auto;
    object-fit: contain;
    object-position: left center;
}
.certification-mention {
    font-size: 14px;
    color: #333;
    line-height: 1.65;
    max-width: 480px;
}
.qualiopi-separator {
    width: 1px;
    min-height: 130px;
    align-self: stretch;
    background: #e0e0e0;
    flex-shrink: 0;
}
.qualiopi-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    min-width: 260px;
}
.download-title {
    font-size: 18px;
    font-weight: 700;
    color: #111;
    text-align: center;
    line-height: 1.3;
}
.download-btn-qualiopi {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 2px solid #111;
    border-radius: 50px;
    padding: 12px 30px;
    font-size: 14px;
    font-weight: 700;
    color: #111;
    text-decoration: none;
    background: transparent;
    transition: background 0.25s, color 0.25s;
    white-space: nowrap;
}
.download-btn-qualiopi:hover {
    background: #111;
    color: #fff;
}

/* Responsive bannière Qualiopi */
@media (max-width: 768px) {
    .qualiopi-banner {
        flex-direction: column;
        padding: 30px 24px;
        gap: 24px;
        align-items: flex-start;
    }
    .qualiopi-logo-img {
        max-width: 100%;
    }
    .qualiopi-separator {
        width: 100%;
        min-height: 1px;
        height: 1px;
        align-self: auto;
    }
    .qualiopi-right {
        width: 100%;
        min-width: unset;
        align-items: flex-start;
    }
    .download-title { text-align: left; }
}

    /* ===================== RESPONSIVE GLOBAL ===================== */
    @media (max-width: 1024px) {
        .hero { flex-direction: column; gap: 40px; padding: 40px 20px; }
        .hero-right { width: 100%; max-width: 420px; margin: 0 auto; }
        .floating-icons { display: none; }
        .devis-btn { left: 0; bottom: 20px; padding: 16px 28px; position: relative; margin: 20px auto 0; display: block; width: fit-content; border-radius: 8px; }
        .hero-left h1 { font-size: 36px; text-align: center; }
        .hero-subtitle { font-size: 24px; text-align: center; }
        .hero-description { font-size: 16px; text-align: center; margin: 0 auto; }
    }
    @media (max-width: 1200px) {
        .hero-right { margin-right: 80px; }
        .floating-icons { right: 20px; }
    }
    @media (max-width: 767px) {
        .hero-right { width: 90%; max-width: 420px; margin: 0 auto; }
        .reviews-slider { padding: 0 16px; }
        .review { min-width: 280px; }
        .company-card { align-items: center; text-align: center; margin-bottom: 20px; }
        .company-info { text-align: center !important; }
        .session-badge { position: relative !important; top: 0 !important; right: 0 !important; margin-bottom: 12px !important; display: inline-block !important; }
        .session-badge span { display: inline-block !important; max-width: 100% !important; font-size: 11px !important; }
        .session-title { padding-right: 0 !important; margin-top: 0 !important; }
    }
    @media (min-width: 768px) {
        .session-badge { position: absolute !important; top: 16px !important; right: 16px !important; }
        .session-title { padding-right: 130px !important; }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .session-title { padding-right: 110px !important; }
    }
    @media (min-width: 1280px) {
        .session-title { padding-right: 52% !important; }
        .session-badge { right: 24px !important; }
        .session-badge span { font-size: 0.75rem !important; padding: 6px 12px !important; max-width: 220px !important; }
    }
    @media (min-width: 1536px) {
        .session-title { padding-right: 55% !important; }
        .session-badge { right: 28px !important; }
        .session-badge span { font-size: 0.8rem !important; max-width: 250px !important; }
    }
    @media (max-width: 768px) {
        .grid-cols-2 { grid-template-columns: 1fr !important; }
        .grid-cols-3 { grid-template-columns: 1fr !important; gap: 20px; }
        .relative.h-48 { height: 200px; }
        .flex-col.lg\:flex-row { flex-direction: column; }
        .lg\:w-1\/4, .lg\:w-3\/4 { width: 100%; }
        .arrow.left, .arrow.right { display: none; }
    }

    html { scroll-behavior: smooth; }
    .banner-section { position: relative; scroll-margin-top: 0; }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
        40% { transform: translateY(-10px) translateX(-50%); }
        60% { transform: translateY(-5px) translateX(-50%); }
    }
    .animate-bounce { animation: bounce 2s infinite; }

    .reviews-slider { scrollbar-width: none; -ms-overflow-style: none; }
    .reviews-slider::-webkit-scrollbar { display: none; }
    .review { flex: 0 0 auto; transition: transform 0.3s ease; }
    .review:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
    .arrow { transition: all 0.3s ease; }
    .break-words { word-wrap: break-word; overflow-wrap: break-word; }
    .whitespace-normal { white-space: normal; }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    new Swiper(".swiper", {
        loop: false,
        speed: 600,
        slidesPerView: 1,
        spaceBetween: 0,
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        document.querySelectorAll('.banner-section .relative.z-10').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(el);
        });
    });

    const slider = document.getElementById('reviewsSlider');
    function scrollReviews(dir) {
        slider.scrollBy({ left: dir * 320, behavior: 'smooth' });
    }
</script>
@endsection
