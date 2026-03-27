@extends('layouts.main')

@section('title', str_replace(':title', $formation->title, __('formation_show.title')))

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
                    <h3 class="text-lg font-semibold text-white">{{ __('formation_show.success.title') }}</h3>
                    <div class="mt-1 text-green-100">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove();"
                class="text-green-300 hover:text-white" aria-label="{{ __('formation_show.close_alert') }}">
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
    }, 8000);
</script>
@endif

<!-- Hero Section - Style sobre -->
<section class="relative py-12" style="background: #000;">
    <div class="absolute inset-0">
        <div class="absolute inset-0 opacity-60" style="background: linear-gradient(135deg, #0a0a0a 0%, #111 100%);">
        </div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="lg:w-2/3 mb-8 lg:mb-0">
                <!-- Breadcrumb -->
                <nav class="mb-4" aria-label="breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-var(--gold)" style="color: var(--gold);">
                                <i class="fas fa-home"></i> {{ __('formation_show.breadcrumb_home') }}
                            </a>
                        </li>
                        <li style="color: #666;">
                            <i class="fas fa-chevron-right"></i>
                        </li>
                        <li>
                            <a href="{{ route('formation') }}" class="hover:text-var(--gold)"
                                style="color: var(--gold);">
                                {{ __('formation_show.breadcrumb_formations') }}
                            </a>
                        </li>
                        <li style="color: #666;">
                            <i class="fas fa-chevron-right"></i>
                        </li>
                        <li class="font-semibold" style="color: white;">
                            {{ str_replace(':title', $formation->title, __('formation_show.breadcrumb_current')) }}
                        </li>
                    </ol>
                </nav>

                <h1 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--gold);">{{ $formation->title }}</h1>

                <!-- Badges - Style sobre -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <!-- Format -->
                    <span class="px-3 py-1.5 text-sm font-medium rounded-full"
                        style="background: rgba(var(--gold-rgb), 0.2); color: var(--gold);">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                        {{ __('formation_show.badge_format') }}: {{ $formation->format_affichage ??
                        ucfirst($formation->format_type) }}
                    </span>

                    <!-- Type -->
                    <span class="px-3 py-1.5 text-sm font-medium rounded-full"
                        style="background: {{ $formation->type_formation === 'e_learning' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(var(--gold-rgb), 0.2)' }}; color: {{ $formation->type_formation === 'e_learning' ? '#22c55e' : 'var(--gold)' }};">
                        {{ $formation->type_formation === 'e_learning' ? __('formation_show.badge_type_online') :
                        __('formation_show.badge_type_presentiel') }}
                    </span>

                    <!-- Catégorie -->
                    <span class="px-3 py-1.5 text-sm font-medium rounded-full"
                        style="background: rgba(59, 130, 246, 0.2); color: #3b82f6;">
                        @if($formation->categorie === 'vtc_theorique')
                        {{ __('formation_show.category_vtc_theoretical') }}
                        @elseif($formation->categorie === 'vtc_pratique')
                        {{ __('formation_show.category_vtc_practical') }}
                        @elseif($formation->categorie === 'e_learning')
                        {{ __('formation_show.category_elearning') }}
                        @elseif($formation->categorie === 'renouvellement')
                        {{ __('formation_show.category_renewal') }}
                        @elseif($formation->categorie === 'international')
                        {{ __('formation_show.category_international') }}
                        @endif
                    </span>

                    <!-- Certification -->
                    @if($formation->is_certified)
                    <span class="px-3 py-1.5 text-sm font-medium rounded-full"
                        style="background: rgba(var(--gold-rgb), 0.2); color: var(--gold);">
                        <i class="fas fa-certificate mr-1"></i>{{ __('formation_show.badge_certified') }}
                    </span>
                    @endif
                </div>

                <p class="text-lg mb-6 max-w-3xl" style="color: #ccc;">
                    {{ Str::limit(strip_tags($formation->description), 200) }}
                </p>

                <!-- Bouton Télécharger le programme PDF - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                @if($formation->programme_pdf_exists)
                <div class="mt-6">
                    <a href="{{ $formation->programme_pdf_route }}" target="_blank"
                        class="inline-flex items-center px-6 py-3 font-semibold transition-all duration-300 hover:bg-blue-800 rounded-lg"
                        style="background: #1e40af; color: white;"
                        title="{{ __('formation_show.download_program_details') }}"
                        onclick="trackPdfView('{{ $formation->title }}')">
                        <i class="mr-3 fas fa-file-pdf"></i>
                        {{ __('formation_show.download_program_pdf') }}
                    </a>
                    <p class="text-sm mt-2" style="color: #888;">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('formation_show.program_info') }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Carte prix et action - Style sobre -->
            <div class="lg:w-1/3">
                <div class="p-6" style="background: #111; border: 1px solid #333;">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold mb-1" style="color: var(--gold);">
                            {{ number_format($formation->price, 0, ',', ' ') }}€
                        </div>
                        <div style="color: #888;">{{ __('formation_show.price_ttc') }}</div>
                    </div>

                    <!-- Durée et médias -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: white;">
                                {{ $formation->duration_hours }}h
                            </div>
                            <div class="text-sm" style="color: #888;">{{ __('formation_show.duration_label') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" style="color: white;">
                                {{ $formation->media->count() }}
                            </div>
                            <div class="text-sm" style="color: #888;">{{ __('formation_show.resources_label') }}</div>
                        </div>
                    </div>

                    <!-- Services inclus -->
                    <div class="space-y-3 mb-6">
                        @if($formation->frais_examen === 'Inclus')
                        <div class="flex items-center" style="color: #22c55e;">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ __('formation_show.exam_fees_included') }}</span>
                        </div>
                        @endif

                        @if($formation->location_vehicule === 'Inclus')
                        <div class="flex items-center" style="color: #22c55e;">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ __('formation_show.vehicle_rental_included') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Bouton principal -->
                    @if($formation->type_formation === 'presentiel')
                    <a href="{{ route('formation.inscrire.presentiel', $formation->id) }}"
                        class="w-full mb-3 inline-flex items-center justify-center px-6 py-3.5 font-semibold rounded transition-all duration-300 transform hover:scale-105"
                        style="background: var(--gold); color: black;">
                        <i class="fas fa-user-plus mr-2"></i>
                        {{ __('formation_show.register_button') }}
                    </a>
                    @elseif($formation->type_formation === 'e_learning')
                    <a href="{{ route('formation.acheter.elearning', $formation->id) }}"
                        class="w-full mb-3 inline-flex items-center justify-center px-6 py-3.5 font-semibold rounded transition-all duration-300 transform hover:scale-105"
                        style="background: #22c55e; color: white;">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        {{ __('formation_show.buy_button') }}
                    </a>
                    @endif

                    <!-- Bouton secondaire -->
                    <a href="#contact"
                        class="w-full inline-flex items-center justify-center px-6 py-3 font-semibold rounded transition-all duration-300 border"
                        style="border-color: var(--gold); color: var(--gold);">
                        <i class="fas fa-question-circle mr-2"></i>
                        {{ __('formation_show.more_info_button') }}
                    </a>

                    <!-- Bouton Programme PDF (version compacte) - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                    @if($formation->programme_pdf_exists)
                    <div class="mt-4">
                        <a href="{{ $formation->programme_pdf_route }}" target="_blank"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 font-semibold transition-all duration-300 hover:bg-blue-800 rounded"
                            style="background: #1e40af; color: white;"
                            title="{{ __('formation_show.see_program_details') }}"
                            onclick="trackPdfView('{{ $formation->title }}')">
                            <i class="mr-2 fas fa-file-pdf"></i>
                            {{ __('formation_show.program_pdf_button') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal -->
<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Colonne gauche (2/3) -->
        <div class="lg:col-span-2 space-y-12">
            <!-- Description complète -->
            <section>
                <h2 class="text-2xl font-bold mb-6 pb-3" style="color: var(--gold); border-bottom: 1px solid #333;">
                    <i class="fas fa-align-left mr-2"></i>
                    {{ __('formation_show.full_description') }}
                </h2>
                <div class="prose max-w-none" style="color: #ccc;">
                    {!! nl2br(e($formation->description)) !!}
                </div>
            </section>

            <!-- Programme détaillé -->
            @if($formation->program && count($formation->program) > 0)
            <section>
                <h2 class="text-2xl font-bold mb-6 pb-3" style="color: var(--gold); border-bottom: 1px solid #333;">
                    <i class="fas fa-list-ol mr-2"></i>
                    {{ __('formation_show.detailed_program') }}
                </h2>
                <div class="space-y-4">
                    @foreach($formation->program as $index => $item)
                    <div class="rounded-lg p-4 transition-colors duration-200"
                        style="background: #111; border: 1px solid #333;">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center mr-3"
                                style="background: rgba(var(--gold-rgb), 0.2); color: var(--gold);">
                                <span class="font-bold text-sm">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1" style="color: white;">{{
                                    __('formation_show.formation_program') }} {{ $index + 1 }}</h3>
                                <p style="color: #ccc;">{{ $item }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- Fichiers multimédias -->
            @php
            $pdfs = $formation->media->where('type', 'pdf');
            $videos = $formation->media->where('type', 'video');
            @endphp

            @if($pdfs->count() > 0 || $videos->count() > 0)
            <section>
                <h2 class="text-2xl font-bold mb-6 pb-3" style="color: var(--gold); border-bottom: 1px solid #333;">
                    <i class="fas fa-photo-video mr-2"></i>
                    {{ __('formation_show.multimedia_resources') }}
                </h2>

                <!-- Message d'information -->
                <div class="mb-6 p-4 rounded-lg"
                    style="background: rgba(59, 130, 246, 0.1); border: 1px solid #3b82f6;">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-3b82f6 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-bold mb-1" style="color: #3b82f6;">{{ __('formation_show.resources_locked')
                                }}</h4>
                            <p class="text-sm" style="color: #93c5fd;">
                                {{ __('formation_show.resources_locked_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Vidéos -->
                @if($videos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 flex items-center" style="color: white;">
                        <i class="fas fa-video mr-2" style="color: #3b82f6;"></i>
                        {{ __('formation_show.videos') }} ({{ $videos->count() }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($videos as $video)
                        <div class="rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200 relative"
                            style="background: #111; border: 1px solid #333;">
                            <!-- Overlay de verrouillage -->
                            <div
                                class="absolute inset-0 bg-gray-900 bg-opacity-80 z-10 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <div class="mb-2" style="color: var(--gold);">
                                        <i class="fas fa-lock text-3xl"></i>
                                    </div>
                                    <p class="font-semibold" style="color: white;">{{ __('formation_show.locked_access')
                                        }}</p>
                                    <p class="text-sm mt-1" style="color: #ccc;">{{
                                        __('formation_show.locked_access_desc') }}</p>
                                </div>
                            </div>

                            <div class="h-40 relative" style="background: #1a1a1a;">
                                @if($video->thumbnail_path)
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}"
                                    class="w-full h-full object-cover">
                                @else
                                <div class="h-full flex items-center justify-center">
                                    <i class="fas fa-video text-3xl" style="color: #666;"></i>
                                </div>
                                @endif
                                @if($video->duration)
                                <div class="absolute bottom-2 right-2 text-xs px-2 py-1 rounded"
                                    style="background: rgba(0, 0, 0, 0.75); color: white;">
                                    {{ $video->duration }}
                                </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-medium mb-1" style="color: white;">{{ $video->title }}</h4>
                                @if($video->description)
                                <p class="text-sm mb-2" style="color: #ccc;">{{ Str::limit($video->description, 60) }}
                                </p>
                                @endif
                                <div class="flex justify-between items-center text-xs" style="color: #888;">
                                    <span>{{ $video->file_name }}</span>
                                    <span>{{ $video->file_size }}</span>
                                </div>

                                <!-- Bouton verrouillé -->
                                <div class="mt-3">
                                    <button disabled
                                        class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg cursor-not-allowed"
                                        style="background: #444; color: #888;">
                                        <i class="fas fa-lock mr-1"></i> {{ __('formation_show.locked_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- PDFs -->
                @if($pdfs->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold mb-4 flex items-center" style="color: white;">
                        <i class="fas fa-file-pdf mr-2" style="color: #ef4444;"></i>
                        {{ __('formation_show.documents_pdf') }} ({{ $pdfs->count() }})
                    </h3>
                    <div class="space-y-3">
                        @foreach($pdfs as $pdf)
                        <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 transition-colors duration-200 relative"
                            style="background: #111; border: 1px solid #333;">
                            <!-- Overlay de verrouillage -->
                            <div
                                class="absolute inset-0 bg-gray-900 bg-opacity-90 z-10 flex items-center justify-center rounded-lg">
                                <div class="text-center p-4">
                                    <div class="mb-2" style="color: var(--gold);">
                                        <i class="fas fa-lock text-2xl"></i>
                                    </div>
                                    <p class="font-semibold" style="color: white;">{{
                                        __('formation_show.locked_document') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-lg flex items-center justify-center mr-4"
                                    style="background: rgba(239, 68, 68, 0.2);">
                                    <i class="fas fa-file-pdf text-xl" style="color: #ef4444;"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium" style="color: white;">{{ $pdf->title }}</h4>
                                    @if($pdf->description)
                                    <p class="text-sm" style="color: #ccc;">{{ Str::limit($pdf->description, 80) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm mb-1" style="color: #888;">{{ $pdf->file_size }}</div>

                                <!-- Bouton verrouillé -->
                                <button disabled
                                    class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg cursor-not-allowed"
                                    style="background: #444; color: #888;">
                                    <i class="fas fa-lock mr-1"></i> {{ __('formation_show.locked_button') }}
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </section>
            @endif

            <!-- Services inclus -->
            @if($formation->included_services && count($formation->included_services) > 0)
            <section>
                <h2 class="text-2xl font-bold mb-6 pb-3" style="color: var(--gold); border-bottom: 1px solid #333;">
                    <i class="fas fa-gift mr-2"></i>
                    {{ __('formation_show.included_services') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($formation->included_services as $service)
                    <div class="flex items-center p-4 rounded-lg border"
                        style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3);">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center mr-3"
                            style="background: rgba(34, 197, 94, 0.2);">
                            <i class="fas fa-check text-sm" style="color: #22c55e;"></i>
                        </div>
                        <span style="color: white;">{{ $service }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- Prérequis -->
            @if($formation->requirements && count($formation->requirements) > 0)
            <section>
                <h2 class="text-2xl font-bold mb-6 pb-3" style="color: var(--gold); border-bottom: 1px solid #333;">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    {{ __('formation_show.prerequisites') }}
                </h2>
                <ul class="space-y-3">
                    @foreach($formation->requirements as $requirement)
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-3" style="color: #22c55e;"></i>
                        <span style="color: #ccc;">{{ $requirement }}</span>
                    </li>
                    @endforeach
                </ul>
            </section>
            @endif
        </div>

        <!-- Colonne droite (1/3) -->
        <div class="space-y-8">
            <!-- Carte d'action fixe -->
            <div class="sticky top-6">
                <div class="p-6" style="background: #111; border: 1px solid #333; border-radius: 12px;">
                    <h3 class="text-xl font-bold mb-4" style="color: var(--gold);">{{
                        __('formation_show.book_formation') }}</h3>

                    <div class="space-y-4">
                        <!-- Prix -->
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold" style="color: var(--gold);">
                                {{ number_format($formation->price, 0, ',', ' ') }}€
                            </div>
                            <div style="color: #888;">{{ __('formation_show.price_ttc') }}</div>
                        </div>

                        <!-- Bouton Programme PDF dans la carte fixe - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                        @if($formation->programme_pdf_exists)
                        <div class="mb-4">
                            <a href="{{ $formation->programme_pdf_route }}" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-3 font-semibold transition-all duration-300 hover:bg-blue-800 rounded"
                                style="background: #1e40af; color: white;"
                                title="{{ __('formation_show.see_program_details') }}"
                                onclick="trackPdfView('{{ $formation->title }}')">
                                <i class="mr-2 fas fa-file-pdf"></i>
                                {{ __('formation_show.program_pdf_button') }} (PDF)
                            </a>
                            <p class="text-xs text-center mt-1" style="color: #888;">
                                {{ __('formation_show.official_program') }}
                            </p>
                        </div>
                        @endif

                        <!-- Bouton principal -->
                        @if($formation->type_formation === 'presentiel')
                        <a href="{{ route('formation.inscrire.presentiel', $formation->id) }}"
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 font-semibold rounded transition-all duration-300 transform hover:scale-105 mb-3"
                            style="background: var(--gold); color: black;">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('formation_show.register_now') }}
                        </a>
                        @elseif($formation->type_formation === 'e_learning')
                        <a href="{{ route('formation.acheter.elearning', $formation->id) }}"
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 font-semibold rounded transition-all duration-300 transform hover:scale-105 mb-3"
                            style="background: #22c55e; color: white;">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            {{ __('formation_show.buy_now') }}
                        </a>
                        @endif

                        <!-- Information sur les médias -->
                        <div class="p-4 rounded-lg" style="background: rgba(var(--gold-rgb), 0.1);">
                            <h4 class="font-bold mb-2 flex items-center" style="color: var(--gold);">
                                <i class="fas fa-video mr-2"></i>
                                {{ __('formation_show.multimedia_content') }}
                            </h4>
                            <p class="text-sm" style="color: #ccc;">
                                {!! str_replace(':count', $formation->media->count(),
                                __('formation_show.multimedia_content_desc')) !!}
                            </p>
                        </div>

                        <!-- Informations complémentaires -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span style="color: #888;">{{ __('formation_show.duration_label') }} :</span>
                                <span class="font-semibold" style="color: white;">{{ $formation->duree ??
                                    $formation->duration_hours.'h' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span style="color: #888;">{{ __('formation_show.format_label') }}</span>
                                <span class="font-semibold" style="color: white;">{{ $formation->format_affichage ??
                                    ucfirst($formation->format_type) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span style="color: #888;">{{ __('formation_show.type_label') }}</span>
                                <span class="font-semibold"
                                    style="color: {{ $formation->type_formation === 'e_learning' ? '#22c55e' : 'var(--gold)' }};">
                                    {{ $formation->type_formation === 'e_learning' ? __('formation_show.type_online') :
                                    __('formation_show.type_presentiel') }}
                                </span>
                            </div>
                        </div>

                        <!-- Contact rapide -->
                        <div class="mt-6 pt-6" style="border-top: 1px solid #333;">
                            <h4 class="font-bold mb-3" style="color: white;">{{ __('formation_show.quick_questions') }}
                            </h4>
                            <div class="space-y-2">
                                <a href="tel:0699164455"
                                    class="flex items-center transition-colors duration-200 hover:text-var(--gold)"
                                    style="color: #ccc;">
                                    <i class="fas fa-phone-alt mr-3" style="color: var(--gold);"></i>
                                    <span>{{ __('formation_show.quick_contact_phone') }}</span>
                                </a>
                                <a href="mailto:contact@djokprestige.com"
                                    class="flex items-center transition-colors duration-200 hover:text-var(--gold)"
                                    style="color: #ccc;">
                                    <i class="fas fa-envelope mr-3" style="color: var(--gold);"></i>
                                    <span>{{ __('formation_show.quick_contact_email') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formation similaire -->
            @php
            $similarFormations = App\Models\Formation::where('id', '!=', $formation->id)
            ->where('is_active', true)
            ->where('type_formation', $formation->type_formation)
            ->limit(3)
            ->get();
            @endphp

            @if($similarFormations->count() > 0)
            <div class="p-6" style="background: #111; border: 1px solid #333; border-radius: 12px;">
                <h3 class="text-lg font-bold mb-4" style="color: var(--gold);">{{
                    __('formation_show.similar_formations') }}</h3>
                <div class="space-y-4">
                    @foreach($similarFormations as $similar)
                    <a href="{{ route('formation.show', $similar->slug) }}"
                        class="block p-4 hover:shadow-md transition-shadow duration-200 border rounded-lg"
                        style="background: #1a1a1a; border-color: #333;">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold mb-1" style="color: white;">{{ Str::limit($similar->title, 40)
                                    }}</h4>
                                <div class="text-sm mb-2" style="color: #888;">{{ $similar->duree ??
                                    $similar->duration_hours.'h' }}</div>
                                <!-- Bouton Programme PDF pour les formations similaires - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                                @if($similar->programme_pdf_exists)
                                <div class="mt-2">
                                    <a href="{{ $similar->programme_pdf_route }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold transition-all duration-300 hover:bg-blue-800 rounded"
                                        style="background: #1e40af; color: white;"
                                        onclick="trackPdfView('{{ $similar->title }}')">
                                        <i class="mr-1 fas fa-file-pdf"></i>
                                        {{ __('formation_show.program_pdf_compact') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold" style="color: var(--gold);">{{
                                    number_format($similar->price, 0, ',', ' ') }}€</div>
                            </div>
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="px-2 py-1 rounded-full"
                                style="background: {{ $similar->type_formation === 'e_learning' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(var(--gold-rgb), 0.2)' }}; color: {{ $similar->type_formation === 'e_learning' ? '#22c55e' : 'var(--gold)' }};">
                                {{ $similar->type_formation === 'e_learning' ? __('formation_show.type_online') :
                                __('formation_show.type_presentiel') }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Section contact - Style sobre -->
<section id="contact" class="py-16" style="background: #111;">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--gold);">{{
                __('formation_show.contact_title') }}
            </h2>

            <!-- Messages d'erreur/succès -->
            @if(session('error'))
            <div id="error-message" class="mb-6 p-4 rounded-lg" style="background: #7f1d1d; border: 1px solid #991b1b;">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                        style="background: #dc2626;">
                        <i class="fas fa-exclamation-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">{{ __('formation_show.error.title') }}</h4>
                        <p class="text-red-100">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-lg" style="background: #7f1d1d; border: 1px solid #991b1b;">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                        style="background: #dc2626;">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">{{ __('formation_show.errors.title') }}</h4>
                        <ul class="text-red-100 list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Formulaire -->
                <div>
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6" id="contactForm">
                        @csrf

                        <input type="hidden" name="service_id" value="autre">

                        @php
                        $formationTitle = $formation->title ?? 'Formation VTC Professionnelle';
                        @endphp
                        <input type="hidden" name="autre_service" value="{{ $formationTitle }}"
                            id="autre_service_hidden">

                        <input type="hidden" name="formation_id" value="{{ $formation->id ?? '' }}">
                        <input type="hidden" name="type_demande" value="formation">

                        <div>
                            <label for="nom" class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('formation_show.full_name') }}</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                class="w-full px-4 py-3 rounded @error('nom') border-red-500 @enderror"
                                style="background: #1a1a1a; border: 1px solid #444; color: white;">
                            @error('nom')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block mb-2 font-medium" style="color: #ddd;">{{
                                    __('formation_show.email') }}</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 rounded @error('email') border-red-500 @enderror"
                                    style="background: #1a1a1a; border: 1px solid #444; color: white;">
                                @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="telephone" class="block mb-2 font-medium" style="color: #ddd;">{{
                                    __('formation_show.phone') }}</label>
                                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                    required
                                    class="w-full px-4 py-3 rounded @error('telephone') border-red-500 @enderror"
                                    style="background: #1a1a1a; border: 1px solid #444; color: white;">
                                @error('telephone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Affichage de la formation -->
                        <div>
                            <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('formation_show.formation_concerned') }}</label>
                            <div class="p-4 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                                <div class="font-semibold" style="color: white;">{{ $formation->title }}</div>
                                <div class="text-sm mt-2" style="color: #888;">
                                    <span class="inline-block mr-4">
                                        <i class="fas fa-clock mr-1"></i>{{ $formation->duree ??
                                        $formation->duration_hours.'h' }}
                                    </span>
                                    <span class="inline-block mr-4">
                                        <i class="fas fa-euro-sign mr-1"></i>{{ number_format($formation->price, 0, ',',
                                        ' ') }} €
                                    </span>
                                    <span class="inline-block">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>{{ $formation->type_formation ===
                                        'e_learning' ? __('formation_show.type_online') :
                                        __('formation_show.type_presentiel') }}
                                    </span>
                                </div>
                                <!-- Bouton Programme PDF dans le formulaire - NE S'AFFICHE QUE SI PDF UPLOADÉ -->
                                @if($formation->programme_pdf_exists)
                                <div class="mt-3">
                                    <a href="{{ $formation->programme_pdf_route }}" target="_blank"
                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold transition-all duration-300 hover:bg-blue-800 rounded"
                                        style="background: #1e40af; color: white;"
                                        onclick="trackPdfView('{{ $formation->title }}')">
                                        <i class="mr-2 fas fa-file-pdf"></i>
                                        {{ __('formation_show.program_pdf_button') }}
                                    </a>
                                </div>
                                @endif
                                <p class="text-xs mt-2" style="color: #666;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('formation_show.formation_auto') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block mb-2 font-medium" style="color: #ddd;">{{
                                __('formation_show.message_label') }}</label>
                            <textarea name="message" id="message" rows="5" required
                                class="w-full px-4 py-3 rounded @error('message') border-red-500 @enderror"
                                style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                placeholder="{{ __('formation_show.message_placeholder') }}">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" id="submitBtn"
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 font-semibold rounded transition-all duration-300 transform hover:scale-105"
                            style="background: var(--gold); color: black;">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ __('formation_show.send_request') }}
                        </button>

                        <p class="text-xs text-center mt-3" style="color: #666;">
                            <i class="fas fa-shield-alt mr-1"></i>
                            {{ __('formation_show.security_info') }}
                        </p>
                    </form>
                </div>

                <!-- Informations de contact -->
                <div>
                    <div class="p-6 rounded-lg" style="background: #1a1a1a; border: 1px solid #333;">
                        <h3 class="text-xl font-bold mb-6" style="color: var(--gold);">{{
                            __('formation_show.contact_us') }}</h3>

                        <div class="space-y-6">
                            <div>
                                <h4 class="font-semibold mb-3" style="color: white;">{{ __('formation_show.by_phone') }}
                                </h4>
                                <a href="tel:0176380017"
                                    class="flex items-center text-lg transition-colors duration-200 hover:text-var(--gold)"
                                    style="color: #ccc;">
                                    <i class="fas fa-phone-alt mr-3" style="color: var(--gold);"></i>
                                    <span>{{ __('formation_show.quick_contact_phone') }}</span>
                                </a>
                                <p class="text-sm mt-2" style="color: #888;">{{ __('formation_show.phone_hours') }}</p>
                            </div>

                            <div>
                                <h4 class="font-semibold mb-3" style="color: white;">{{ __('formation_show.by_email') }}
                                </h4>
                                <a href="mailto:formation@djokprestige.com"
                                    class="flex items-center text-lg transition-colors duration-200 hover:text-var(--gold)"
                                    style="color: #ccc;">
                                    <i class="fas fa-envelope mr-3" style="color: var(--gold);"></i>
                                    <span>{{ __('formation_show.quick_contact_email') }}</span>
                                </a>
                            </div>

                            <div>
                                <h4 class="font-semibold mb-3" style="color: white;">{{ __('formation_show.our_center')
                                    }}</h4>
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt mr-3 mt-1" style="color: var(--gold);"></i>
                                    <div>
                                        {!! __('formation_show.center_address') !!}
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ rapide -->
                            <div class="pt-6" style="border-top: 1px solid #333;">
                                <h4 class="font-semibold mb-3" style="color: white;">{{ __('formation_show.faq') }}</h4>
                                <div class="space-y-3">
                                    <div class="text-sm">
                                        <p class="font-medium" style="color: white;">{{ __('formation_show.faq_payment')
                                            }}
                                        </p>
                                        <p style="color: #888;">{{ __('formation_show.faq_payment_answer') }}</p>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-medium" style="color: white;">{{ __('formation_show.faq_start')
                                            }}</p>
                                        <p style="color: #888;">{{ __('formation_show.faq_start_answer') }}</p>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-medium" style="color: white;">{{ __('formation_show.faq_program')
                                            }}
                                        </p>
                                        <p style="color: #888;">
                                            <i class="fas fa-file-pdf mr-1" style="color: #1e40af;"></i>
                                            {{ __('formation_show.faq_program_answer') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Auto-hide des messages
        const successAlert = document.getElementById('success-alert');
        const errorMessage = document.getElementById('error-message');

        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => successAlert.remove(), 500);
            }, 8000);
        }

        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 8000);
        }

        // VÉRIFICATION CRITIQUE : S'assurer que autre_service n'est pas vide
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const autreServiceInput = document.getElementById('autre_service_hidden');
                if (!autreServiceInput || !autreServiceInput.value.trim()) {
                    e.preventDefault();
                    alert("{{ __('formation_show.formation_not_specified') }}");
                    return false;
                }

                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('formation_show.sending') }}';
                }
            });
        }

        // Animation au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        // Animer les sections
        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Scroll vers le message de succès s'il existe
        if (successAlert) {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        }

        // Scroll vers le formulaire s'il y a des erreurs
        if (document.querySelector('[class*="border-red"]')) {
            setTimeout(() => {
                document.getElementById('contact').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 300);
        }

        // Fonction de tracking pour les téléchargements PDF
        function trackPdfView(formationTitle) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'pdf_download', {
                    'event_category': 'Formation',
                    'event_label': formationTitle,
                    'value': 1
                });
            }

            fetch('/api/track-pdf-download', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    formation_title: formationTitle,
                    url: window.location.href,
                    timestamp: new Date().toISOString()
                })
            }).catch(error => console.log('Tracking error:', error));
        }

        window.trackPdfView = trackPdfView;
    });
</script>
@endsection
