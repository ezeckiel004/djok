@extends('layouts.admin')

@section('title', 'Demandes Formation Internationale')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Demandes Formation Internationale</h1>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                {{ $demandes->total() }} demandes au total
            </div>
            <a href="{{ route('admin.demandes-formation-internationale.create') }}"
                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                <i class="fas fa-plus mr-2"></i>Nouvelle demande
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.demandes-formation-internationale.index') }}"
                class="px-4 py-2 rounded {{ !request('statut') ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Toutes ({{ \App\Models\DemandeFormationInternationale::count() }})
            </a>
            <a href="{{ route('admin.demandes-formation-internationale.index', ['statut' => 'nouveau']) }}"
                class="px-4 py-2 rounded {{ request('statut') == 'nouveau' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Nouvelles ({{ \App\Models\DemandeFormationInternationale::where('statut', 'nouveau')->count() }})
            </a>
            <a href="{{ route('admin.demandes-formation-internationale.index', ['statut' => 'en_cours']) }}"
                class="px-4 py-2 rounded {{ request('statut') == 'en_cours' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                En cours ({{ \App\Models\DemandeFormationInternationale::where('statut', 'en_cours')->count() }})
            </a>
            <a href="{{ route('admin.demandes-formation-internationale.index', ['statut' => 'traite']) }}"
                class="px-4 py-2 rounded {{ request('statut') == 'traite' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Traitées ({{ \App\Models\DemandeFormationInternationale::where('statut', 'traite')->count() }})
            </a>
            <a href="{{ route('admin.demandes-formation-internationale.index', ['statut' => 'annule']) }}"
                class="px-4 py-2 rounded {{ request('statut') == 'annule' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Annulées ({{ \App\Models\DemandeFormationInternationale::where('statut', 'annule')->count() }})
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entreprise</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $demande->nom_responsable ?? $demande->nom_complet }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $demande->nom_entreprise ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $demande->email }}</div>
                            <div class="text-sm text-gray-500">{{ $demande->telephone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                                {{ $demande->destination_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $demande->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $demande->statut_color }}">
                                {{ $demande->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.demandes-formation-internationale.show', $demande) }}"
                                    class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.demandes-formation-internationale.edit', $demande) }}"
                                    class="text-green-600 hover:text-green-900" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.demandes-formation-internationale.destroy', $demande) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Supprimer cette demande ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Aucune demande trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
        $stats = \App\Http\Controllers\Admin\FormationInternationaleController::getStatistics();
        @endphp
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 mr-4">
                    <i class="fas fa-inbox text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nouvelles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['nouveau'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <i class="fas fa-spinner text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En cours</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['en_cours'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Traitées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['traite'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Annulées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['annule'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
