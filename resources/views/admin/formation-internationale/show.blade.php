@extends('layouts.admin')

@section('title', 'Détail Demande Formation Internationale')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Demande de {{ $demande->nom_responsable ?? $demande->nom_complet }}</h1>
            <p class="text-gray-600">Reçue le {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('admin.demandes-formation-internationale.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($demande->nom_entreprise)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Entreprise</label>
                        <p class="mt-1 text-gray-900">{{ $demande->nom_entreprise }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Responsable</label>
                        <p class="mt-1 text-gray-900">{{ $demande->nom_responsable ?? $demande->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-gray-900">
                            <a href="mailto:{{ $demande->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $demande->email }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                        <p class="mt-1 text-gray-900">
                            <a href="tel:{{ $demande->telephone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $demande->telephone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Détails du projet -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails du projet</h2>

                @if($demande->destination_souhaitee)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500">Destination souhaitée</label>
                    <p class="mt-1 text-gray-900">{{ $demande->destination_label }}</p>
                </div>
                @endif

                @if($demande->nombre_participants)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500">Nombre de participants</label>
                    <p class="mt-1 text-gray-900">{{ $demande->nombre_participants }}</p>
                </div>
                @endif

                @if($demande->type_evenement_list)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500">Type(s) d'événement</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($demande->type_evenement_list as $type)
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">{{ $type }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Objectifs du projet</label>
                    <div class="mt-2 p-4 bg-gray-50 rounded">
                        <p class="text-gray-700 whitespace-pre-line">{{ $demande->message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statut</h2>
                <div class="mb-4">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $demande->statut_color }}">
                        {{ $demande->statut_label }}
                    </span>
                </div>

                <form action="{{ route('admin.demandes-formation-internationale.update-statut', $demande) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Changer le statut</label>
                        <select name="statut"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="nouveau" {{ $demande->statut == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                            <option value="en_cours" {{ $demande->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="traite" {{ $demande->statut == 'traite' ? 'selected' : '' }}>Traité</option>
                            <option value="annule" {{ $demande->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes internes</label>
                        <textarea name="notes_admin" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Ajoutez des notes...">{{ old('notes_admin', $demande->notes_admin) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    <a href="mailto:{{ $demande->email }}?subject=Réponse à votre demande de formation internationale"
                        class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-envelope mr-2"></i>Envoyer un email
                    </a>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $demande->telephone) }}" target="_blank"
                        class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>

                    <a href="{{ route('admin.demandes-formation-internationale.edit', $demande) }}"
                        class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>

                    <form action="{{ route('admin.demandes-formation-internationale.destroy', $demande) }}" method="POST"
                        onsubmit="return confirm('Supprimer définitivement cette demande ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full text-center bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
