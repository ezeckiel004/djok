@extends('layouts.admin')

@section('title', 'Gestion des sessions')

@section('page-title', 'Gestion des sessions de formation')

@section('page-actions')
<a href="{{ route('admin.sessions.create') }}" class="btn-primary">
    <i class="fas fa-plus mr-2"></i>Nouvelle session
</a>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6">
        <!-- Filtres -->
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Formation</label>
                <select name="formation_id" class="w-full rounded-lg border-gray-300">
                    <option value="">Toutes les formations</option>
                    @foreach($formations as $formation)
                    <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                        {{ $formation->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="w-full rounded-lg border-gray-300">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                    <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Passées</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-secondary w-full">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('admin.sessions.index') }}" class="btn-secondary w-full text-center">
                    <i class="fas fa-undo mr-2"></i>Réinitialiser
                </a>
            </div>
        </form>

        <!-- Tableau des sessions -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Nom de la session</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Formation</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Dates</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Lieu</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Places</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Inscrits</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Statut</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $session->name }}</div>
                            <div class="text-sm text-gray-500">{{ $session->formatted_price }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.formations.show', $session->formation) }}" class="text-blue-600 hover:underline">
                                {{ Str::limit($session->formation->title, 40) }}
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <div>{{ $session->formatted_dates }}</div>
                            @if($session->formatted_schedule)
                            <div class="text-sm text-gray-500">{{ $session->formatted_schedule }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            {{ $session->location ?: '—' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="font-medium">{{ $session->available_places }} / {{ $session->max_places }}</div>
                            @if($session->available_places <= 5 && $session->available_places > 0)
                            <span class="text-xs text-orange-500">Plus que {{ $session->available_places }} places</span>
                            @elseif($session->available_places == 0)
                            <span class="text-xs text-red-500">Complet</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('admin.sessions.show', $session) }}" class="text-blue-600 hover:underline">
                                {{ $session->participants()->count() }}
                            </a>
                        </td>
                        <td class="py-3 px-4 text-center">
                            {!! $session->status_badge !!}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.sessions.show', $session) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.sessions.edit', $session) }}"
                                   class="text-yellow-600 hover:text-yellow-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- <button type="button" onclick="toggleStatus({{ $session->id }}, {{ $session->is_active ? 'true' : 'false' }})"
                                        class="text-{{ $session->is_active ? 'green' : 'gray' }}-600 hover:text-{{ $session->is_active ? 'green' : 'gray' }}-800"
                                        title="{{ $session->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $session->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                </button>
                                <button type="button" onclick="adjustPlaces({{ $session->id }})"
                                        class="text-purple-600 hover:text-purple-800" title="Ajuster les places">
                                    <i class="fas fa-users"></i>
                                </button> --}}
                                <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"
                                            title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            Aucune session trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $sessions->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(sessionId, currentStatus) {
    const newStatus = !currentStatus;
    const action = newStatus ? 'activer' : 'désactiver';

    if (confirm(`Êtes-vous sûr de vouloir ${action} cette session ?`)) {
        fetch(`/admin/sessions/${sessionId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

function adjustPlaces(sessionId) {
    const adjustment = prompt('Entrez le nombre de places à ajouter (+) ou retirer (-):', '0');

    if (adjustment !== null) {
        const numAdjustment = parseInt(adjustment);
        if (isNaN(numAdjustment)) {
            alert('Veuillez entrer un nombre valide');
            return;
        }

        const reason = prompt('Raison de l\'ajustement (optionnel):', '');

        fetch(`/admin/sessions/${sessionId}/adjust-places`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                adjustment: numAdjustment,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}
</script>
@endpush
@endsection
