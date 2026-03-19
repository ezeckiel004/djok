@extends('layouts.main')

@section('title', trans('international.title'))

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
                    <h3 class="text-lg font-semibold text-white">{{ trans('international.success_title') }}</h3>
                    <div class="mt-1 text-green-100">
                        <p>{{ session('success') }}</p>
                        @if(session('email'))
                        <p class="text-sm mt-1">
                            {!! trans('international.confirmation_email', ['email' => session('email')]) !!}
                        </p>
                        @endif
                        <p class="text-sm mt-1">
                            {{ trans('international.contact_soon') }}
                        </p>
                    </div>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove();"
                class="text-green-300 hover:text-white" aria-label="{{ trans('international.close_alert') }}">
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
        <img src="https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&dpr=1"
            alt="{{ trans('international.hero_title') }}" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.7);"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 py-20 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-8" style="color: var(--gold);">
                {{ trans('international.hero_title') }}
            </h1>

            <p class="text-lg md:text-xl text-gray-300 mb-12">
                {{ trans('international.hero_description') }}
            </p>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#formations"
                    class="w-full sm:w-auto px-8 py-3 font-semibold text-center transition duration-300"
                    style="background: var(--gold); color: black;">
                    {{ trans('international.discover_formations') }}
                </a>
                <a href="#accompagnement"
                    class="w-full sm:w-auto px-8 py-3 font-semibold text-center border transition duration-300"
                    style="border-color: var(--gold); color: var(--gold);">
                    {{ trans('international.visa_accompaniment') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
        <a href="#formations" class="text-white transition duration-300 hover:text-var(--gold)"
            aria-label="{{ trans('international.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Domaines de formation - Style sobre -->
<section id="formations" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('international.destinations_title') }}</h2>
        </div>

        <!-- Formations principales -->
