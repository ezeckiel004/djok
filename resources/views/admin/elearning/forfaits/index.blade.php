@extends('layouts.admin')

@section('title', 'Gestion des forfaits E-learning | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des forfaits</h1>
                <p class="text-gray-600 mt-1">Configurez les offres e-learning disponibles</p>
            </div>
            <a href="{{ route('admin.elearning.forfaits.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nouveau forfait
            </a>
        </div>
    </div>

    <!-- Liste des forfaits -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Forfait
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durée
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contenu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Accès
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($forfaits as $forfait)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $forfait->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $forfait->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $forfait->formatted_price }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $forfait->duration_days }} jours</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $hasCustomContent = !$forfait->include_all_cours || !$forfait->include_all_qcms || !$forfait->include_all_examens;
                                $coursCount = $forfait->include_all_cours ? 'Tous' : count($forfait->selected_cours_ids ?? []);
                                $qcmsCount = $forfait->include_all_qcms ? 'Tous' : count($forfait->selected_qcms_ids ?? []);
                                $examensCount = $forfait->include_all_examens ? 'Tous' : count($forfait->selected_examens_ids ?? []);
                            @endphp
                            @if($hasCustomContent)
                                <div class="space-y-1">
                                    <div class="flex items-center text-xs">
                                        <i class="fas fa-book w-4 text-blue-500 mr-1"></i>
                                        <span class="text-gray-600">Cours:</span>
                                        <span class="ml-1 font-medium">{{ $coursCount }}</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <i class="fas fa-question-circle w-4 text-green-500 mr-1"></i>
                                        <span class="text-gray-600">QCM:</span>
                                        <span class="ml-1 font-medium">{{ $qcmsCount }}</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <i class="fas fa-star w-4 text-purple-500 mr-1"></i>
                                        <span class="text-gray-600">Examens:</span>
                                        <span class="ml-1 font-medium">{{ $examensCount }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-layer-group mr-1"></i> Tout inclus
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $forfait->acces_count }} accès</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($forfait->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Actif
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Inactif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.elearning.forfaits.edit', $forfait->id) }}"
                                    class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.elearning.acces') }}?forfait={{ $forfait->id }}"
                                    class="text-green-600 hover:text-green-800" title="Voir les accès">
                                    <i class="fas fa-users"></i>
                                </a>
                                <button type="button"
                                    onclick="showDeleteModal({{ $forfait->id }}, '{{ addslashes($forfait->name) }}', {{ $forfait->acces_count }})"
                                    class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($forfaits->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Aucun forfait créé pour le moment.</p>
            <a href="{{ route('admin.elearning.forfaits.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Créer votre premier forfait
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir supprimer le forfait <strong id="deleteForfaitName"></strong> ?
                </p>
                <p id="deleteWarning" class="text-xs text-red-600 mt-2 hidden">
                    ⚠️ Attention : Ce forfait a <span id="accesCount"></span> accès associé(s). Tous les accès, sessions et progressions seront également supprimés définitivement.
                </p>
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showDeleteModal(id, name, accesCount) {
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteForfaitName = document.getElementById('deleteForfaitName');
        const deleteWarning = document.getElementById('deleteWarning');
        const accesCountSpan = document.getElementById('accesCount');

        deleteForfaitName.textContent = name;
        deleteForm.action = "{{ route('admin.elearning.forfaits.destroy', '') }}/" + id;

        if (accesCount > 0) {
            deleteWarning.classList.remove('hidden');
            accesCountSpan.textContent = accesCount;
        } else {
            deleteWarning.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endpush
