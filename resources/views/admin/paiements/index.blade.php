{{-- resources\views\admin\paiements\index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Gestion des paiements | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Gestion des paiements</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Suivi des transactions multi-services</p>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.paiements.statistiques') }}"
                    class="inline-flex items-center px-3 sm:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm sm:text-base">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span class="hidden sm:inline">Statistiques</span>
                    <span class="sm:hidden">Stats</span>
                </a>

                <a href="{{ route('admin.paiements.export') }}"
                    class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm sm:text-base">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden sm:inline">Exporter</span>
                    <span class="sm:hidden">Exp.</span>
                </a>

                <div class="flex flex-wrap gap-2">
                    <select id="serviceTypeFilter"
                        class="border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="filterByServiceType(this.value)">
                        <option value="">Tous services</option>
                        <option value="formation" {{ request('service_type')=='formation' ? 'selected' : '' }}>Formations</option>
                        <option value="reservation" {{ request('service_type')=='reservation' ? 'selected' : '' }}>Réservations VTC</option>
                        <option value="location" {{ request('service_type')=='location' ? 'selected' : '' }}>Locations</option>
                        <option value="conciergerie" {{ request('service_type')=='conciergerie' ? 'selected' : '' }}>Conciergerie</option>
                        <option value="formation_internationale" {{ request('service_type')=='formation_internationale' ? 'selected' : '' }}>Form. int.</option>
                    </select>

                    <select id="statusFilter"
                        class="border border-gray-300 rounded-lg px-2 sm:px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="filterByStatus(this.value)">
                        <option value="">Tous statuts</option>
                        <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Payés</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>En attente</option>
                        <option value="canceled" {{ request('status')=='canceled' ? 'selected' : '' }}>Annulés</option>
                        <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Échoués</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
            <p class="text-yellow-800 font-medium">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    <div id="bulkActionsBar" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center">
                <i class="fas fa-check-square text-blue-600 text-xl mr-3"></i>
                <span class="text-blue-800 font-medium">
                    <span id="selectedCount">0</span> paiement(s) sélectionné(s)
                </span>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="confirmBulkDelete()"
                    class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Supprimer
                </button>
                <button type="button" onclick="clearSelection()"
                    class="px-3 sm:px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors text-sm">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-blue-100 text-blue-600 mr-3 sm:mr-4">
                    <i class="fas fa-money-check-alt text-base sm:text-xl"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistiques['total'] }}</div>
                    <div class="text-xs sm:text-sm text-gray-600">Total paiements</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-green-100 text-green-600 mr-3 sm:mr-4">
                    <i class="fas fa-check-circle text-base sm:text-xl"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistiques['payes'] }}</div>
                    <div class="text-xs sm:text-sm text-gray-600">Payés</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-yellow-100 text-yellow-600 mr-3 sm:mr-4">
                    <i class="fas fa-clock text-base sm:text-xl"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistiques['en_attente'] }}</div>
                    <div class="text-xs sm:text-sm text-gray-600">En attente</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-purple-100 text-purple-600 mr-3 sm:mr-4">
                    <i class="fas fa-euro-sign text-base sm:text-xl"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($statistiques['total_amount'], 0, ',', ' ') }} €</div>
                    <div class="text-xs sm:text-sm text-gray-600">CA total</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution par service -->
    <div class="mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Répartition par service</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
                @foreach($statistiques['par_service'] as $serviceType => $count)
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $count }}</div>
                    <div class="text-xs sm:text-sm text-gray-600">
                        @if($serviceType === 'formation') Formations
                        @elseif($serviceType === 'reservation') Réservations
                        @elseif($serviceType === 'location') Locations
                        @elseif($serviceType === 'conciergerie') Conciergerie
                        @elseif($serviceType === 'formation_internationale') Form. Int.
                        @else {{ $serviceType }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Liste des transactions</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $paiements->total() }} transactions trouvées</p>
        </div>

        <div class="overflow-x-auto">
            <form id="bulkDeleteForm" action="{{ route('admin.paiements.bulk-destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paiements as $paiement)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <input type="checkbox" name="paiement_ids[]" value="{{ $paiement->id }}" class="paiement-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <div class="font-mono text-xs sm:text-sm text-gray-900">{{ $paiement->reference }}</div>
                                <div class="text-xs text-gray-500 hidden sm:block">Stripe: {{ substr($paiement->stripe_session_id ?? 'N/A', 0, 10) }}...</div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <div class="text-xs sm:text-sm text-gray-900">{{ $paiement->customer_name }}</div>
                                <div class="text-xs text-gray-500 hidden sm:block">{{ $paiement->customer_email ?? 'N/A' }}</div>
                                @if($paiement->user)
                                <div class="text-xs mt-1">
                                    <a href="{{ route('admin.users.show', $paiement->user_id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-user mr-1"></i> Compte
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4">
                                <div class="flex flex-col">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->service_type_color }} w-fit">
                                        {{ $paiement->formatted_service_type }}
                                    </span>
                                    <span class="text-xs sm:text-sm text-gray-900 mt-1 sm:mt-0 sm:ml-2">
                                        {{ Str::limit($paiement->service_name, 20) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm font-semibold text-gray-900">{{ number_format($paiement->amount, 0, ',', ' ') }} €</div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                    'failed' => 'bg-gray-100 text-gray-800',
                                    'refunded' => 'bg-purple-100 text-purple-800',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$paiement->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    @if($paiement->status === 'paid') Payé
                                    @elseif($paiement->status === 'pending') En attente
                                    @elseif($paiement->status === 'canceled') Annulé
                                    @elseif($paiement->status === 'failed') Échoué
                                    @elseif($paiement->status === 'refunded') Remboursé
                                    @else {{ $paiement->status }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                {{ $paiement->created_at->format('d/m/Y') }}<br>
                                <span class="text-xs hidden sm:inline">{{ $paiement->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('admin.paiements.show', $paiement) }}"
                                        class="inline-flex items-center justify-center px-2 sm:px-3 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span class="hidden sm:inline">Détails</span>
                                    </a>
                                    <button type="button"
                                        onclick="deletePayment({{ $paiement->id }}, '{{ addslashes($paiement->reference) }}', '{{ $paiement->status }}', '{{ addslashes($paiement->formatted_service_type) }}')"
                                        class="inline-flex items-center justify-center px-2 sm:px-3 py-1 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span class="hidden sm:inline">Supprimer</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900">Aucun paiement trouvé</p>
                                    <p class="text-gray-600 mt-1">Aucune transaction n'a été enregistrée pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        @if($paiements->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $paiements->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal confirmation suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[90%] sm:w-[500px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-4">Confirmer la suppression</h3>
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 break-words"><strong>Référence :</strong> <span id="deleteReference" class="font-mono"></span></p>
                <p class="text-sm text-gray-600 mt-2"><strong>Service :</strong> <span id="deleteServiceType"></span></p>
                <p class="text-sm text-gray-600 mt-2"><strong>Statut :</strong> <span id="deletePaymentStatus"></span></p>
            </div>
            <div id="sensitiveWarning" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800">⚠️ ATTENTION : Suppression critique</p>
                        <p class="text-xs text-red-700 mt-1">Ce paiement est déjà <strong id="sensitiveStatus"></strong>.</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-3 mt-6">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-trash-alt mr-2"></i> Oui, supprimer
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Définir toutes les fonctions dans la portée globale
window.deletePayment = function(id, reference, status, serviceType) {
    console.log('deletePayment called', id, reference, status, serviceType);

    const modal = document.getElementById('deleteModal');
    if (!modal) return;

    document.getElementById('deleteReference').innerText = reference;
    document.getElementById('deleteServiceType').innerText = serviceType;

    let statusText = '';
    let isSensitive = false;

    switch(status) {
        case 'paid':
            statusText = 'Payé ✅';
            isSensitive = true;
            break;
        case 'refunded':
            statusText = 'Remboursé 🔄';
            isSensitive = true;
            break;
        case 'pending':
            statusText = 'En attente ⏳';
            break;
        case 'canceled':
            statusText = 'Annulé ❌';
            break;
        case 'failed':
            statusText = 'Échoué ⚠️';
            break;
        default:
            statusText = status;
    }

    document.getElementById('deletePaymentStatus').innerHTML = '<span class="font-semibold">' + statusText + '</span>';

    const sensitiveWarning = document.getElementById('sensitiveWarning');
    if (isSensitive) {
        sensitiveWarning.classList.remove('hidden');
        document.getElementById('sensitiveStatus').innerText = status === 'paid' ? 'PAYÉ' : 'REMBOURSÉ';
    } else {
        sensitiveWarning.classList.add('hidden');
    }

    const form = document.getElementById('deleteForm');
    if (form) {
        form.action = '/admin/paiements/' + id;
    }

    modal.classList.remove('hidden');
};

window.closeDeleteModal = function() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.filterByServiceType = function(serviceType) {
    const url = new URL(window.location.href);
    if (serviceType) {
        url.searchParams.set('service_type', serviceType);
    } else {
        url.searchParams.delete('service_type');
    }
    window.location.href = url.toString();
};

window.filterByStatus = function(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
};

window.updateBulkActionsBar = function() {
    const checkboxes = document.querySelectorAll('.paiement-checkbox:checked');
    const count = checkboxes.length;
    const bar = document.getElementById('bulkActionsBar');
    if (bar) {
        if (count > 0) {
            bar.classList.remove('hidden');
            document.getElementById('selectedCount').innerText = count;
        } else {
            bar.classList.add('hidden');
        }
    }
};

window.clearSelection = function() {
    document.querySelectorAll('.paiement-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    window.updateBulkActionsBar();
};

window.confirmBulkDelete = function() {
    const selected = document.querySelectorAll('.paiement-checkbox:checked');
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un paiement.');
        return;
    }
    if (confirm('Supprimer ' + selected.length + ' paiement(s) ? Cette action est irréversible.')) {
        document.getElementById('bulkDeleteForm').submit();
    }
};

// Initialisation au chargement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initPaymentPage();
    });
} else {
    initPaymentPage();
}

function initPaymentPage() {
    window.updateBulkActionsBar();

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function(e) {
            document.querySelectorAll('.paiement-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
            });
            window.updateBulkActionsBar();
        });
    }

    document.querySelectorAll('.paiement-checkbox').forEach(cb => {
        cb.addEventListener('change', window.updateBulkActionsBar);
    });
}

// Fermer la modale en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        window.closeDeleteModal();
    }
};

// Log pour vérifier que les fonctions sont chargées
console.log('Payment page scripts loaded', {
    deletePayment: typeof window.deletePayment,
    closeDeleteModal: typeof window.closeDeleteModal,
    filterByServiceType: typeof window.filterByServiceType,
    filterByStatus: typeof window.filterByStatus
});
</script>
@endpush
