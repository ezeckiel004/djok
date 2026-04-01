@extends('layouts.main')

@section('title', trans('formation.title'))

@section('content')
<!-- Messages de succès/erreur - Style sobre -->
<div class="container px-4 mx-auto md:px-6">
    @if(session('success'))
    <div class="mt-6 mb-6">
        <div class="p-4" style="background: #111; border-left: 4px solid #b89449;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle" style="color: #b89449;"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-white">{{ session('success') }}</p>
                    @if(str_contains(session('success'), trans('formation.success_title')))
                    <p class="mt-1 text-sm text-gray-300">
                        {{ trans('formation.success_message') }}
                        <a href="{{ route('client.formations.index') }}"
                            class="font-semibold underline hover:text-gray-100">
                            {{ trans('formation.see_trainings') }}
                        </a>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mt-6 mb-6">
        <div class="p-4" style="background: #2a0f0f; border-left: 4px solid #f56565;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle" style="color: #f56565;"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-white">{{ session('error') }}</p>
                    <p class="mt-1 text-sm text-gray-300">
                        {{ trans('formation.error_contact_support', ['phone' => '01 76 38 00 17']) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="mt-6 mb-6">
        <div class="p-4" style="background: #0f2a3d; border-left: 4px solid #4299e1;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle" style="color: #4299e1;"></i>
                </div>
                <div class="ml-3">
                    <p class="text-white">{{ session('info') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Hero Section - Style sobre -->
<header class="relative flex items-center min-h-screen" style="background: #000;">
    <div class="absolute inset-0 bg-black">
        <img src="https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&dpr=1"
            alt="{{ trans('formation.hero_title') }}" class="object-cover w-full h-full opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.7);"></div>
    </div>

    <div class="container relative z-10 px-4 py-20 mx-auto md:px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="mb-8 text-3xl font-bold md:text-4xl lg:text-5xl" style="color: #b89449;">
                {{ trans('formation.hero_title') }}
            </h1>

            <p class="mb-10 text-lg text-gray-300 md:text-xl">
                {{ trans('formation.hero_description') }}
            </p>

            <p class="mb-12 text-lg" style="color: #b89449;">
                {{ trans('formation.hero_success') }}
            </p>

            <!-- Avantages clés - Style sobre avec centrage parfait -->
            <div class="flex flex-wrap justify-center gap-6 mb-16">
                <div class="flex flex-col items-center text-white" style="flex: 0 0 auto; min-width: 200px;">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-clock"></i>
                    </div>
                    <span class="text-sm text-center">{{ trans('formation.advantage_1') }}</span>
                </div>
                <div class="flex flex-col items-center text-white" style="flex: 0 0 auto; min-width: 200px;">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-chalkboard-teacher"></i>
                    </div>
                    <span class="text-sm text-center">{{ trans('formation.advantage_3') }}</span>
                </div>
                <div class="flex flex-col items-center text-white" style="flex: 0 0 auto; min-width: 200px;">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-car"></i>
                    </div>
                    <span class="text-sm text-center">{{ trans('formation.advantage_4') }}</span>
                </div>
            </div>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#formations"
                    class="w-full px-8 py-3 font-semibold text-center transition duration-300 sm:w-auto"
                    style="background: #b89449; color: black;">
                    {{ trans('formation.see_trainings_btn') }}
                </a>
                <a href="#inscription"
                    class="w-full px-8 py-3 font-semibold text-center transition duration-300 border sm:w-auto"
                    style="border-color: #b89449; color: #b89449;">
                    {{ trans('formation.request_quote_btn') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute transform -translate-x-1/2 bottom-8 left-1/2">
        <a href="#formations" class="text-white transition duration-300 hover:text-b89449"
            aria-label="{{ trans('formation.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Nos formations disponibles - Tableau moderne style noir -->
<section id="formations" class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-8 text-center">
            <h2 class="mb-2 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{
                trans('formation.available_trainings') }}</h2>
            <p class="max-w-3xl mx-auto text-sm italic text-gray-400 md:text-base">
                {{ trans('formation.available_desc') }}
            </p>
        </div>

        <!-- Tableau principal -->
        <div class="overflow-x-auto">
            <div class="min-w-full" style="background: #111; border-radius: 18px; overflow: hidden;">
                <!-- En-tête du tableau (desktop seulement) -->
                <div class="hidden px-4 py-3 font-bold text-center bg-black md:grid md:grid-cols-6">
                    <div class="text-white"></div>
                    <div class="py-2 text-white">{{ trans('formation.table_duration') }}</div>
                    <div class="py-2 text-white">{{ trans('formation.table_elearning') }}</div>
                    <div class="py-2 text-white">{{ trans('formation.table_exam_fees') }}</div>
                    <div class="py-2 text-white">{{ trans('formation.table_exam_vehicle') }}</div>
                    <div class="py-2 text-white"></div>
                </div>

                <!-- Lignes des formations -->
                @forelse($formations as $formation)
                <div class="formation-row group">
                    <!-- Mobile: Label en pleine largeur -->
                    <div class="md:hidden">
                        @php
                        $labelColor = $formation->type_formation == 'presentiel' ? '#2bb3e6' : '#9bc64c';
                        @endphp
                        <div class="w-full formation-label-mobile" style="background: {{ $labelColor }};">
                            <div class="px-6 py-4 text-sm font-bold leading-tight text-center text-white md:text-base">
                                @php
                                $titleParts = explode(' ', $formation->title);
                                $formattedTitle = $formation->title;

                                if (count($titleParts) >= 3) {
                                $firstWord = strtoupper($titleParts[0]);
                                $rest = implode(' ', array_slice($titleParts, 1));

                                if (str_contains($firstWord, 'THÉORIE') || str_contains($firstWord, 'E-LEARNING') ||
                                str_contains($firstWord, 'COMPLET')) {
                                $formattedTitle = $firstWord . '<br>' . $rest;
                                }
                                }
                                @endphp
                                {!! $formattedTitle !!}
                            </div>
                        </div>
                    </div>

                    <!-- Desktop: Grille normale -->
                    <div
                        class="hidden px-4 py-3 transition duration-200 border-b border-gray-800 md:grid md:grid-cols-6 md:items-center group-hover:bg-gray-900">
                        <!-- Label avec forme flèche - Première colonne -->
                        <div class="flex justify-center">
                            @php
                            $labelColor = $formation->type_formation == 'presentiel' ? '#2bb3e6' : '#9bc64c';
                            @endphp

                            <div class="formation-label-desktop" style="background: {{ $labelColor }};">
                                <div
                                    class="max-w-xs px-6 py-3 text-sm font-bold leading-tight text-white whitespace-normal md:text-base">
                                    {!! $formattedTitle ?? $formation->title !!}
                                </div>
                            </div>
                        </div>

                        <!-- Durée - Deuxième colonne -->
                        <div class="py-2 text-sm text-center text-gray-300">
                            {{ $formation->duree ?? trans('formation.table_not_available') }}
                        </div>

                        <!-- E-learning - Troisième colonne -->
                        <div class="py-2 text-sm text-center text-gray-300">
                            @if($formation->type_formation == 'e_learning')
                            {{ trans('formation.type_theoretical') }}{{ $formation->categorie == 'vtc_pratique' ? '<br>+
                            ' . trans('formation.type_practical') : '' }}
                            @else
                            {{ str_contains(strtolower($formation->format_affichage), 'présentiel') ?
                            trans('formation.type_presentiel') : $formation->format_affichage }}
                            @endif
                        </div>

                        <!-- Frais d'examen - Quatrième colonne -->
                        <div class="py-2 text-sm text-center text-gray-300">
                            {{ $formation->frais_examen == 'Inclus' ? trans('formation.table_included') :
                            trans('formation.table_not_available') }}
                        </div>

                        <!-- Véhicule examen - Cinquième colonne -->
                        <div class="py-2 text-sm text-center text-gray-300">
                            {{ $formation->location_vehicule == 'Inclus' ? trans('formation.table_included') :
                            trans('formation.table_not_available') }}
                        </div>

                        <!-- Prix avec effet hover - Sixième colonne -->
                        <div class="py-2 text-center">
                            @php
                            $priceColor = $formation->type_formation == 'presentiel' ? '#2bb3e6' : '#9bc64c';
                            @endphp

                            <a href="{{ route('formation.show', $formation->slug) }}"
                                class="relative inline-block px-4 py-2 overflow-hidden font-bold text-white transition-all duration-300 rounded-lg cursor-pointer price-btn"
                                style="background: {{ $priceColor }}; min-width: 120px;"
                                aria-label="{{ trans('formation.see_training_details') }}">
                                <span class="block text-sm transition-all duration-300 price-text md:text-base">
                                    {{ number_format($formation->price, 0, ',', ' ') }} €
                                </span>
                                <span
                                    class="absolute inset-0 flex items-center justify-center text-sm transition-all duration-300 transform translate-y-2 opacity-0 price-hover-text md:text-base">
                                    {{ trans('formation.discover_btn') }}
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Mobile: Contenu sous le label -->
                    <div class="bg-gray-900 border-b border-gray-800 md:hidden">
                        <div class="p-4 space-y-3">
                            <!-- Informations -->
                            <div class="grid grid-cols-2 gap-3 text-sm text-gray-300">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-400">{{ trans('formation.table_duration')
                                        }}:</span>
                                    <span>{{ $formation->duree ?? trans('formation.table_not_available') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-400">{{ trans('formation.table_elearning')
                                        }}:</span>
                                    <span>
                                        @if($formation->type_formation == 'e_learning')
                                        {{ trans('formation.type_theoretical') }}{{ $formation->categorie ==
                                        'vtc_pratique' ? '+ ' . trans('formation.type_practical') : '' }}
                                        @else
                                        {{ str_contains(strtolower($formation->format_affichage), 'présentiel') ?
                                        trans('formation.type_presentiel') : $formation->format_affichage }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-400">{{ trans('formation.table_exam_fees')
                                        }}:</span>
                                    <span>{{ $formation->frais_examen == 'Inclus' ? trans('formation.table_included') :
                                        trans('formation.table_not_available') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-400">{{ trans('formation.table_exam_vehicle')
                                        }}:</span>
                                    <span>{{ $formation->location_vehicule == 'Inclus' ?
                                        trans('formation.table_included') : trans('formation.table_not_available')
                                        }}</span>
                                </div>
                            </div>

                            <!-- Bouton Prix -->
                            <div class="pt-3">
                                @php
                                $priceColor = $formation->type_formation == 'presentiel' ? '#2bb3e6' : '#9bc64c';
                                @endphp
                                <a href="{{ route('formation.show', $formation->slug) }}"
                                    class="relative inline-block w-full px-4 py-3 overflow-hidden font-bold text-center text-white transition-all duration-300 rounded-lg cursor-pointer price-btn"
                                    style="background: {{ $priceColor }};"
                                    aria-label="{{ trans('formation.see_training_details') }}">
                                    <span class="block text-base transition-all duration-300 price-text">
                                        {{ number_format($formation->price, 0, ',', ' ') }} €
                                    </span>
                                    <span
                                        class="absolute inset-0 flex items-center justify-center text-base transition-all duration-300 transform translate-y-2 opacity-0 price-hover-text">
                                        {{ trans('formation.discover_btn') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Message aucune formation -->
                <div class="col-span-6 py-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-gray-900 rounded-full">
                        <i class="text-xl text-gray-400 fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="mb-1 text-base font-semibold text-white">{{ trans('formation.no_trainings') }}</h3>
                    <p class="text-sm text-gray-400">{{ trans('formation.no_trainings_desc') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Section cartes avec détail -->
        <div class="mt-12">
            <div class="mb-8 text-center">
                <h2 class="mb-2 text-xl font-bold md:text-2xl" style="color: #b89449;">{{
                    trans('formation.detail_title') }}</h2>
                <p class="max-w-3xl mx-auto text-sm text-gray-400 md:text-base">
                    {{ trans('formation.detail_desc') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($formations as $formation)
                <div
                    class="overflow-hidden transition duration-300 bg-gray-900 border border-gray-800 rounded-lg hover:border-gray-700">
                    <!-- En-tête avec badge -->
                    @php
                    $videoMedia = $formation->media->where('type', 'video')->first();
                    $badgeColor = $formation->type_formation === 'e_learning' ? '#46b94c' : '#b89449';
                    @endphp

                    <div class="relative h-40" style="background: #000;">
                        @if($videoMedia && $videoMedia->thumbnail_path)
                        <img src="{{ $videoMedia->thumbnail_url }}" alt="{{ $formation->title }}"
                            class="object-cover w-full h-full opacity-70">
                        @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <i class="mb-2 text-2xl text-white fas fa-graduation-cap"></i>
                                <p class="text-sm font-semibold text-white">{{ Str::limit($formation->title, 30) }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Badge type de formation -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded"
                                style="background: {{ $badgeColor }};">
                                {{ $formation->type_formation === 'e_learning' ? trans('formation.type_online') :
                                trans('formation.type_presentiel') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <!-- Catégorie -->
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
                                style="background: #222; color: #ddd;">
                                @if($formation->categorie === 'vtc_theorique')
                                {{ trans('formation.category_vtc_theoretical') }}
                                @elseif($formation->categorie === 'vtc_pratique')
                                {{ trans('formation.category_vtc_practical') }}
                                @elseif($formation->categorie === 'e_learning')
                                {{ trans('formation.category_elearning') }}
                                @else
                                {{ trans('formation.category_renewal') }}
                                @endif
                            </span>
                        </div>

                        <!-- Titre -->
                        <h3 class="mb-1 text-base font-bold text-white truncate">{{ $formation->title }}</h3>

                        <!-- Description courte -->
                        <p class="mb-3 text-xs text-gray-400">
                            {{ Str::limit(strip_tags($formation->description), 80) }}
                        </p>

                        <!-- Stats -->
                        <div class="flex items-center justify-between mb-3 text-xs text-gray-500">
                            <div class="flex items-center">
                                <i class="mr-1 text-gray-400 fas fa-clock"></i>
                                <span>{{ $formation->duree ?? trans('formation.table_not_available') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="mr-1 text-gray-400 fas fa-video"></i>
                                <span>{{ $formation->media->where('type', 'video')->count() }} {{
                                    trans('formation.videos') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="mr-1 text-gray-400 fas fa-file-pdf"></i>
                                <span>{{ $formation->media->where('type', 'pdf')->count() }} {{ trans('formation.pdfs')
                                    }}</span>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="text-lg font-bold" style="color: #b89449;">
                                    {{ number_format($formation->price, 0, ',', ' ') }} €
                                </span>
                                <span class="block text-xs text-gray-500">{{ trans('formation.vat_included') }}</span>
                            </div>

                            <!-- Frais inclus -->
                            <div class="text-right">
                                @if($formation->frais_examen === 'Inclus')
                                <span class="px-2 py-1 text-xs rounded" style="background: #064e3b; color: #a7f3d0;">{{
                                    trans('formation.exam_included') }}</span>
                                @endif
                                @if($formation->location_vehicule === 'Inclus')
                                <span class="block px-2 py-1 mt-1 text-xs rounded"
                                    style="background: #064e3b; color: #a7f3d0;">{{ trans('formation.vehicle_included')
                                    }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="space-y-2">
                            <!-- Bouton Programme PDF - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                            @if($formation->programme_pdf_exists)
                            <a href="{{ $formation->programme_pdf_route }}" target="_blank"
                                class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-semibold transition-all duration-300 rounded hover:bg-blue-800"
                                style="background: #1e40af; color: white;"
                                title="{{ trans('formation.download_training_program') }}"
                                onclick="trackPdfView('{{ $formation->title }}')">
                                <i class="mr-2 fas fa-file-pdf"></i>
                                {{ trans('formation.download_program') }}
                            </a>
                            @endif

                            <!-- Bouton Détails -->
                            <a href="{{ route('formation.show', $formation->slug) }}"
                                class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-semibold transition-all duration-300 rounded hover:bg-gray-800"
                                style="background: #111; color: white;">
                                <i class="mr-2 fas fa-info-circle"></i>
                                {{ trans('formation.see_details') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($formations->count() === 0)
            <div class="py-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-gray-900 rounded-full">
                    <i class="text-xl text-gray-400 fas fa-graduation-cap"></i>
                </div>
                <h3 class="mb-1 text-base font-semibold text-white">{{ trans('formation.no_trainings') }}</h3>
                <p class="text-sm text-gray-400">{{ trans('formation.no_trainings_desc') }}</p>
            </div>
            @endif
        </div>

        <!-- Boutons d'action -->
        <div class="flex flex-col justify-center gap-3 mt-8 sm:flex-row">
            <a href="#inscription"
                class="inline-flex items-center px-6 py-2 text-base font-semibold transition-all duration-300"
                style="background: #b89449; color: black;">
                {{ trans('formation.register_now') }}
            </a>
            {{-- <a href="#" class="inline-flex items-center px-6 py-2 text-base font-semibold transition-all duration-300"
                style="background: #111; color: white; border: 1px solid #333;">
                {{ trans('formation.download_brochure') }}
            </a> --}}
        </div>
    </div>
</section>

<!-- Programme officiel VTC - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{ trans('formation.program_title')
                }}</h2>
            <p class="max-w-3xl mx-auto text-gray-400">
                {{ trans('formation.program_desc') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Épreuve A -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_a') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 3</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_a_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 45 minutes • {{
                    trans('formation.questions') }} : 5 QRC + 10 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_a_desc') }}</p>
            </div>

            <!-- Épreuve B -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_b') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 2</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_b_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 45 minutes • {{
                    trans('formation.questions') }} : 2 QRC + 16 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_b_desc') }}</p>
            </div>

            <!-- Épreuve C -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_c') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 3</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_c_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 30 minutes • {{
                    trans('formation.questions') }} : 20 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_c_desc') }}</p>
            </div>

            <!-- Épreuve D -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_d') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 2</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_d_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 30 minutes • {{
                    trans('formation.questions') }} : 3 QRC + 7 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_d_desc') }}</p>
            </div>

            <!-- Épreuve E -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_e') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 1</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_e_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 30 minutes • {{
                    trans('formation.questions') }} : 20 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_e_desc') }}</p>
            </div>

            <!-- Épreuve F -->
            <div class="p-6 border-l-4" style="background: #1a1a1a; border-color: #b89449;">
                <div class="flex items-start mb-4">
                    <div class="px-3 py-1 mr-3 text-sm font-semibold" style="background: #b89449; color: black;">
                        {{ trans('formation.test_f') }}
                    </div>
                    <span class="text-sm text-gray-400">{{ trans('formation.coefficient') }} : 3</span>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">{{ trans('formation.test_f_title') }}</h3>
                <p class="mb-4 text-gray-400">{{ trans('formation.duration') }} : 30 minutes • {{
                    trans('formation.questions') }} : 4 QRC + 12 QCM</p>
                <p class="text-gray-300">{{ trans('formation.test_f_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Formation pratique VTC - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="grid items-center grid-cols-1 gap-8 lg:grid-cols-2 md:gap-12">
            <div>
                <h2 class="mb-6 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{
                    trans('formation.practical_title') }}</h2>
                <p class="mb-6 text-gray-400">
                    {{ trans('formation.practical_desc') }}
                </p>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-car" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.practical_1') }}</h4>
                            <p class="text-sm text-gray-400">{{ trans('formation.practical_1_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-comments" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.practical_2') }}</h4>
                            <p class="text-sm text-gray-400">{{ trans('formation.practical_2_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-exclamation-triangle" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.practical_3') }}</h4>
                            <p class="text-sm text-gray-400">{{ trans('formation.practical_3_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8" style="background: #111;">
                <h3 class="mb-4 text-lg font-bold text-white md:text-xl">{{ trans('formation.evaluation_title') }}</h3>
                <p class="mb-6 text-gray-400">{{ trans('formation.evaluation_desc') }}</p>
                <ul class="space-y-3">
                    <li class="flex items-center">
                        <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                        <span class="text-white">{{ trans('formation.evaluation_1') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                        <span class="text-white">{{ trans('formation.evaluation_2') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                        <span class="text-white">{{ trans('formation.evaluation_3') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                        <span class="text-white">{{ trans('formation.evaluation_4') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Formation e-learning - Style sobre -->
{{-- <section class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="grid items-center grid-cols-1 gap-8 lg:grid-cols-2 md:gap-12">
            <div class="text-white">
                <h2 class="mb-6 text-2xl font-bold md:text-3xl">{{ trans('formation.elearning_title') }}</h2>
                <p class="mb-6 text-gray-400">
                    {{ trans('formation.elearning_desc') }}
                </p>

                <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2">
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-video" style="color: #b89449;"></i>
                        <span>{{ trans('formation.elearning_1') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-question-circle" style="color: #b89449;"></i>
                        <span>{{ trans('formation.elearning_2') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-user-tie" style="color: #b89449;"></i>
                        <span>{{ trans('formation.elearning_3') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-calendar-alt" style="color: #b89449;"></i>
                        <span>{{ trans('formation.elearning_4') }}</span>
                    </div>
                </div>

                <a href="#formations"
                    class="inline-flex items-center px-8 py-3 font-semibold transition-all duration-300"
                    style="background: #b89449; color: black;">
                    <i class="mr-3 fas fa-laptop"></i>{{ trans('formation.discover_online') }}
                </a>
            </div>

            <div class="p-6 md:p-8" style="background: #111; color: white;">
                <h3 class="mb-4 text-lg font-bold md:text-xl">{{ trans('formation.continuous_title') }}</h3>
                <p class="mb-4 text-gray-400">
                    {{ trans('formation.continuous_desc') }}
                </p>
                <div class="p-4" style="background: #222;">
                    <div>
                        <h4 class="font-bold">{{ trans('formation.continuous_goals') }}</h4>
                        <p class="text-sm text-gray-400">{{ trans('formation.continuous_goals_desc') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold md:text-3xl" style="color: #b89449;">{{
                            trans('formation.continuous_price') }}</div>
                        <div class="text-sm text-gray-400">{{ trans('formation.continuous_price_desc') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Conditions d'inscription - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 md:gap-12">
            <div>
                <h2 class="mb-6 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{
                    trans('formation.conditions_title') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-passport" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.condition_1') }}</h4>
                            <p class="text-gray-400">{{ trans('formation.condition_1_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-id-card" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.condition_2') }}</h4>
                            <p class="text-gray-400">{{ trans('formation.condition_2_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-heartbeat" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.condition_3') }}</h4>
                            <p class="text-gray-400">{{ trans('formation.condition_3_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-gavel" style="color: #b89449;"></i>
                        <div>
                            <h4 class="font-semibold text-white">{{ trans('formation.condition_4') }}</h4>
                            <p class="text-gray-400">{{ trans('formation.condition_4_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 mt-8" style="background: #b89449; color: black;">
                    <p>
                        <i class="mr-2 fas fa-info-circle"></i>
                        {{ trans('formation.psc1_info') }}
                    </p>
                </div>
            </div>

            <div>
                <h2 class="mb-6 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{
                    trans('formation.dossier_title') }}
                </h2>
                <p class="mb-6 text-gray-400">
                    {{ trans('formation.dossier_desc') }}
                </p>

                <div class="p-6 mb-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <h4 class="mb-4 font-bold text-white">{{ trans('formation.documents_title') }}</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-file-alt" style="color: #60a5fa;"></i>
                            <span class="text-white">{{ trans('formation.document_1') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-id-card" style="color: #60a5fa;"></i>
                            <span class="text-white">{{ trans('formation.document_2') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-home" style="color: #60a5fa;"></i>
                            <span class="text-white">{{ trans('formation.document_3') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-camera" style="color: #60a5fa;"></i>
                            <span class="text-white">{{ trans('formation.document_4') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-euro-sign" style="color: #60a5fa;"></i>
                            <span class="text-white">{{ trans('formation.document_5') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="p-4" style="background: #064e3b; border: 1px solid #047857;">
                    <p class="text-white">
                        <i class="mr-2 fas fa-check-circle" style="color: #a7f3d0;"></i>
                        {{ trans('formation.dossier_help') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pourquoi choisir DJOK PRESTIGE - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{ trans('formation.why_title') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 lg:grid-cols-3">
            {{-- <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-certificate"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_1') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_1_desc') }}</p>
            </div> --}}

            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_2') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_2_desc') }}</p>
            </div>

            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_3') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_3_desc') }}</p>
            </div>

            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_4') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_4_desc') }}</p>
            </div>

            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-car"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_5') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_5_desc') }}</p>
            </div>

            <div class="p-6" style="background: #111; border: 1px solid #333;">
                <div class="mb-4 text-xl" style="color: #b89449;">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold text-white">{{ trans('formation.why_6') }}</h4>
                <p class="text-gray-400">{{ trans('formation.why_6_desc') }}</p>
            </div>
        </div>

        <div class="text-center">
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#formations"
                    class="inline-flex items-center px-8 py-3 font-semibold transition-all duration-300"
                    style="background: #b89449; color: black;">
                    <i class="mr-3 fas fa-user-plus"></i>{{ trans('formation.register_now_btn') }}
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center px-8 py-3 font-semibold transition-all duration-300"
                    style="background: #111; color: white; border: 1px solid #333;">
                    <i class="mr-3 fas fa-phone"></i>{{ trans('formation.call_back_btn') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Accompagnement post-formation - Style sobre -->
<section class="py-16" style="background: #b89449; color: black;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl">{{ trans('formation.post_formation_title') }}</h2>
            <p class="max-w-3xl mx-auto text-lg">
                {{ trans('formation.post_formation_desc') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-6" style="background: rgba(255, 255, 255, 0.2);">
                <div class="mb-4 text-xl">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold">{{ trans('formation.post_1') }}</h4>
                <p>{{ trans('formation.post_1_desc') }}</p>
            </div>

            <div class="p-6" style="background: rgba(255, 255, 255, 0.2);">
                <div class="mb-4 text-xl">
                    <i class="fas fa-calculator"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold">{{ trans('formation.post_2') }}</h4>
                <p>{{ trans('formation.post_2_desc') }}</p>
            </div>

            <div class="p-6" style="background: rgba(255, 255, 255, 0.2);">
                <div class="mb-4 text-xl">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold">{{ trans('formation.post_3') }}</h4>
                <p>{{ trans('formation.post_3_desc') }}</p>
            </div>

            <div class="p-6" style="background: rgba(255, 255, 255, 0.2);">
                <div class="mb-4 text-xl">
                    <i class="fas fa-key"></i>
                </div>
                <h4 class="mb-2 text-lg font-bold">{{ trans('formation.post_4') }}</h4>
                <p>{{ trans('formation.post_4_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Section inscription - Style sobre -->
<section id="inscription" class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl p-6 mx-auto md:p-8" style="background: #1a1a1a; border: 1px solid #333;">
            <h2 class="mb-8 text-2xl font-bold text-center md:text-3xl" style="color: #b89449;">{{
                trans('formation.contact_title') }}</h2>

            @if(session('success') && !str_contains(session('success'), trans('formation.success_title')))
            <div class="p-4 mb-6" style="background: #064e3b; border: 1px solid #047857;">
                <div class="flex items-center">
                    <i class="mr-3 fas fa-check-circle" style="color: #a7f3d0;"></i>
                    <div>
                        <h4 class="font-bold text-white">{{ trans('formation.contact_success') }}</h4>
                        <p class="text-green-100">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- AFFICHAGE DES ERREURS -->
            @if($errors->any())
            <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;">
                <div class="flex items-center">
                    <i class="mr-3 fas fa-exclamation-circle" style="color: #fca5a5;"></i>
                    <div>
                        <h4 class="font-bold text-white">{{ trans('formation.contact_errors') }}</h4>
                        <ul class="mt-1 text-sm text-red-100">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-lg font-bold text-white md:text-xl">{{ trans('formation.form_contact') }}</h3>
                    <p class="mb-6 text-gray-400">{{ trans('formation.form_desc') }}</p>

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf

                        <!-- CHAMP CACHÉ POUR SERVICE_ID -->
                        <input type="hidden" name="service_id" value="autre">

                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2" style="color: #ddd;">{{ trans('formation.full_name')
                                    }}</label>
                                <input type="text" name="nom" value="{{ old('nom') }}" required
                                    class="w-full px-4 py-3 {{ $errors->has('nom') ? 'border-red-500' : 'border-gray-600' }}"
                                    style="background: #111; border: 1px solid #444; color: white;">
                                @error('nom')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2" style="color: #ddd;">{{ trans('formation.email') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }}"
                                    style="background: #111; border: 1px solid #444; color: white;">
                                @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2" style="color: #ddd;">{{ trans('formation.phone') }}</label>
                                <input type="tel" name="telephone" value="{{ old('telephone') }}"
                                    class="w-full px-4 py-3 {{ $errors->has('telephone') ? 'border-red-500' : 'border-gray-600' }}"
                                    style="background: #111; border: 1px solid #444; color: white;"
                                    placeholder="{{ trans('formation.phone_placeholder') }}">
                                @error('telephone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2" style="color: #ddd;">{{ trans('formation.interested_training')
                                    }}</label>
                                <select name="autre_service" required
                                    class="w-full px-4 py-3 {{ $errors->has('autre_service') ? 'border-red-500' : 'border-gray-600' }}"
                                    style="background: #111; border: 1px solid #444; color: white;">
                                    <option value="" style="color: #666;">{{ trans('formation.select_training') }}
                                    </option>
                                    @foreach($formations as $formation)
                                    <option value="{{ $formation->title }}" {{ old('autre_service')==$formation->title ?
                                        'selected' : '' }} style="color: white;">
                                        {{ $formation->title }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('autre_service')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2" style="color: #ddd;">{{ trans('formation.message') }}</label>
                                <textarea name="message" rows="4" required
                                    class="w-full px-4 py-3 {{ $errors->has('message') ? 'border-red-500' : 'border-gray-600' }}"
                                    style="background: #111; border: 1px solid #444; color: white;"
                                    placeholder="{{ trans('formation.message_placeholder') }}">{{ old('message') }}</textarea>
                                @error('message')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center w-full px-6 py-4 mt-6 font-semibold transition-all duration-300"
                                style="background: #b89449; color: black;">
                                <i class="mr-2 fas fa-paper-plane"></i>
                                {{ trans('formation.send_request') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    <h3 class="mb-4 text-lg font-bold text-white md:text-xl">{{ trans('formation.practical_info') }}
                    </h3>

                    <div class="space-y-6">
                        <div class="p-4" style="background: #1e3a8a;">
                            <h4 class="mb-2 font-bold text-white">{{ trans('formation.quick_contact') }}</h4>
                            <p class="text-blue-100">
                                <i class="mr-2 fas fa-phone-alt"></i>
                                {{ trans('formation.phone_label') }}
                                <a href="tel:0176380017" class="font-semibold text-white hover:underline">06 99 16 44 55</a>
                            </p>
                            <p class="mt-2 text-blue-100">
                                <i class="mr-2 fas fa-envelope"></i>
                                {{ trans('formation.email_label') }}
                                <a href="mailto:formation@djokprestige.com"
                                    class="font-semibold text-white hover:underline">contact@djokprestige.com</a>
                            </p>
                        </div>

                        <div class="p-4" style="background: #064e3b;">
                            <h4 class="mb-2 font-bold text-white">{{ trans('formation.opening_hours') }}</h4>
                            <p class="text-green-100">
                                <i class="mr-2 fas fa-clock"></i>
                                {{ trans('formation.weekdays') }}
                            </p>
                            <p class="mt-2 text-green-100">
                                <i class="mr-2 fas fa-clock"></i>
                                {{ trans('formation.saturday') }}
                            </p>
                        </div>

                        <div class="p-4" style="background: #78350f;">
                            <h4 class="mb-2 font-bold text-white">{{ trans('formation.center_address') }}</h4>
                            <p class="text-yellow-100">
                                <i class="mr-2 fas fa-map-marker-alt"></i>
                                {!! trans('formation.address') !!}
                            </p>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('formation') }}"
                                class="inline-flex items-center px-8 py-3 font-semibold transition-all duration-300"
                                style="background: #111; color: white; border: 1px solid #333;">
                                <i class="mr-3 fas fa-graduation-cap"></i>{{ trans('formation.review_trainings') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styles pour le tableau moderne - CORRIGÉ */
    .formation-label-desktop {
        position: relative;
        color: #fff;
        font-weight: bold;
        padding: 8px 16px;
        padding-right: 24px;
        min-width: 160px;
        max-width: 200px;
        line-height: 1.2;
        text-align: center;
        clip-path: polygon(0 0, 92% 0, 100% 50%, 92% 100%, 0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .formation-label-mobile {
        position: relative;
        color: #fff;
        font-weight: bold;
        padding: 12px 16px;
        padding-right: 20px;
        line-height: 1.2;
        text-align: center;
        clip-path: polygon(0 0, 96% 0, 100% 50%, 96% 100%, 0 100%);
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60px;
    }

    .formation-label-desktop>div,
    .formation-label-mobile>div {
        width: 100%;
        padding: 0 8px;
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .price-btn {
        position: relative;
        min-height: 40px;
        transition: all 0.3s ease;
        display: inline-block;
        overflow: hidden;
    }

    .price-btn:hover .price-text,
    .price-btn:active .price-text,
    .price-btn.touch-active .price-text {
        opacity: 0;
        transform: translateY(-8px);
    }

    .price-btn:hover .price-hover-text,
    .price-btn:active .price-hover-text,
    .price-btn.touch-active .price-hover-text {
        opacity: 1;
        transform: translateY(0);
    }

    .price-text {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease;
        display: block;
        width: 100%;
    }

    .price-hover-text {
        opacity: 0;
        transform: translateY(8px);
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 1024px) {
        .price-btn {
            min-height: 48px;
        }
    }

    .formation-row {
        border-bottom: 1px solid #333;
    }

    .formation-row:last-child {
        border-bottom: none;
    }

    @media (max-width: 767px) {
        .formation-label-mobile {
            border-radius: 0;
            margin-top: -1px;
        }

        .formation-row:first-child .formation-label-mobile {
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .price-btn {
            min-height: 52px;
        }
    }

    @media (min-width: 768px) {
        .formation-row:hover {
            background-color: #1f2937;
        }

        @media (min-width: 1280px) {
            .formation-label-desktop {
                max-width: 220px;
                padding-right: 28px;
                clip-path: polygon(0 0, 94% 0, 100% 50%, 94% 100%, 0 100%);
            }
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceButtons = document.querySelectorAll('.price-btn');

        priceButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                const priceText = this.querySelector('.price-text');
                const hoverText = this.querySelector('.price-hover-text');

                if(priceText && hoverText) {
                    priceText.style.opacity = '0';
                    priceText.style.transform = 'translateY(-8px)';
                    hoverText.style.opacity = '1';
                    hoverText.style.transform = 'translateY(0)';
                }
            });

            button.addEventListener('mouseleave', function() {
                const priceText = this.querySelector('.price-text');
                const hoverText = this.querySelector('.price-hover-text');

                if(priceText && hoverText) {
                    priceText.style.opacity = '1';
                    priceText.style.transform = 'translateY(0)';
                    hoverText.style.opacity = '0';
                    hoverText.style.transform = 'translateY(8px)';
                }
            });

            button.addEventListener('touchstart', function(e) {
                e.preventDefault();
                this.classList.add('touch-active');

                const priceText = this.querySelector('.price-text');
                const hoverText = this.querySelector('.price-hover-text');

                if(priceText && hoverText) {
                    priceText.style.opacity = '0';
                    priceText.style.transform = 'translateY(-8px)';
                    hoverText.style.opacity = '1';
                    hoverText.style.transform = 'translateY(0)';
                }
            });

            button.addEventListener('touchend', function(e) {
                e.preventDefault();
                setTimeout(() => {
                    this.classList.remove('touch-active');

                    const priceText = this.querySelector('.price-text');
                    const hoverText = this.querySelector('.price-hover-text');

                    if(priceText && hoverText) {
                        priceText.style.opacity = '1';
                        priceText.style.transform = 'translateY(0)';
                        hoverText.style.opacity = '0';
                        hoverText.style.transform = 'translateY(8px)';
                    }
                }, 300);
            });

            button.addEventListener('touchmove', function(e) {
                if (this.classList.contains('touch-active')) {
                    e.preventDefault();
                }
            });
        });

        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
    });
</script>
@endsection
