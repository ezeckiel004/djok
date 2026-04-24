@extends('layouts.admin')

@section('title', 'Nouveau forfait E-learning | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('admin.elearning.forfaits') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux forfaits
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900">Créer un nouveau forfait</h2>
            <p class="text-sm text-gray-600 mt-1">Configurez les offres e-learning avec sélection personnalisée du contenu</p>
        </div>

        <form action="{{ route('admin.elearning.forfaits.store') }}" method="POST" class="p-6" id="forfaitForm">
            @csrf

            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du forfait *</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ex: Forfait Révision 30 jours" value="{{ old('name') }}">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL) *</label>
                    <input type="text" name="slug" id="slug" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ex: forfait-revision-30-jours" value="{{ old('slug') }}">
                    <p class="text-xs text-gray-500 mt-1">Utilisez des tirets, pas d'espaces</p>
                    @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Description détaillée du forfait...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€) *</label>
                    <input type="number" name="price" step="0.01" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="49.90" value="{{ old('price') }}">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) *</label>
                    <input type="number" name="duration_days" min="1" max="365" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="30" value="{{ old('duration_days', 30) }}">
                    @error('duration_days')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Connexions max *</label>
                    <input type="number" name="max_concurrent_connections" min="1" max="10" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('max_concurrent_connections', 1) }}">
                    @error('max_concurrent_connections')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fonctionnalités de base -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-md font-medium text-gray-900 mb-3">Fonctionnalités incluses</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="includes_qcm" value="1" {{ old('includes_qcm', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Inclut les QCM</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="includes_examens_blancs" value="1" {{ old('includes_examens_blancs', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Inclut les examens blancs</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="includes_certification" value="1" {{ old('includes_certification') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Inclut une certification</span>
                    </label>
                </div>
            </div>

            <!-- Contenu du forfait -->
            <div class="mb-8">
                <div class="border-b border-gray-200 pb-3 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Contenu du forfait</h3>
                    <p class="text-sm text-gray-500 mt-1">Choisissez les cours, QCM et examens blancs inclus</p>
                </div>

                <!-- Mode de sélection global -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border border-blue-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Mode de sélection :</span>
                        </div>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="selection_mode" value="all" checked
                                       class="selection-mode-radio rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700 font-medium">📦 Tout inclure</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="selection_mode" value="custom"
                                       class="selection-mode-radio rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700 font-medium">🎯 Sélection personnalisée</span>
                            </label>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3 pt-2 border-t border-blue-200">
                        <i class="fas fa-info-circle mr-1"></i>
                        En mode "Tout inclure", tous les cours/QCM/examens actifs seront automatiquement disponibles.<br>
                        En mode "Sélection personnalisée", vous pourrez choisir précisément le contenu.
                    </p>
                </div>

                <!-- Section "Tout inclure" -->
                <div id="allModeSection" class="space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 text-xl mr-3 mt-0.5"></i>
                            <div>
                                <p class="font-medium text-green-800">Mode "Tout inclure" actif</p>
                                <p class="text-sm text-green-700 mt-1">
                                    Tous les éléments actifs seront inclus dans ce forfait :
                                </p>
                                <ul class="text-sm text-green-700 mt-2 space-y-1">
                                    <li>• {{ $cours->count() }} cours disponibles</li>
                                    <li>• {{ $qcmsNormaux->count() }} QCM disponibles</li>
                                    <li>• {{ $examensBlancs->count() }} examens blancs disponibles</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section "Sélection personnalisée" -->
                <div id="customModeSection" style="display: none;">

                    <!-- Sélection des cours -->
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-gradient-to-r from-blue-50 to-white px-4 py-3 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-book text-blue-600 mr-2"></i>
                                    <label class="font-medium text-gray-700">Cours à inclure</label>
                                    <span class="ml-2 text-xs text-gray-500">({{ $cours->count() }} disponibles)</span>
                                </div>
                                <button type="button" id="selectAllCours"
                                        class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i> Tout sélectionner
                                </button>
                            </div>
                        </div>
                        <div class="p-4 max-h-80 overflow-y-auto bg-white">
                            @if($cours->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($cours as $coursItem)
                                <label class="flex items-start space-x-3 p-3 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors border border-transparent hover:border-blue-200">
                                    <input type="checkbox" name="selected_cours_ids[]" value="{{ $coursItem->id }}"
                                           class="cours-checkbox mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $coursItem->title }}</div>
                                        @if($coursItem->duration_minutes)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <i class="far fa-clock mr-1"></i>{{ $coursItem->duration_formatted }}
                                        </div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <i class="fas fa-book-open text-gray-300 text-4xl mb-2"></i>
                                <p class="text-gray-500">Aucun cours disponible.</p>
                                <a href="{{ route('admin.elearning.cours.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                                    Créer un cours →
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-200 text-xs text-gray-500">
                            <span id="selectedCoursCount">0</span> cours sélectionné(s)
                        </div>
                    </div>

                    <!-- Sélection des QCM -->
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-gradient-to-r from-green-50 to-white px-4 py-3 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-question-circle text-green-600 mr-2"></i>
                                    <label class="font-medium text-gray-700">QCM à inclure</label>
                                    <span class="ml-2 text-xs text-gray-500">({{ $qcmsNormaux->count() }} disponibles)</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="include_all_qcms" value="1"
                                               class="include-all-qcms rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">
                                            <i class="fas fa-layer-group mr-1"></i>Tous les QCM
                                        </span>
                                    </label>
                                    <button type="button" id="selectAllQcms"
                                            class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-check-double mr-1"></i> Tout sélectionner
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 max-h-80 overflow-y-auto bg-white">
                            @if($qcmsNormaux->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($qcmsNormaux as $qcm)
                                <label class="flex items-start space-x-3 p-3 rounded-lg hover:bg-green-50 cursor-pointer transition-colors border border-transparent hover:border-green-200">
                                    <input type="checkbox" name="selected_qcms_ids[]" value="{{ $qcm->id }}"
                                           class="qcm-checkbox mt-0.5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           data-group="qcm">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $qcm->title }}</div>
                                        @if($qcm->cours)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <i class="fas fa-book mr-1"></i>{{ $qcm->cours->title }}
                                        </div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            {{ $qcm->questions_count }} questions • Score requis: {{ $qcm->passing_score }}%
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-gray-300 text-4xl mb-2"></i>
                                <p class="text-gray-500">Aucun QCM disponible.</p>
                                <a href="{{ route('admin.elearning.qcms.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                                    Créer un QCM →
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-200 text-xs text-gray-500">
                            <span id="selectedQcmsCount">0</span> QCM sélectionné(s)
                        </div>
                    </div>

                    <!-- Sélection des examens blancs -->
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-gradient-to-r from-purple-50 to-white px-4 py-3 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-purple-600 mr-2"></i>
                                    <label class="font-medium text-gray-700">Examens blancs à inclure</label>
                                    <span class="ml-2 text-xs text-gray-500">({{ $examensBlancs->count() }} disponibles)</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="include_all_examens" value="1"
                                               class="include-all-examens rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span class="ml-2 text-sm text-gray-600">
                                            <i class="fas fa-layer-group mr-1"></i>Tous les examens
                                        </span>
                                    </label>
                                    <button type="button" id="selectAllExamens"
                                            class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-check-double mr-1"></i> Tout sélectionner
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 max-h-80 overflow-y-auto bg-white">
                            @if($examensBlancs->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($examensBlancs as $examen)
                                <label class="flex items-start space-x-3 p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors border border-transparent hover:border-purple-200">
                                    <input type="checkbox" name="selected_examens_ids[]" value="{{ $examen->id }}"
                                           class="examen-checkbox mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                           data-group="examen">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $examen->title }}</div>
                                        @if($examen->cours)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <i class="fas fa-book mr-1"></i>{{ $examen->cours->title }}
                                        </div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            {{ $examen->questions_count }} questions • Score requis: {{ $examen->passing_score }}%
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <i class="fas fa-trophy text-gray-300 text-4xl mb-2"></i>
                                <p class="text-gray-500">Aucun examen blanc disponible.</p>
                                <a href="{{ route('admin.elearning.qcms.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                                    Créer un examen blanc →
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-200 text-xs text-gray-500">
                            <span id="selectedExamensCount">0</span> examen(s) blanc(s) sélectionné(s)
                        </div>
                    </div>

                </div>
            </div>

            <input type="hidden" name="include_all_cours" id="include_all_cours" value="1">
            <input type="hidden" name="include_all_qcms" id="include_all_qcms" value="1">
            <input type="hidden" name="include_all_examens" id="include_all_examens" value="1">

            <!-- ============================================= -->
            <!-- SECTION CODES PROMO -->
            <!-- ============================================= -->
            @include('admin.elearning.forfaits.partials._promo_codes')

            <!-- Fonctionnalités détaillées -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-medium text-gray-700">Fonctionnalités détaillées</label>
                    <button type="button" id="addFeatureBtn"
                        class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                        <i class="fas fa-plus mr-1"></i> Ajouter une fonctionnalité
                    </button>
                </div>

                <div id="featuresContainer" class="space-y-3">
                    <div class="feature-item flex items-center space-x-2">
                        <input type="text" name="feature_titles[]"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Titre de la fonctionnalité">
                        <textarea name="feature_descriptions[]" rows="1"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Description..."></textarea>
                        <button type="button" class="remove-feature px-3 py-2 text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="features" id="featuresJson">
                <p class="text-xs text-gray-500 mt-2">
                    Ajoutez des fonctionnalités spécifiques à ce forfait (ex: "Support prioritaire", "Attestation téléchargeable"...)
                </p>
            </div>

            <!-- Ordre d'affichage et statut -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                    <input type="number" name="access_order" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('access_order', 0) }}">
                    <p class="text-xs text-gray-500 mt-1">Plus le chiffre est petit, plus le forfait apparaît en premier</p>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Forfait actif</span>
                    </label>
                </div>
            </div>

            <!-- Option Stripe -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <label class="flex items-center">
                    <input type="checkbox" name="create_stripe_product" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Créer un produit Stripe associé</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ml-6">
                    Crée automatiquement un produit et un prix sur Stripe pour ce forfait
                </p>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.elearning.forfaits') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Créer le forfait
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const featuresContainer = document.getElementById('featuresContainer');
    const addFeatureBtn = document.getElementById('addFeatureBtn');
    const featuresJson = document.getElementById('featuresJson');
    const form = document.getElementById('forfaitForm');

    const selectionModeRadios = document.querySelectorAll('.selection-mode-radio');
    const allModeSection = document.getElementById('allModeSection');
    const customModeSection = document.getElementById('customModeSection');

    const includeAllCoursHidden = document.getElementById('include_all_cours');
    const includeAllQcmsHidden = document.getElementById('include_all_qcms');
    const includeAllExamensHidden = document.getElementById('include_all_examens');

    const includeAllQcmsCheckbox = document.querySelector('.include-all-qcms');
    const includeAllExamensCheckbox = document.querySelector('.include-all-examens');

    const selectedCoursCountSpan = document.getElementById('selectedCoursCount');
    const selectedQcmsCountSpan = document.getElementById('selectedQcmsCount');
    const selectedExamensCountSpan = document.getElementById('selectedExamensCount');

    // Auto-génération du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.value = slug;
        });
    }

    function toggleSelectionMode() {
        const selectedMode = document.querySelector('input[name="selection_mode"]:checked').value;

        if (selectedMode === 'all') {
            allModeSection.style.display = 'block';
            customModeSection.style.display = 'none';

            includeAllCoursHidden.value = '1';
            includeAllQcmsHidden.value = '1';
            includeAllExamensHidden.value = '1';

            document.querySelectorAll('.cours-checkbox, .qcm-checkbox, .examen-checkbox').forEach(cb => {
                cb.disabled = true;
                cb.checked = false;
            });

            if (includeAllQcmsCheckbox) {
                includeAllQcmsCheckbox.disabled = true;
                includeAllQcmsCheckbox.checked = true;
            }
            if (includeAllExamensCheckbox) {
                includeAllExamensCheckbox.disabled = true;
                includeAllExamensCheckbox.checked = true;
            }

            updateCounters();

        } else {
            allModeSection.style.display = 'none';
            customModeSection.style.display = 'block';

            includeAllCoursHidden.value = '0';

            if (includeAllQcmsCheckbox) {
                includeAllQcmsHidden.value = includeAllQcmsCheckbox.checked ? '1' : '0';
                includeAllQcmsCheckbox.disabled = false;
            }
            if (includeAllExamensCheckbox) {
                includeAllExamensHidden.value = includeAllExamensCheckbox.checked ? '1' : '0';
                includeAllExamensCheckbox.disabled = false;
            }

            document.querySelectorAll('.cours-checkbox').forEach(cb => cb.disabled = false);

            if (includeAllQcmsCheckbox && includeAllQcmsCheckbox.checked) {
                document.querySelectorAll('.qcm-checkbox').forEach(cb => {
                    cb.disabled = true;
                    cb.checked = true;
                });
            } else if (includeAllQcmsCheckbox && !includeAllQcmsCheckbox.checked) {
                document.querySelectorAll('.qcm-checkbox').forEach(cb => {
                    cb.disabled = false;
                });
            }

            if (includeAllExamensCheckbox && includeAllExamensCheckbox.checked) {
                document.querySelectorAll('.examen-checkbox').forEach(cb => {
                    cb.disabled = true;
                    cb.checked = true;
                });
            } else if (includeAllExamensCheckbox && !includeAllExamensCheckbox.checked) {
                document.querySelectorAll('.examen-checkbox').forEach(cb => {
                    cb.disabled = false;
                });
            }

            updateCounters();
        }
    }

    if (includeAllQcmsCheckbox) {
        includeAllQcmsCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            includeAllQcmsHidden.value = isChecked ? '1' : '0';

            const qcmCheckboxes = document.querySelectorAll('.qcm-checkbox');
            qcmCheckboxes.forEach(cb => {
                cb.disabled = isChecked;
                cb.checked = isChecked;
            });
            updateCounters();
        });
    }

    if (includeAllExamensCheckbox) {
        includeAllExamensCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            includeAllExamensHidden.value = isChecked ? '1' : '0';

            const examenCheckboxes = document.querySelectorAll('.examen-checkbox');
            examenCheckboxes.forEach(cb => {
                cb.disabled = isChecked;
                cb.checked = isChecked;
            });
            updateCounters();
        });
    }

    document.getElementById('selectAllCours')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.cours-checkbox:not(:disabled)');
        checkboxes.forEach(cb => cb.checked = true);
        updateCounters();
    });

    document.getElementById('selectAllQcms')?.addEventListener('click', function() {
        if (includeAllQcmsCheckbox && includeAllQcmsCheckbox.checked) {
            alert('"Tous les QCM" est déjà coché. Tous les QCM sont déjà sélectionnés.');
            return;
        }
        const checkboxes = document.querySelectorAll('.qcm-checkbox:not(:disabled)');
        checkboxes.forEach(cb => cb.checked = true);
        updateCounters();
    });

    document.getElementById('selectAllExamens')?.addEventListener('click', function() {
        if (includeAllExamensCheckbox && includeAllExamensCheckbox.checked) {
            alert('"Tous les examens" est déjà coché. Tous les examens sont déjà sélectionnés.');
            return;
        }
        const checkboxes = document.querySelectorAll('.examen-checkbox:not(:disabled)');
        checkboxes.forEach(cb => cb.checked = true);
        updateCounters();
    });

    function updateCounters() {
        const selectedMode = document.querySelector('input[name="selection_mode"]:checked').value;

        if (selectedMode === 'all') {
            if (selectedCoursCountSpan) selectedCoursCountSpan.textContent = 'Tous';
            if (selectedQcmsCountSpan) selectedQcmsCountSpan.textContent = 'Tous';
            if (selectedExamensCountSpan) selectedExamensCountSpan.textContent = 'Tous';
        } else {
            const coursCount = document.querySelectorAll('.cours-checkbox:checked').length;
            const qcmsCount = document.querySelectorAll('.qcm-checkbox:checked').length;
            const examensCount = document.querySelectorAll('.examen-checkbox:checked').length;

            if (selectedCoursCountSpan) selectedCoursCountSpan.textContent = coursCount;
            if (selectedQcmsCountSpan) selectedQcmsCountSpan.textContent = qcmsCount;
            if (selectedExamensCountSpan) selectedExamensCountSpan.textContent = examensCount;
        }
    }

    document.querySelectorAll('.cours-checkbox, .qcm-checkbox, .examen-checkbox').forEach(cb => {
        cb.addEventListener('change', updateCounters);
    });

    selectionModeRadios.forEach(radio => {
        radio.addEventListener('change', toggleSelectionMode);
    });

    function addFeature(title = '', description = '') {
        const featureDiv = document.createElement('div');
        featureDiv.className = 'feature-item flex items-center space-x-2';
        featureDiv.innerHTML = `
            <input type="text" name="feature_titles[]"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Titre de la fonctionnalité"
                value="${escapeHtml(title)}">
            <textarea name="feature_descriptions[]" rows="1"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Description...">${escapeHtml(description)}</textarea>
            <button type="button" class="remove-feature px-3 py-2 text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        `;

        featuresContainer.appendChild(featureDiv);

        featureDiv.querySelector('.remove-feature').addEventListener('click', function() {
            featureDiv.remove();
            updateFeaturesJson();
        });

        featureDiv.querySelector('input').addEventListener('input', updateFeaturesJson);
        featureDiv.querySelector('textarea').addEventListener('input', updateFeaturesJson);

        updateFeaturesJson();
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function updateFeaturesJson() {
        const titles = document.querySelectorAll('input[name="feature_titles[]"]');
        const descriptions = document.querySelectorAll('textarea[name="feature_descriptions[]"]');
        const features = [];

        titles.forEach((titleInput, index) => {
            const title = titleInput.value.trim();
            const description = descriptions[index] ? descriptions[index].value.trim() : '';
            if (title) {
                features.push({ title: title, description: description });
            }
        });

        featuresJson.value = JSON.stringify(features);
    }

    if (addFeatureBtn) {
        addFeatureBtn.addEventListener('click', function() {
            addFeature();
        });
    }

    if (featuresContainer.children.length === 0) {
        addFeature();
    }

    form.addEventListener('submit', function(e) {
        updateFeaturesJson();

        const selectedMode = document.querySelector('input[name="selection_mode"]:checked').value;

        if (selectedMode === 'custom') {
            const coursCount = document.querySelectorAll('.cours-checkbox:checked').length;
            const qcmsCount = document.querySelectorAll('.qcm-checkbox:checked').length;
            const examensCount = document.querySelectorAll('.examen-checkbox:checked').length;

            if (coursCount === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un cours ou activer le mode "Tout inclure".');
                return;
            }

            const includeAllQcmsVal = document.querySelector('.include-all-qcms')?.checked || false;
            const includeAllExamensVal = document.querySelector('.include-all-examens')?.checked || false;

            if (!includeAllQcmsVal && qcmsCount === 0) {
                const confirmMsg = confirm('Aucun QCM sélectionné. Voulez-vous continuer ?');
                if (!confirmMsg) {
                    e.preventDefault();
                    return;
                }
            }

            if (!includeAllExamensVal && examensCount === 0) {
                const confirmMsg = confirm('Aucun examen blanc sélectionné. Voulez-vous continuer ?');
                if (!confirmMsg) {
                    e.preventDefault();
                    return;
                }
            }
        }
    });

    toggleSelectionMode();
    updateCounters();
});
</script>

<style>
    .feature-item textarea {
        min-height: 40px;
        resize: vertical;
    }

    #customModeSection {
        transition: all 0.3s ease;
    }

    label:hover .border-transparent {
        border-color: inherit;
    }
</style>
@endpush
