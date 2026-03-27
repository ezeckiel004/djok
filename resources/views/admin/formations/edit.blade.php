@extends('layouts.admin')

@section('title', 'Éditer ' . $formation->title)

@section('page-title', 'Éditer la formation')

@section('page-actions')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('admin.formations.show', $formation) }}" class="btn-secondary">
        <i class="fas fa-eye mr-2"></i>Voir la formation
    </a>
    <a href="{{ route('admin.formations.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
    </a>
    @if($formation->isElearning() && !$formation->stripe_price_id)
    <a href="{{ route('admin.formations.sync-stripe', $formation) }}" class="btn-primary"
        onclick="return confirm('Créer un produit Stripe pour cette formation e-learning ?')">
        <i class="fas fa-sync-alt mr-2"></i>Sync Stripe
    </a>
    @endif
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Alertes Stripe -->
    @if($formation->isElearning())
    <div class="mb-6">
        @if(!$formation->stripe_price_id)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-yellow-800 font-medium">Produit Stripe manquant</p>
                    <p class="text-yellow-700 text-sm mt-1">
                        Cette formation e-learning n'a pas encore de produit Stripe associé.
                        <a href="{{ route('admin.formations.sync-stripe', $formation) }}"
                            class="font-semibold underline hover:text-yellow-900">
                            Cliquez ici pour en créer un.
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-green-800 font-medium">Produit Stripe configuré</p>
                    <p class="text-green-700 text-sm mt-1">
                        Produit ID: <code
                            class="bg-gray-100 px-2 py-1 rounded">{{ $formation->stripe_product_id }}</code>
                        | Price ID: <code class="bg-gray-100 px-2 py-1 rounded">{{ $formation->stripe_price_id }}</code>
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <form action="{{ route('admin.formations.update', $formation) }}" method="POST" id="formation-form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="px-4 py-5 sm:p-6 space-y-8">
                <!-- Informations de base -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-djok-yellow mr-2"></i>
                        Informations de base
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Titre de la formation *
                            </label>
                            <input type="text" name="title" id="title" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('title', $formation->title) }}"
                                placeholder="Ex: Formation VTC Professionnelle">
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug (URL) *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">/formations/</span>
                                </div>
                                <input type="text" name="slug" id="slug" required
                                    class="w-full pl-28 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                    value="{{ old('slug', $formation->slug) }}"
                                    placeholder="formation-vtc-professionnelle">
                            </div>
                            @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Ce slug sera utilisé dans l'URL de la formation</p>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Prix (€) *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">€</span>
                                </div>
                                <input type="number" step="0.01" name="price" id="price" required min="0"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                    value="{{ old('price', $formation->price) }}" placeholder="0.00">
                            </div>
                            @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-1">
                                Durée (heures) *
                            </label>
                            <div class="relative">
                                <input type="number" name="duration_hours" id="duration_hours" required min="1"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                    value="{{ old('duration_hours', $formation->duration_hours) }}"
                                    placeholder="Ex: 40">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">heures</span>
                                </div>
                            </div>
                            @error('duration_hours')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duree" class="block text-sm font-medium text-gray-700 mb-1">
                                Durée (affichage)
                            </label>
                            <input type="text" name="duree" id="duree"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('duree', $formation->duree) }}" placeholder="Ex: 40 heures (5 jours)">
                            @error('duree')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="format_affichage" class="block text-sm font-medium text-gray-700 mb-1">
                                Format d'affichage
                            </label>
                            <input type="text" name="format_affichage" id="format_affichage"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('format_affichage', $formation->format_affichage) }}"
                                placeholder="Ex: Présentiel, E-learning, Mixte">
                            @error('format_affichage')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Catégorisation -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags text-djok-yellow mr-2"></i>
                        Catégorisation
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="format_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Format *
                            </label>
                            <select name="format_type" id="format_type" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="presentiel" {{ old('format_type', $formation->format_type) ==
                                    'presentiel' ? 'selected' : '' }}>
                                    Présentiel
                                </option>
                                <option value="en_ligne" {{ old('format_type', $formation->format_type) == 'en_ligne' ?
                                    'selected' : '' }}>
                                    En ligne
                                </option>
                                <option value="mixte" {{ old('format_type', $formation->format_type) == 'mixte' ?
                                    'selected' : '' }}>
                                    Mixte (Présentiel + En ligne)
                                </option>
                            </select>
                            @error('format_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type_formation" class="block text-sm font-medium text-gray-700 mb-1">
                                Type de formation *
                            </label>
                            <select name="type_formation" id="type_formation" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="presentiel" {{ old('type_formation', $formation->type_formation) ==
                                    'presentiel' ? 'selected' : '' }}>
                                    Présentiel
                                </option>
                                <option value="e_learning" {{ old('type_formation', $formation->type_formation) ==
                                    'e_learning' ? 'selected' : '' }}>
                                    E-learning
                                </option>
                            </select>
                            @error('type_formation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie *
                            </label>
                            <select name="categorie" id="categorie" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="vtc_theorique" {{ old('categorie', $formation->categorie) ==
                                    'vtc_theorique' ? 'selected' : '' }}>
                                    VTC - Formation théorique
                                </option>
                                <option value="vtc_pratique" {{ old('categorie', $formation->categorie) ==
                                    'vtc_pratique' ? 'selected' : '' }}>
                                    VTC - Formation pratique
                                </option>
                                <option value="e_learning" {{ old('categorie', $formation->categorie) == 'e_learning' ?
                                    'selected' : '' }}>
                                    E-learning
                                </option>
                                <option value="renouvellement" {{ old('categorie', $formation->categorie) ==
                                    'renouvellement' ? 'selected' : '' }}>
                                    Renouvellement de licence
                                </option>
                            </select>
                            @error('categorie')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Options incluses -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-check-circle text-djok-yellow mr-2"></i>
                        Options incluses
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="frais_examen" class="block text-sm font-medium text-gray-700 mb-1">
                                Frais d'examen
                            </label>
                            <select name="frais_examen" id="frais_examen"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="Inclus" {{ old('frais_examen', $formation->frais_examen) == 'Inclus' ?
                                    'selected' : '' }}>Inclus</option>
                                <option value="Non inclus" {{ old('frais_examen', $formation->frais_examen) == 'Non
                                    inclus' ? 'selected' : '' }}>Non inclus</option>
                                <option value="À préciser" {{ old('frais_examen', $formation->frais_examen) == 'À
                                    préciser' ? 'selected' : '' }}>À préciser</option>
                            </select>
                            @error('frais_examen')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_vehicule" class="block text-sm font-medium text-gray-700 mb-1">
                                Location véhicule
                            </label>
                            <select name="location_vehicule" id="location_vehicule"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="Inclus" {{ old('location_vehicule', $formation->location_vehicule) ==
                                    'Inclus' ? 'selected' : '' }}>Inclus</option>
                                <option value="Non inclus" {{ old('location_vehicule', $formation->location_vehicule) ==
                                    'Non inclus' ? 'selected' : '' }}>Non inclus</option>
                                <option value="Optionnel" {{ old('location_vehicule', $formation->location_vehicule) ==
                                    'Optionnel' ? 'selected' : '' }}>Optionnel</option>
                            </select>
                            @error('location_vehicule')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description de la formation *
                    </label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                        placeholder="Décrivez en détail le contenu de la formation, ses objectifs et ce que les participants apprendront...">{{ old('description', $formation->description) }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Cette description sera visible sur la page publique de la formation
                    </p>
                </div>

                <!-- Options -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-djok-yellow mr-2"></i>
                        Options de la formation
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                            <input type="checkbox" name="is_certified" id="is_certified" value="1" {{
                                old('is_certified', $formation->is_certified) ? 'checked' : '' }}
                            class="h-5 w-5 text-djok-yellow focus:ring-djok-yellow border-gray-300 rounded">
                            <label for="is_certified" class="ml-3 block text-sm text-gray-900">
                                <span class="font-medium">Formation certifiée</span>
                                <p class="text-xs text-gray-500 mt-1">Délivre un certificat reconnu</p>
                            </label>
                        </div>

                        <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                            <input type="checkbox" name="is_financeable_cpf" id="is_financeable_cpf" value="1" {{
                                old('is_financeable_cpf', $formation->is_financeable_cpf) ? 'checked' : '' }}
                            class="h-5 w-5 text-djok-yellow focus:ring-djok-yellow border-gray-300 rounded">
                            <label for="is_financeable_cpf" class="ml-3 block text-sm text-gray-900">
                                <span class="font-medium">Éligible CPF</span>
                                <p class="text-xs text-gray-500 mt-1">Financement par Compte Personnel de Formation</p>
                            </label>
                        </div>

                        <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',
                                $formation->is_active) ? 'checked' : '' }}
                            class="h-5 w-5 text-djok-yellow focus:ring-djok-yellow border-gray-300 rounded">
                            <label for="is_active" class="ml-3 block text-sm text-gray-900">
                                <span class="font-medium">Formation active</span>
                                <p class="text-xs text-gray-500 mt-1">Visible et accessible aux utilisateurs</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Détails -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-list-alt text-djok-yellow mr-2"></i>
                        Détails de la formation
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-2">
                                Programme détaillé
                            </label>
                            <textarea name="program" id="program" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                placeholder="Entrez chaque module sur une nouvelle ligne :">{{ old('program', $formation->program ? implode("\n", $formation->program) : '') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">
                                Chaque ligne représente un module ou une partie du programme
                            </p>
                        </div>

                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                Prérequis
                            </label>
                            <textarea name="requirements" id="requirements" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                placeholder="Exemples :">{{ old('requirements', $formation->requirements ? implode("\n", $formation->requirements) : '') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">
                                Liste des conditions requises pour suivre la formation
                            </p>
                        </div>

                        <div>
                            <label for="included_services" class="block text-sm font-medium text-gray-700 mb-2">
                                Services inclus
                            </label>
                            <textarea name="included_services" id="included_services" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                placeholder="Exemples :">{{ old('included_services', $formation->included_services ? implode("\n", $formation->included_services) : '') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">
                                Ce qui est inclus dans le prix de la formation
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Programme détaillé PDF (Brochure) -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-file-pdf text-djok-yellow mr-2"></i>
                        Programme détaillé (PDF)
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Cette section permet d'uploader un PDF unique qui servira de brochure commerciale pour présenter le programme détaillé de la formation.
                        Ce PDF sera téléchargeable publiquement sur la page de la formation.
                    </p>

                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="programme_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nouveau fichier PDF du programme
                                </label>
                                <input type="file" name="programme_pdf" id="programme_pdf" accept="application/pdf"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                <p class="text-xs text-gray-500 mt-1">PDF uniquement, taille max : 50 Mo (laisser vide pour conserver le PDF actuel)</p>
                                @error('programme_pdf')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                @if($formation->programme_pdf_exists)
                                <div class="w-full p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                            <span class="text-sm text-green-700">PDF actuel :</span>
                                            <a href="{{ $formation->programme_pdf_url }}" target="_blank"
                                               class="text-sm font-semibold text-blue-600 hover:underline ml-2">
                                                {{ basename($formation->programme_pdf) }}
                                            </a>
                                        </div>
                                        <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                            <input type="checkbox" name="delete_programme_pdf" value="1" class="mr-1">
                                            Supprimer
                                        </label>
                                    </div>
                                </div>
                                @else
                                <div class="w-full p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        <span class="text-sm text-blue-700">Aucun PDF programme n'est actuellement associé à cette formation.</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ce PDF sera affiché comme "Programme détaillé" sur la page publique de la formation.
                        </p>
                    </div>
                </div>

                <!-- Fichiers multimédias existants -->
                @if($formation->media->count() > 0)
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-photo-video text-djok-yellow mr-2"></i>
                        Fichiers multimédias existants (Ressources pédagogiques)
                    </h3>
                    <div class="space-y-4" id="media-container" data-sortable>
                        @foreach($formation->media()->orderBy('order')->get() as $media)
                        <div class="media-item bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200"
                            data-id="{{ $media->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 flex items-start space-x-4">
                                    <!-- Icone type ou miniature -->
                                    <div class="flex-shrink-0">
                                        @if($media->type === 'pdf')
                                        <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                        </div>
                                        @else
                                        @if($media->thumbnail_path)
                                        <div class="h-20 w-32 bg-gray-100 rounded-lg overflow-hidden">
                                            <img src="{{ $media->thumbnail_url }}" alt="{{ $media->title }}"
                                                class="h-full w-full object-cover">
                                        </div>
                                        @else
                                        <div class="h-20 w-32 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-video text-blue-600 text-xl"></i>
                                        </div>
                                        @endif
                                        @endif
                                    </div>

                                    <!-- Infos fichier -->
                                    <div class="flex-1">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                                <input type="text" name="media_titles[{{ $media->id }}]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                                    value="{{ $media->title }}">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                <input type="text" name="media_descriptions[{{ $media->id }}]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                                                    value="{{ $media->description }}">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">Fichier</label>
                                                <div class="text-sm text-gray-600">
                                                    <div class="truncate">{{ $media->file_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $media->file_size }}</div>
                                                    @if($media->type === 'video' && $media->duration)
                                                    <div class="text-xs text-blue-600">{{ $media->duration }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col items-end space-y-2 ml-4">
                                    <!-- Bouton de suppression -->
                                    <div class="flex items-center space-x-2">
                                        <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}"
                                                class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                            <span class="ml-2">Supprimer</span>
                                        </label>
                                    </div>

                                    <!-- Bouton drag -->
                                    <button type="button" class="handle cursor-move text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-arrows-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="media_order" id="media-order" value="">
                </div>
                @endif

                <!-- Nouveaux fichiers multimédias -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-plus-circle text-djok-yellow mr-2"></i>
                        Ajouter de nouveaux fichiers (Ressources pédagogiques)
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Pour les formations en ligne ou mixtes, vous pouvez ajouter des fichiers PDF et des vidéos qui serviront de ressources pédagogiques.
                    </p>

                    <!-- Nouveaux fichiers PDF -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                            Nouveaux fichiers PDF (Cours)
                        </h4>
                        <div id="new-pdf-files-container" class="space-y-4">
                            <div class="new-pdf-file-group border border-gray-200 rounded-lg p-4 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fichier PDF</label>
                                        <input type="file" name="new_pdf_files[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                        <input type="text" name="new_pdf_titles[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Ex: Manuel de formation">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <input type="text" name="new_pdf_descriptions[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Ex: Manuel complet du cours">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addNewPdfField()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            <i class="fas fa-plus mr-2"></i>Ajouter un PDF
                        </button>
                    </div>

                    <!-- Nouveaux fichiers Vidéo -->
                    <div>
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-video text-blue-500 mr-2"></i>
                            Nouveaux fichiers Vidéo
                        </h4>
                        <div id="new-video-files-container" class="space-y-4">
                            <div class="new-video-file-group border border-gray-200 rounded-lg p-4 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fichier
                                            Vidéo</label>
                                        <input type="file" name="new_video_files[]" accept="video/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                        <input type="text" name="new_video_titles[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Ex: Introduction au VTC">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <input type="text" name="new_video_descriptions[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Ex: Vidéo d'introduction">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Miniature</label>
                                        <input type="file" name="new_video_thumbnails[]" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                        <p class="text-xs text-gray-500 mt-1">Image JPG/PNG (optionnel)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Durée</label>
                                        <input type="text" name="new_video_durations[]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Ex: 15:30">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addNewVideoField()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            <i class="fas fa-plus mr-2"></i>Ajouter une vidéo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="px-4 py-5 sm:p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.formations.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-djok-yellow text-white rounded-lg text-sm font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-djok-yellow transition-colors duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour la formation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Génération automatique du slug
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        let slugManuallyEdited = false;

        titleInput.addEventListener('input', function() {
            if (!slugManuallyEdited && titleInput.value.trim() !== '') {
                generateSlug();
            }
        });

        slugInput.addEventListener('input', function() {
            slugManuallyEdited = this.value !== '';
        });

        function generateSlug() {
            let slug = titleInput.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();

            slugInput.value = slug;
        }

        // Gestion du drag & drop pour les médias
        const mediaContainer = document.getElementById('media-container');
        if (mediaContainer) {
            const sortable = new Sortable(mediaContainer, {
                animation: 150,
                handle: '.handle',
                ghostClass: 'bg-blue-50',
                onEnd: function() {
                    updateMediaOrder();
                }
            });

            function updateMediaOrder() {
                const order = [];
                mediaContainer.querySelectorAll('.media-item').forEach(item => {
                    order.push(item.getAttribute('data-id'));
                });
                document.getElementById('media-order').value = order.join(',');
            }

            // Initialiser l'ordre
            updateMediaOrder();
        }

        // Ajout dynamique de nouveaux champs PDF
        window.addNewPdfField = function() {
            const container = document.getElementById('new-pdf-files-container');
            const newField = container.firstElementChild.cloneNode(true);
            newField.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newField);
        }

        // Ajout dynamique de nouveaux champs vidéo
        window.addNewVideoField = function() {
            const container = document.getElementById('new-video-files-container');
            const newField = container.firstElementChild.cloneNode(true);
            newField.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newField);
        }

        // Validation du formulaire
        const form = document.getElementById('formation-form');
        form.addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    valid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });

        // Confirmation pour la suppression des médias
        form.addEventListener('submit', function(e) {
            const deleteCheckboxes = form.querySelectorAll('input[name="delete_media[]"]:checked');
            if (deleteCheckboxes.length > 0) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer les fichiers sélectionnés ? Cette action est irréversible.')) {
                    e.preventDefault();
                }
            }

            const deleteProgrammePdf = form.querySelector('input[name="delete_programme_pdf"]:checked');
            if (deleteProgrammePdf) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer le PDF programme ?')) {
                    e.preventDefault();
                }
            }
        });
    });

    // Vérification des limites de fichiers
    function checkFileSize(fileInput, maxSizeMB) {
        const maxSize = maxSizeMB * 1024 * 1024;
        let totalSize = 0;

        for (let file of fileInput.files) {
            totalSize += file.size;
            if (file.size > maxSize) {
                alert('Le fichier "' + file.name + '" dépasse ' + maxSizeMB + 'MB');
                return false;
            }
        }

        if (totalSize > maxSize) {
            alert('La taille totale des fichiers dépasse ' + maxSizeMB + 'MB');
            return false;
        }

        return true;
    }

    // Ajouter la vérification sur tous les inputs file
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.accept.includes('video')) {
                if (!checkFileSize(this, 2048)) {
                    this.value = '';
                }
            } else if (this.accept.includes('image')) {
                if (!checkFileSize(this, 2)) {
                    this.value = '';
                }
            } else if (this.name.includes('pdf') && this.id !== 'programme_pdf') {
                if (!checkFileSize(this, 500)) {
                    this.value = '';
                }
            } else if (this.id === 'programme_pdf') {
                if (!checkFileSize(this, 50)) {
                    this.value = '';
                }
            }
        });
    });

    // Afficher la taille des fichiers sélectionnés
    function updateFileInfo(fileInput, infoElementId) {
        const infoElement = document.getElementById(infoElementId);
        if (!infoElement) return;

        if (fileInput.files.length === 0) {
            infoElement.textContent = 'Aucun fichier sélectionné';
            return;
        }

        let totalSize = 0;
        let fileNames = [];

        for (let file of fileInput.files) {
            totalSize += file.size;
            fileNames.push(file.name);
        }

        const sizeText = formatFileSize(totalSize);
        infoElement.textContent = `${fileNames.length} fichier(s) - ${sizeText}`;
        infoElement.title = fileNames.join('\n');
    }

    function formatFileSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        let unitIndex = 0;

        while (bytes >= 1024 && unitIndex < units.length - 1) {
            bytes /= 1024;
            unitIndex++;
        }
        return bytes.toFixed(2) + ' ' + units[unitIndex];
    }

    // Initialiser pour tous les inputs file existants
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="file"]').forEach((input, index) => {
            const infoId = 'file-info-' + index;
            const infoElement = document.createElement('div');
            infoElement.id = infoId;
            infoElement.className = 'text-sm text-gray-600 mt-1';
            input.parentNode.appendChild(infoElement);

            input.addEventListener('change', function() {
                updateFileInfo(this, infoId);
            });

            updateFileInfo(input, infoId);
        });
    });
</script>
@endpush
@endsection