<div class="mb-16">
    <h3 class="text-2xl font-semibold text-center mb-8" style="color: white;">{{
        trans('international.destinations_subtitle') }}</h3>

    <!-- Destinations professionnelles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['title' => 'Dubaï', 'desc' => 'Innovation, business international', 'icon' => 'fa-building'],
            ['title' => 'États-Unis', 'desc' => 'Entrepreneuriat et leadership', 'icon' => 'fa-chart-line'],
            ['title' => 'France & Europe', 'desc' => 'Standards européens et networking', 'icon' => 'fa-globe-europe'],
            ['title' => 'Afrique', 'desc' => 'Développement économique et opportunités', 'icon' => 'fa-tree'],
            ['title' => 'Asie', 'desc' => 'Technologie et transformation digitale', 'icon' => 'fa-microchip'],
            ['title' => 'Sur mesure', 'desc' => 'Programmes personnalisés selon vos besoins', 'icon' => 'fa-compass']
        ] as $destination)
        <div class="p-6 transition-all duration-300 hover:scale-105" style="background: #111; border: 1px solid #333;">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 flex items-center justify-center rounded-full mb-4" style="background: var(--gold);">
                    <i class="fas {{ $destination['icon'] }} text-black text-xl"></i>
                </div>
                <h4 class="text-xl font-bold mb-2" style="color: var(--gold);">{{ $destination['title'] }}</h4>
                <p class="text-gray-300">{{ $destination['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Message de personnalisation -->
    <div class="max-w-3xl mx-auto text-center p-8 mt-8" style="background: #111; border: 1px solid #333;">
        <i class="fas fa-gem text-4xl mb-4" style="color: var(--gold);"></i>
        <p class="text-gray-300">
            {{ __('international.program_custom_desc') }}
        </p>
    </div>
</div>
        <!-- Types d'événements organisés -->
<div class="mt-16">
    <h3 class="text-2xl font-semibold text-center mb-8" style="color: white;">{{
        trans('international.event_types') }}</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Séminaires d'entreprise -->
        <div class="p-6" style="background: #111; border: 1px solid #333;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center rounded-lg" style="background: var(--gold);">
                        <i class="fas fa-users text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold mb-2" style="color: var(--gold);">{{ trans('international.event_seminars') }}</h4>
                    <p class="text-gray-400">{{ trans('international.event_seminars_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Formations professionnelles internationales -->
        <div class="p-6" style="background: #111; border: 1px solid #333;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center rounded-lg" style="background: var(--gold);">
                        <i class="fas fa-globe text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold mb-2" style="color: var(--gold);">{{ trans('international.event_trainings') }}</h4>
                    <p class="text-gray-400">{{ trans('international.event_trainings_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Voyages business & networking -->
        <div class="p-6" style="background: #111; border: 1px solid #333;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center rounded-lg" style="background: var(--gold);">
                        <i class="fas fa-handshake text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold mb-2" style="color: var(--gold);">{{ trans('international.event_networking') }}</h4>
                    <p class="text-gray-400">{{ trans('international.event_networking_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Programmes leadership & management -->
        <div class="p-6" style="background: #111; border: 1px solid #333;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center rounded-lg" style="background: var(--gold);">
                        <i class="fas fa-chart-line text-black"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold mb-2" style="color: var(--gold);">{{ trans('international.event_leadership') }}</h4>
                    <p class="text-gray-400">{{ trans('international.event_leadership_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</section>

<!-- Public visé - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('international.target_audience') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Entreprises -->
            <div class="text-center p-6 transition-all duration-300 hover:scale-105" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4" style="background: #2563eb;">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-3" style="color: white;">{{ trans('international.companies') }}</h3>
                <p class="text-gray-400">{{ trans('international.companies_desc') }}</p>
            </div>

            <!-- Dirigeants & entrepreneurs -->
            <div class="text-center p-6 transition-all duration-300 hover:scale-105" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4" style="background: #b45309;">
                    <i class="fas fa-user-tie text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-3" style="color: white;">{{ trans('international.leaders') }}</h3>
                <p class="text-gray-400">{{ trans('international.leaders_desc') }}</p>
            </div>

            <!-- Organisations & institutions -->
            <div class="text-center p-6 transition-all duration-300 hover:scale-105" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4" style="background: #7e22ce;">
                    <i class="fas fa-landmark text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-3" style="color: white;">{{ trans('international.organizations') }}</h3>
                <p class="text-gray-400">{{ trans('international.organizations_desc') }}</p>
            </div>

            <!-- Startups & porteurs de projet -->
            <div class="text-center p-6 transition-all duration-300 hover:scale-105" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4" style="background: #059669;">
                    <i class="fas fa-rocket text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-3" style="color: white;">{{ trans('international.startups') }}</h3>
                <p class="text-gray-400">{{ trans('international.startups_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Accompagnement visa - Style sobre -->
<section id="accompagnement" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                    trans('international.visa_title') }}</h2>
                <p class="text-gray-400 max-w-3xl mx-auto">
                    {{ trans('international.visa_subtitle') }}
                </p>
            </div>

            <div class="p-6 md:p-8 mb-12" style="background: #111; border: 1px solid #333;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-6" style="color: white;">{{
                            trans('international.documents_provided') }}</h3>
                        <ul class="space-y-4">
                            @foreach([
                            trans('international.document_inscription'),
                            trans('international.document_accommodation'),
                            trans('international.document_payment'),
                            trans('international.document_logistic')
                            ] as $document)
                            <li class="flex items-start">
                                <i class="fas fa-file-alt mt-1 mr-3" style="color: #3b82f6;"></i>
                                <span style="color: white;">{{ $document }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold mb-6" style="color: white;">{{
                            trans('international.support_continuous') }}</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fab fa-whatsapp mt-1 mr-3" style="color: #25D366;"></i>
                                <span style="color: white;">{{ trans('international.support_whatsapp') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-plane-arrival mt-1 mr-3" style="color: #3b82f6;"></i>
                                <span style="color: white;">{{ trans('international.support_airport') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-map-signs mt-1 mr-3" style="color: #3b82f6;"></i>
                                <span style="color: white;">{{ trans('international.support_orientation') }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-headset mt-1 mr-3" style="color: #3b82f6;"></i>
                                <span style="color: white;">{{ trans('international.support_247') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="#contact"
                        class="inline-flex items-center px-6 md:px-8 py-3 font-semibold transition-all duration-300"
                        style="background: var(--gold); color: black;">
                        {{ trans('international.visa_button') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pourquoi choisir DJOK PRESTIGE - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="color: var(--gold);">{{
                trans('international.why_title') }}
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 max-w-4xl mx-auto">
            <!-- Organisation de séminaires internationaux -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-calendar-alt text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_seminars') }}</span>
            </div>

            <!-- Réseau d'experts et d'entreprises partenaires -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-handshake text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_network') }}</span>
            </div>

            <!-- Programmes personnalisés -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-puzzle-piece text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_custom') }}</span>
            </div>

            <!-- Destinations business stratégiques -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-globe text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_destinations') }}</span>
            </div>

            <!-- Organisation clé en main -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-key text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_turnkey') }}</span>
            </div>

            <!-- Expérience premium pour dirigeants et équipes -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full mb-4 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: var(--gold);">
                    <i class="fas fa-crown text-black text-xl md:text-2xl"></i>
                </div>
                <span class="text-sm md:text-base font-medium" style="color: white;">{{ trans('international.why_premium') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Contact et inscription - Style sobre -->
<section id="contact" class="py-16" style="background: #000;">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-4xl mx-auto p-6 md:p-8" style="background: #111; border: 1px solid #333;">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8" style="color: var(--gold);">{{
                trans('international.contact_title') }}</h2>

            @if(session('error'))
            <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;" id="error-message">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                        style="background: #dc2626;">
                        <i class="fas fa-exclamation-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white">{{ trans('international.error_title') }}</h4>
                        <p class="text-red-100">{{ session('error') }}</p>
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
                        <h4 class="font-bold text-white">{{ trans('international.error_correction') }}</h4>
                        <ul class="text-red-100 list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('formation-internationale.store') }}" method="POST">
    @csrf

    <!-- Nom de l'entreprise (nouveau, optionnel) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Nom de l'entreprise</label>
        <input type="text" name="nom_entreprise" value="{{ old('nom_entreprise') }}"
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;">
    </div>

    <!-- Nom du responsable (correspond à nom_complet) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Nom du responsable *</label>
        <input type="text" name="nom_responsable" required value="{{ old('nom_responsable') }}"
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;">
    </div>

    <!-- Email -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Email *</label>
        <input type="email" name="email" required value="{{ old('email') }}"
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;">
    </div>

    <!-- Téléphone / WhatsApp -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Téléphone / WhatsApp *</label>
        <input type="tel" name="telephone" required value="{{ old('telephone') }}"
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;">
    </div>

    <!-- Destination souhaitée (nouveau, optionnel) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Destination souhaitée</label>
        <select name="destination_souhaitee" class="w-full px-4 py-3 rounded"
                style="background: #1a1a1a; border: 1px solid #444; color: white;">
            <option value="">Sélectionnez une destination</option>
            <option value="dubai">Dubaï</option>
            <option value="usa">USA</option>
            <option value="europe">Europe</option>
            <option value="afrique">Afrique</option>
            <option value="autre">Autre</option>
        </select>
    </div>

    <!-- Nombre de participants (nouveau, optionnel) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Nombre de participants</label>
        <input type="number" name="nombre_participants" min="1" value="{{ old('nombre_participants') }}"
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;">
    </div>

    <!-- Type d'événement (nouveau, optionnel) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Type d'événement</label>
        <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center p-2 rounded" style="background: #1a1a1a;">
                <input type="checkbox" name="type_evenement[]" value="formation" class="mr-2"> Formation
            </label>
            <label class="flex items-center p-2 rounded" style="background: #1a1a1a;">
                <input type="checkbox" name="type_evenement[]" value="seminaire" class="mr-2"> Séminaire
            </label>
            <label class="flex items-center p-2 rounded" style="background: #1a1a1a;">
                <input type="checkbox" name="type_evenement[]" value="voyage_business" class="mr-2"> Voyage business
            </label>
            <label class="flex items-center p-2 rounded" style="background: #1a1a1a;">
                <input type="checkbox" name="type_evenement[]" value="team_building" class="mr-2"> Team building
            </label>
        </div>
    </div>

    <!-- Message / objectifs du projet (utilise message pour compatibilité) -->
    <div>
        <label class="block mb-2 font-medium" style="color: #ddd;">Message / objectifs du projet *</label>
        <textarea name="message" rows="5" required
            class="w-full px-4 py-3 rounded"
            style="background: #1a1a1a; border: 1px solid #444; color: white;"
            placeholder="Décrivez votre projet...">{{ old('message') }}</textarea>
    </div>

    <!-- Bouton -->
    <button type="submit" class="w-full px-6 py-3 font-semibold rounded"
            style="background: var(--gold); color: black;">
        <i class="fas fa-paper-plane mr-2"></i> Recevoir une proposition
    </button>
</form>
        </div>

        <!-- Contact rapide - Style sobre -->
        <div class="mt-12 max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6" style="background: #111; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: #1e40af;">
                        <i class="fas fa-phone text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">{{
                        trans('international.international_phone') }}</h3>
                    <a href="tel:+33176380017" class="font-semibold hover:text-blue-300" style="color: #60a5fa;">+33 1
                        76 38 00 17</a>
                </div>

                <div class="text-center p-6" style="background: #111; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: #25D366;">
                        <i class="fab fa-whatsapp text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">WhatsApp</h3>
                    <p class="font-semibold" style="color: #86efac;">{{ trans('international.whatsapp_available') }}</p>
                </div>

                <div class="text-center p-6" style="background: #111; border: 1px solid #333;">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4"
                        style="background: var(--gold);">
                        <i class="fas fa-envelope text-black"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: white;">{{ trans('international.email_label') }}
                    </h3>
                    <a href="mailto:international@djokprestige.com" class="font-semibold hover:text-yellow-300"
                        style="color: var(--gold);">
                        international@djokprestige.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide des messages d'erreur
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
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
        const form = document.getElementById('formation-form');
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
                document.getElementById('contact').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 300);
        }

        // Scroll vers le message de succès s'il existe
        if (document.getElementById('success-alert')) {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        }

        // Date minimum pour la date de début
        const dateInput = document.querySelector('input[name="date_debut"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
    });
</script>
@endsection
