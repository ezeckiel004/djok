@extends('layouts.admin')

@section('title', 'Nouvelle session de formation')

@section('page-title', 'Créer une nouvelle session')

@section('page-actions')
<a href="{{ route('admin.sessions.index') }}" class="btn-secondary">
    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <form action="{{ route('admin.sessions.store') }}" method="POST" id="session-form">
            @csrf

            <div class="px-4 py-5 sm:p-6 space-y-8">
                <!-- Informations de base -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-djok-yellow mr-2"></i>
                        Informations de la session
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="formation_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Formation *
                            </label>
                            <select name="formation_id" id="formation_id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une formation</option>
                                @foreach($formations as $formation)
                                <option value="{{ $formation->id }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }}>
                                    {{ $formation->title }} ({{ number_format($formation->price, 0, ',', ' ') }} €)
                                </option>
                                @endforeach
                            </select>
                            @error('formation_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom de la session *
                            </label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('name') }}" placeholder="Ex: Session Janvier 2024">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                Type de session *
                            </label>
                            <select name="type" id="type" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200">
                                <option value="presentiel" {{ old('type') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                                <option value="e_learning" {{ old('type') == 'e_learning' ? 'selected' : '' }}>E-learning</option>
                                <option value="mixte" {{ old('type') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                            </select>
                            @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_places" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de places *
                            </label>
                            <input type="number" name="max_places" id="max_places" required min="1"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('max_places', 10) }}">
                            @error('max_places')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates et horaires -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-djok-yellow mr-2"></i>
                        Dates et horaires
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Date de début *
                            </label>
                            <input type="date" name="start_date" id="start_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Date de fin *
                            </label>
                            <input type="date" name="end_date" id="end_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Heure de début
                            </label>
                            <input type="time" name="start_time" id="start_time"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('start_time') }}">
                            @error('start_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Heure de fin
                            </label>
                            <input type="time" name="end_time" id="end_time"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('end_time') }}">
                            @error('end_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Lieu et prix -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-djok-yellow mr-2"></i>
                        Lieu et tarif
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                Lieu
                            </label>
                            <input type="text" name="location" id="location"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('location') }}" placeholder="Adresse du centre de formation">
                            @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Prix (€)
                            </label>
                            <input type="number" step="0.01" name="price" id="price" min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                                value="{{ old('price') }}" placeholder="Laisser vide pour utiliser le prix de la formation">
                            @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Si non renseigné, le prix de la formation sera utilisé</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description de la session
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent transition-all duration-200"
                        placeholder="Informations supplémentaires sur cette session...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-djok-yellow mr-2"></i>
                        Options
                    </h3>

                    <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-5 w-5 text-djok-yellow focus:ring-djok-yellow border-gray-300 rounded">
                        <label for="is_active" class="ml-3 block text-sm text-gray-900">
                            <span class="font-medium">Session active</span>
                            <p class="text-xs text-gray-500 mt-1">Visible et accessible pour les inscriptions</p>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="px-4 py-5 sm:p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.sessions.index') }}"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-djok-yellow text-white rounded-lg text-sm font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-djok-yellow transition-colors duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Créer la session
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation des dates
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        startDate.addEventListener('change', function() {
            if (endDate.value && this.value > endDate.value) {
                endDate.value = this.value;
            }
        });

        endDate.addEventListener('change', function() {
            if (startDate.value && this.value < startDate.value) {
                this.value = startDate.value;
            }
        });

        // Validation du formulaire
        const form = document.getElementById('session-form');
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
    });
</script>
@endpush
@endsection
