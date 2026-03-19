@extends('layouts.admin')

@section('title', 'Créer une Demande Formation Internationale')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Créer une nouvelle demande</h1>
        <a href="{{ route('admin.demandes-formation-internationale.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.demandes-formation-internationale.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de l'entreprise -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informations</h2>
                </div>

                <div>
                    <label for="nom_entreprise" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de l'entreprise
                    </label>
                    <input type="text" name="nom_entreprise" id="nom_entreprise" value="{{ old('nom_entreprise') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div>
                    <label for="nom_responsable" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du responsable *
                    </label>
                    <input type="text" name="nom_responsable" id="nom_responsable" required value="{{ old('nom_responsable') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone / WhatsApp *
                    </label>
                    <input type="text" name="telephone" id="telephone" required value="{{ old('telephone') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Détails du projet -->
                <div class="md:col-span-2 mt-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Détails du projet</h2>
                </div>

                <div>
                    <label for="destination_souhaitee" class="block text-sm font-medium text-gray-700 mb-2">
                        Destination souhaitée
                    </label>
                    <select name="destination_souhaitee" id="destination_souhaitee"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="">-- Sélectionner --</option>
                        <option value="dubai" {{ old('destination_souhaitee') == 'dubai' ? 'selected' : '' }}>Dubaï</option>
                        <option value="usa" {{ old('destination_souhaitee') == 'usa' ? 'selected' : '' }}>USA</option>
                        <option value="europe" {{ old('destination_souhaitee') == 'europe' ? 'selected' : '' }}>Europe</option>
                        <option value="afrique" {{ old('destination_souhaitee') == 'afrique' ? 'selected' : '' }}>Afrique</option>
                        <option value="autre" {{ old('destination_souhaitee') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div>
                    <label for="nombre_participants" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de participants
                    </label>
                    <input type="number" name="nombre_participants" id="nombre_participants" min="1"
                        value="{{ old('nombre_participants') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Type d'événement -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type d'événement
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                        $oldTypes = old('type_evenement', []);
                        @endphp
                        <label class="flex items-center">
                            <input type="checkbox" name="type_evenement[]" value="formation" {{ in_array('formation', $oldTypes) ? 'checked' : '' }}
                                class="mr-2 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            <span class="text-sm">Formation</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="type_evenement[]" value="seminaire" {{ in_array('seminaire', $oldTypes) ? 'checked' : '' }}
                                class="mr-2 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            <span class="text-sm">Séminaire</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="type_evenement[]" value="voyage_business" {{ in_array('voyage_business', $oldTypes) ? 'checked' : '' }}
                                class="mr-2 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            <span class="text-sm">Voyage business</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="type_evenement[]" value="team_building" {{ in_array('team_building', $oldTypes) ? 'checked' : '' }}
                                class="mr-2 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            <span class="text-sm">Team building</span>
                        </label>
                    </div>
                </div>

                <!-- Message -->
                <div class="md:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message / Objectifs du projet *
                    </label>
                    <textarea name="message" id="message" rows="6" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Décrivez votre projet, vos objectifs...">{{ old('message') }}</textarea>
                </div>

                <!-- Statut et Notes -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut *
                    </label>
                    <select name="statut" id="statut" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="nouveau" {{ old('statut') == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="traite" {{ old('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                        <option value="annule" {{ old('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <div>
                    <label for="notes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes internes
                    </label>
                    <textarea name="notes_admin" id="notes_admin" rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Notes pour le suivi interne...">{{ old('notes_admin') }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.demandes-formation-internationale.index') }}"
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                        Annuler
                    </a>
                    <button type="submit"
                        class="bg-yellow-600 text-white px-6 py-2 rounded hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-save mr-2"></i>Créer la demande
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
