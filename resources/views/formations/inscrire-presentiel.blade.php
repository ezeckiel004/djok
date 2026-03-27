@extends('layouts.main')

@section('title', __('inscription.title') . ' - ' . $formation->title . ' | DJOK PRESTIGE')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-yellow-600">
                            {{ __('inscription.breadcrumb.home') }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <a href="{{ route('formation') }}" class="hover:text-yellow-600">
                            {{ __('inscription.breadcrumb.trainings') }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <a href="{{ route('formation.show', $formation->slug) }}" class="hover:text-yellow-600">
                            {{ $formation->title }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 font-medium">{{ __('inscription.breadcrumb.registration') }}</span>
                    </li>
                </ol>
            </nav>

            <!-- En-tête -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ __('inscription.header.title') }}
                </h1>
                <h2 class="text-2xl md:text-3xl font-semibold text-blue-600">
                    {{ $formation->title }}
                </h2>
                <p class="text-gray-600 mt-2">
                    {{ __('inscription.header.format') }} • {{ $formation->duree }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne gauche : Formulaire -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                            <h2 class="text-2xl md:text-3xl font-bold text-white">
                                {{ __('inscription.form.title') }}
                            </h2>
                            <p class="text-blue-200 mt-2">
                                {{ __('inscription.form.subtitle') }}
                            </p>
                        </div>

                        <div class="p-8">
                            @if(session('error'))
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-red-800">{{ __('inscription.messages.error') }}</p>
                                        <p class="text-red-700">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-green-800">{{ __('inscription.messages.success') }}</p>
                                        <p class="text-green-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Session sélectionnée -->
                            @if(request()->has('session_id'))
                            @php
                            $selectedSession = \App\Models\FormationSession::find(request()->session_id);
                            @endphp
                            @if($selectedSession && $selectedSession->formation_id == $formation->id && $selectedSession->is_active && $selectedSession->available_places > 0)
                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-alt text-yellow-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="font-semibold text-yellow-800">{{ __('inscription.form.session.selected') }}</h4>
                                        <p class="font-medium text-gray-900">{{ $selectedSession->name }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-calendar-day mr-1"></i> {{ $selectedSession->formatted_dates }}
                                            @if($selectedSession->formatted_schedule)
                                            <br><i class="fas fa-clock mr-1"></i> {{ $selectedSession->formatted_schedule }}
                                            @endif
                                            @if($selectedSession->location)
                                            <br><i class="fas fa-map-marker-alt mr-1"></i> {{ $selectedSession->location }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-green-600 mt-2">
                                            <i class="fas fa-users mr-1"></i> {{ __('inscription.form.session.places_left') }} : {{ $selectedSession->places_remaining }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif

                            <!-- Note pour utilisateurs connectés -->
                            @auth
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-blue-800">{{ __('inscription.form.prefilled_info') }}</p>
                                        <p class="text-blue-700">
                                            {{ __('inscription.form.connected_as') }} <strong>{{ auth()->user()->email }}</strong>.
                                            {{ __('inscription.form.info_prefilled') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endauth

                            <form action="{{ route('formation.inscrire.presentiel.store', $formation->id) }}" method="POST" class="space-y-6">
                                @csrf

                                @if(request()->has('session_id'))
                                <input type="hidden" name="session_id" value="{{ request()->session_id }}">
                                @endif

                                <!-- Section 1: Informations personnelles -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                                        <i class="fas fa-user mr-2 text-blue-600"></i>
                                        {{ __('inscription.form.sections.personal_info') }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Civilité -->
                                        <div>
                                            <label class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.civility') }} <span class="text-red-500">*</span>
                                            </label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="civilite" value="monsieur" class="h-4 w-4 text-blue-600" {{ old('civilite', auth()->user()->civilite ?? 'monsieur') == 'monsieur' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-gray-700">{{ __('inscription.form.options.mr') }}</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="civilite" value="madame" class="h-4 w-4 text-blue-600" {{ old('civilite', auth()->user()->civilite ?? '') == 'madame' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-gray-700">{{ __('inscription.form.options.mrs') }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Nom -->
                                        <div>
                                            <label for="nom" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.last_name') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="nom" id="nom" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.last_name') }}"
                                                value="{{ old('nom', auth()->user()->name ?? '') }}">
                                            @error('nom')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Prénom -->
                                        <div>
                                            <label for="prenom" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.first_name') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="prenom" id="prenom" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.first_name') }}"
                                                value="{{ old('prenom', auth()->user()->prenom ?? '') }}">
                                            @error('prenom')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.email') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" name="email" id="email" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.email') }}"
                                                value="{{ old('email', auth()->user()->email ?? '') }}">
                                            @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Téléphone -->
                                        <div>
                                            <label for="telephone" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.phone') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" name="telephone" id="telephone" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.phone') }}"
                                                value="{{ old('telephone', auth()->user()->phone ?? '') }}">
                                            @error('telephone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Date de naissance -->
                                        <div>
                                            <label for="date_naissance" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.birth_date') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" name="date_naissance" id="date_naissance" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                value="{{ old('date_naissance', auth()->user()->date_naissance ?? '') }}"
                                                max="{{ date('Y-m-d', strtotime('-16 years')) }}"
                                                min="{{ date('Y-m-d', strtotime('-70 years')) }}">
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ __('inscription.form.placeholders.date_format') }} •
                                                {{ __('inscription.form.min_age') }}
                                            </p>
                                            @error('date_naissance')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Date d'obtention du permis -->
                                        <div>
                                            <label for="permis_date" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.license_date') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" name="permis_date" id="permis_date" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                value="{{ old('permis_date', auth()->user()->permis_date ?? '') }}"
                                                max="{{ date('Y-m-d') }}"
                                                min="{{ date('Y-m-d', strtotime('-50 years')) }}">
                                            @error('permis_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Adresse -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                                        <i class="fas fa-home mr-2 text-blue-600"></i>
                                        {{ __('inscription.form.sections.address') }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Adresse -->
                                        <div class="md:col-span-2">
                                            <label for="adresse" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.address') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="adresse" id="adresse" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.address') }}"
                                                value="{{ old('adresse', auth()->user()->address ?? '') }}">
                                            @error('adresse')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Code postal -->
                                        <div>
                                            <label for="code_postal" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.zip_code') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="code_postal" id="code_postal" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.zip_code') }}"
                                                value="{{ old('code_postal', auth()->user()->code_postal ?? '') }}">
                                            @error('code_postal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Ville -->
                                        <div>
                                            <label for="ville" class="block text-gray-700 mb-2">
                                                {{ __('inscription.form.fields.city') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="ville" id="ville" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ __('inscription.form.placeholders.city') }}"
                                                value="{{ old('ville', auth()->user()->city ?? '') }}">
                                            @error('ville')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 3: Financement -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                                        <i class="fas fa-euro-sign mr-2 text-blue-600"></i>
                                        {{ __('inscription.form.sections.funding') }}
                                    </h3>

                                    <div class="space-y-3">
                                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer">
                                            <input type="radio" name="financement" value="perso" class="h-4 w-4 text-blue-600" {{ old('financement', 'perso') == 'perso' ? 'checked' : '' }}>
                                            <div class="ml-3">
                                                <span class="font-medium text-gray-900">{{ __('inscription.form.funding.personal') }}</span>
                                                <p class="text-sm text-gray-600">{{ __('inscription.form.funding.personal_desc') }}</p>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer">
                                            <input type="radio" name="financement" value="pole_emploi" class="h-4 w-4 text-blue-600" {{ old('financement') == 'pole_emploi' ? 'checked' : '' }}>
                                            <div class="ml-3">
                                                <span class="font-medium text-gray-900">{{ __('inscription.form.funding.pole_emploi') }}</span>
                                                <p class="text-sm text-gray-600">{{ __('inscription.form.funding.pole_emploi_desc') }}</p>
                                            </div>
                                        </label>
                                    </div>
                                    @error('financement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Section 4: Message supplémentaire -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                                        <i class="fas fa-comment-alt mr-2 text-blue-600"></i>
                                        {{ __('inscription.form.sections.additional_info') }}
                                    </h3>

                                    <div>
                                        <label for="message" class="block text-gray-700 mb-2">
                                            {{ __('inscription.form.fields.message') }} <span class="text-gray-500 text-sm">({{ __('inscription.form.fields.optional') }})</span>
                                        </label>
                                        <textarea name="message" id="message" rows="4"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="{{ __('inscription.form.placeholders.message') }}">{{ old('message') }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ __('inscription.form.placeholders.message_desc') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Section 5: CGV -->
                                <div class="pb-6">
                                    <div class="flex items-start">
                                        <input type="checkbox" name="terms" id="terms" required
                                            class="mt-1 mr-3 h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ old('terms') ? 'checked' : '' }}>
                                        <label for="terms" class="text-gray-700">
                                            {!! __('inscription.form.terms.accept') !!}
                                            <a href="{{ route('cgu') }}" class="text-blue-600 hover:underline font-medium">
                                                {{ __('inscription.form.terms.terms') }}
                                            </a>
                                            {{ __('inscription.form.terms.and') }}
                                            <a href="{{ route('rgpd') }}" class="text-blue-600 hover:underline font-medium">
                                                {{ __('inscription.form.terms.privacy') }}
                                            </a>
                                            . <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                    @error('terms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bouton de soumission -->
                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                        <i class="fas fa-paper-plane mr-3"></i>
                                        {{ __('inscription.form.submit') }}
                                    </button>
                                    <p class="mt-3 text-center text-sm text-gray-600">
                                        {{ __('inscription.form.confirmation') }}
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Informations -->
                <div class="space-y-6">
                    <!-- Résumé formation -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            {{ __('inscription.summary.title') }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">{{ __('inscription.summary.training') }}</h4>
                                <p class="text-gray-600">{{ $formation->title }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">{{ __('inscription.summary.duration') }}</h4>
                                <p class="text-gray-600">{{ $formation->duree }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">{{ __('inscription.summary.format') }}</h4>
                                <p class="text-gray-600">{{ $formation->format_affichage ?? __('inscription.summary.in_person') }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">{{ __('inscription.summary.price') }}</h4>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{ $formation->price ? number_format((float) $formation->price, 0, ',', ' ') . ' €' : __('inscription.summary.quote') }}
                                </p>
                                @if($formation->frais_examen && (float) $formation->frais_examen > 0)
                                <p class="text-sm text-gray-600">
                                    + {{ number_format((float) $formation->frais_examen, 0, ',', ' ') }} € {{ __('inscription.summary.exam_fees') }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Programme -->
                    @if($formation->program && count($formation->program) > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-list-alt mr-2 text-blue-600"></i>
                            {{ __('inscription.program.title') }}
                        </h3>

                        <ul class="space-y-2">
                            @foreach(array_slice($formation->program, 0, 5) as $module)
                            <li class="flex items-start text-sm">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-xs"></i>
                                <span class="text-gray-600">{{ $module }}</span>
                            </li>
                            @endforeach
                            @if(count($formation->program) > 5)
                            <li class="text-sm text-blue-600 font-medium">
                                + {{ count($formation->program) - 5 }} {{ __('inscription.program.other_modules') }}
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <!-- Documents requis -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-file-alt mr-2 text-yellow-600"></i>
                            {{ __('inscription.documents.title') }}
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-id-card text-yellow-600 mr-3 text-sm"></i>
                                <span class="text-gray-700">{{ __('inscription.documents.id_copy') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-home text-yellow-600 mr-3 text-sm"></i>
                                <span class="text-gray-700">{{ __('inscription.documents.residence_proof') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-car text-yellow-600 mr-3 text-sm"></i>
                                <span class="text-gray-700">{{ __('inscription.documents.driving_license') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-camera text-yellow-600 mr-3 text-sm"></i>
                                <span class="text-gray-700">{{ __('inscription.documents.id_photo') }}</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-white rounded-lg">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                {{ __('inscription.documents.send_to') }}
                            </p>
                            <a href="mailto:formations@djokprestige.com" class="block mt-1 text-blue-600 hover:underline font-medium">
                                formations@djokprestige.com
                            </a>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="bg-blue-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-headset mr-2 text-blue-600"></i>
                            {{ __('inscription.contact.title') }}
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-phone-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">06 99 16 44 55</p>
                                    <p class="text-sm text-gray-600">{{ __('inscription.contact.phone_hours') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">contact@djokprestige.com</p>
                                    <p class="text-sm text-gray-600">{{ __('inscription.contact.email_response') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Retour -->
            <div class="mt-8 text-center">
                <a href="{{ route('formation.show', $formation->slug) }}"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('inscription.back_button') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateNaissance = document.getElementById('date_naissance');
        const permisDate = document.getElementById('permis_date');

        const today = new Date();
        const minNaissance = new Date();
        minNaissance.setFullYear(today.getFullYear() - 70);
        const maxNaissance = new Date();
        maxNaissance.setFullYear(today.getFullYear() - 16);
        const minPermis = new Date();
        minPermis.setFullYear(today.getFullYear() - 50);

        function formatDateForInput(date) {
            return date.toISOString().split('T')[0];
        }

        if (dateNaissance) {
            dateNaissance.min = formatDateForInput(minNaissance);
            dateNaissance.max = formatDateForInput(maxNaissance);
        }

        if (permisDate) {
            permisDate.min = formatDateForInput(minPermis);
            permisDate.max = formatDateForInput(today);
        }

        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    errorMessage = '{{ __("inscription.validation.required_fields") }}';
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (dateNaissance.value) {
                const birthDate = new Date(dateNaissance.value);
                const age = today.getFullYear() - birthDate.getFullYear();
                if (age < 16) {
                    isValid = false;
                    dateNaissance.classList.add('border-red-500');
                    errorMessage = '{{ __("inscription.validation.min_age") }}';
                }
            }

            if (permisDate.value) {
                const permis = new Date(permisDate.value);
                if (permis > today) {
                    isValid = false;
                    permisDate.classList.add('border-red-500');
                    errorMessage = '{{ __("inscription.validation.license_future") }}';
                }
            }

            if (!isValid) {
                e.preventDefault();
                let errorDiv = document.querySelector('.error-message');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message mb-4 p-3 bg-red-50 text-red-700 rounded-lg';
                    form.prepend(errorDiv);
                }
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>${errorMessage}</span>
                    </div>
                `;
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        const cards = document.querySelectorAll('.bg-white, .bg-yellow-50, .bg-blue-50');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
</script>

<style>
    input, textarea, select {
        background-color: white !important;
        color: #374151 !important;
        border-color: #d1d5db !important;
    }
    input::placeholder, textarea::placeholder {
        color: #9ca3af !important;
    }
    input:focus, textarea:focus, select:focus {
        background-color: white !important;
        color: #374151 !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    @media (prefers-color-scheme: dark) {
        input, textarea, select {
            background-color: white !important;
            color: #374151 !important;
        }
        input::placeholder, textarea::placeholder {
            color: #6b7280 !important;
        }
    }
    input:invalid, textarea:invalid {
        border-color: #e53e3e;
    }
    input:valid, textarea:valid {
        border-color: #cbd5e0;
    }
    .error-message {
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
