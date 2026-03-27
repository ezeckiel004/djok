@extends('layouts.admin')

@section('title', 'Détails de la session')

@section('page-title', 'Détails de la session')

@section('page-actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.sessions.edit', $session) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>Modifier
    </a>
    <a href="{{ route('admin.sessions.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Informations générales -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informations générales</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Nom de la session</p>
                    <p class="font-medium text-gray-900">{{ $session->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Formation associée</p>
                    <a href="{{ route('admin.formations.show', $session->formation) }}" class="font-medium text-djok-yellow hover:underline">
                        {{ $session->formation->title }}
                    </a>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Type</p>
                    <p class="font-medium text-gray-900">
                        @if($session->type == 'presentiel')
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Présentiel</span>
                        @elseif($session->type == 'e_learning')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">E-learning</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Mixte</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <p>{!! $session->status_badge !!}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Période</p>
                    <p class="font-medium text-gray-900">{{ $session->formatted_dates }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Horaires</p>
                    <p class="font-medium text-gray-900">{{ $session->formatted_schedule ?: 'Non spécifié' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Lieu</p>
                    <p class="font-medium text-gray-900">{{ $session->location ?: 'Non spécifié' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prix</p>
                    <p class="font-medium text-gray-900">{{ $session->formatted_price }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Places et inscriptions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Places et inscriptions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-djok-yellow">{{ $session->max_places }}</p>
                    <p class="text-sm text-gray-500">Places totales</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $session->available_places }}</p>
                    <p class="text-sm text-gray-500">Places disponibles</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600">{{ $participantsCount }}</p>
                    <p class="text-sm text-gray-500">Inscrits</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-orange-600">{{ $waitingListCount }}</p>
                    <p class="text-sm text-gray-500">Liste d'attente</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <button onclick="openAdjustPlacesModal({{ $session->id }}, {{ $session->available_places }}, {{ $session->max_places }})"
                        class="btn-secondary">
                    <i class="fas fa-users mr-2"></i>Ajuster les places
                </button>
                <a href="{{ route('admin.sessions.export', $session) }}" class="btn-secondary">
                    <i class="fas fa-download mr-2"></i>Exporter les inscrits
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des participants -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Participants inscrits</h3>
        </div>
        <div class="p-6">
            @if($session->participants->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($session->participants as $participant)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.participants.show', $participant->id) }}"
                                   class="font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $participant->prenom }} {{ $participant->nom }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <a href="mailto:{{ $participant->email }}" class="hover:text-blue-600 hover:underline">
                                    {{ $participant->email }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if($participant->telephone)
                                <a href="tel:{{ $participant->telephone }}" class="hover:text-blue-600">
                                    {{ $participant->telephone }}
                                </a>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $participant->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                $statusColors = [
                                    'confirme' => 'bg-green-100 text-green-800',
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'annule' => 'bg-red-100 text-red-800',
                                    'termine' => 'bg-blue-100 text-blue-800',
                                ];
                                $statusLabels = [
                                    'confirme' => 'Confirmé',
                                    'en_attente' => 'En attente',
                                    'annule' => 'Annulé',
                                    'termine' => 'Terminé',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$participant->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$participant->statut] ?? $participant->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.participants.show', $participant->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition-colors duration-150"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.participants.edit', $participant->id) }}"
                                       class="text-yellow-600 hover:text-yellow-800 transition-colors duration-150"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- <button type="button"
                                            onclick="sendEmail('{{ $participant->email }}', '{{ addslashes($participant->prenom . ' ' . $participant->nom) }}', '{{ addslashes($session->name) }}')"
                                            class="text-purple-600 hover:text-purple-800 transition-colors duration-150"
                                            title="Envoyer un email">
                                        <i class="fas fa-envelope"></i>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-3">
                    <i class="fas fa-users text-5xl"></i>
                </div>
                <p class="text-gray-500 text-lg">Aucun participant inscrit pour cette session.</p>
                <p class="text-gray-400 text-sm mt-2">Les inscriptions apparaîtront ici une fois que des participants se seront inscrits.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour ajuster les places -->
<div id="adjustPlacesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeAdjustPlacesModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="adjustPlacesForm" onsubmit="submitAdjustPlaces(event)">
                                <div class="px-6 pt-6 pb-4 bg-white">
                    <div class="flex items-center justify-between pb-4 border-b">
                        <h3 class="text-xl font-semibold text-gray-900" id="modal-title">
                            Ajuster les places disponibles
                        </h3>
                        <button type="button" onclick="closeAdjustPlacesModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="mt-6 space-y-4">
                        <!-- Situation actuelle -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">Places actuelles</p>
                                    <p class="text-2xl font-bold text-blue-600" id="currentPlaces">0</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">Places max</p>
                                    <p class="text-2xl font-bold text-gray-700" id="maxPlaces">0</p>
                                </div>
                            </div>
                        </div>

                        <!-- Choix de l'ajustement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Type d'ajustement
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="adjustment_type" value="add" class="h-4 w-4 text-green-600" checked>
                                    <span class="ml-2 text-gray-700">Ajouter des places</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="adjustment_type" value="remove" class="h-4 w-4 text-red-600">
                                    <span class="ml-2 text-gray-700">Retirer des places</span>
                                </label>
                            </div>
                        </div>

                        <!-- Nombre de places -->
                        <div>
                            <label for="adjustment_value" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de places
                            </label>
                            <input type="number"
                                   id="adjustment_value"
                                   name="adjustment_value"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent"
                                   min="1"
                                   max="100"
                                   value="1"
                                   required>
                            <p class="mt-1 text-xs text-gray-500" id="adjustmentInfo"></p>
                        </div>

                        <!-- Raison (optionnelle) -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Raison (optionnelle)
                            </label>
                            <textarea id="reason"
                                      name="reason"
                                      rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-djok-yellow focus:border-transparent"
                                      placeholder="Ex: Annulation de dernière minute, ajout de places supplémentaires..."></textarea>
                        </div>

                        <!-- Aperçu du résultat -->
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-blue-800 mb-2">Aperçu du résultat</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-blue-700">Nouveau nombre de places :</span>
                                <span class="text-xl font-bold text-blue-900" id="previewResult">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-djok-yellow border border-transparent rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-djok-yellow sm:ml-3 sm:w-auto">
                        <i class="fas fa-save mr-2"></i> Confirmer l'ajustement
                    </button>
                    <button type="button"
                            onclick="closeAdjustPlacesModal()"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-djok-yellow sm:mt-0 sm:ml-3 sm:w-auto">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentSessionId = null;
let currentAvailablePlaces = 0;
let currentMaxPlaces = 0;

// Fonction pour obtenir le token CSRF
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) {
        return meta.content;
    }
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        return tokenInput.value;
    }
    console.warn('CSRF token not found, using fallback');
    return '{{ csrf_token() }}';
}

function openAdjustPlacesModal(sessionId, availablePlaces, maxPlaces) {
    console.log('Opening modal for session:', sessionId, availablePlaces, maxPlaces);

    currentSessionId = sessionId;
    currentAvailablePlaces = availablePlaces;
    currentMaxPlaces = maxPlaces;

    const currentPlacesEl = document.getElementById('currentPlaces');
    const maxPlacesEl = document.getElementById('maxPlaces');
    const adjustmentValue = document.getElementById('adjustment_value');
    const reasonEl = document.getElementById('reason');

    if (currentPlacesEl) currentPlacesEl.textContent = availablePlaces;
    if (maxPlacesEl) maxPlacesEl.textContent = maxPlaces;

    if (adjustmentValue) adjustmentValue.value = 1;
    if (reasonEl) reasonEl.value = '';

    const addRadio = document.querySelector('input[name="adjustment_type"][value="add"]');
    if (addRadio) addRadio.checked = true;

    updatePreview();

    const modal = document.getElementById('adjustPlacesModal');
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        console.error('Modal element not found');
        alert('Erreur: le modal n\'a pas été trouvé');
    }
}

function closeAdjustPlacesModal() {
    const modal = document.getElementById('adjustPlacesModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function updatePreview() {
    const adjustmentTypeRadio = document.querySelector('input[name="adjustment_type"]:checked');
    const adjustmentValue = document.getElementById('adjustment_value');

    if (!adjustmentTypeRadio || !adjustmentValue) return;

    const adjustmentType = adjustmentTypeRadio.value;
    const value = parseInt(adjustmentValue.value) || 0;
    const currentPlaces = currentAvailablePlaces;
    const maxPlaces = currentMaxPlaces;

    let newPlaces = currentPlaces;
    let infoText = '';

    if (adjustmentType === 'add') {
        newPlaces = currentPlaces + value;
        infoText = `Vous allez ajouter ${value} place${value > 1 ? 's' : ''}.`;

        if (newPlaces > maxPlaces) {
            infoText += ` Attention : vous dépasserez la capacité maximale (${maxPlaces} places). La capacité max sera augmentée.`;
        }
    } else {
        newPlaces = currentPlaces - value;
        infoText = `Vous allez retirer ${value} place${value > 1 ? 's' : ''}.`;

        if (newPlaces < 0) {
            infoText += ` Attention : vous ne pouvez pas avoir de places négatives.`;
            newPlaces = 0;
        }
    }

    const previewResult = document.getElementById('previewResult');
    const adjustmentInfo = document.getElementById('adjustmentInfo');

    if (previewResult) previewResult.textContent = newPlaces;
    if (adjustmentInfo) adjustmentInfo.textContent = infoText;
}

function submitAdjustPlaces(event) {
    event.preventDefault();

    console.log('Submitting adjustment...');

    const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
    const adjustmentValue = document.getElementById('adjustment_value');
    const reason = document.getElementById('reason');

    if (!adjustmentType || !adjustmentValue) {
        alert('Erreur: formulaire incomplet');
        return;
    }

    const adjustmentTypeValue = adjustmentType.value;
    const value = parseInt(adjustmentValue.value);
    const reasonText = reason ? reason.value : '';

    if (isNaN(value) || value < 1) {
        alert('Veuillez entrer un nombre valide');
        return;
    }

    let adjustment = adjustmentTypeValue === 'add' ? value : -value;

    console.log('Adjustment:', adjustment, 'Reason:', reasonText);

    let newPlaces = currentAvailablePlaces + adjustment;
    if (newPlaces < 0) {
        alert('Impossible de retirer plus de places que disponibles.');
        return;
    }

    // Désactiver le bouton pour éviter les doubles soumissions
    const submitBtn = document.querySelector('#adjustPlacesForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
    }

    fetch(`/admin/sessions/${currentSessionId}/adjust-places`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            adjustment: adjustment,
            reason: reasonText
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const message = adjustment > 0
                ? `${adjustment} place${adjustment > 1 ? 's' : ''} ajoutée${adjustment > 1 ? 's' : ''} avec succès !`
                : `${Math.abs(adjustment)} place${Math.abs(adjustment) > 1 ? 's' : ''} retirée${Math.abs(adjustment) > 1 ? 's' : ''} avec succès !`;
            alert(message);
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Confirmer l\'ajustement';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de l\'ajustement: ' + (error.message || 'Erreur réseau'));
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Confirmer l\'ajustement';
        }
    });
}

// Écouter les changements pour mettre à jour l'aperçu
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Session show page');

    const adjustmentValue = document.getElementById('adjustment_value');
    const adjustmentTypeRadios = document.querySelectorAll('input[name="adjustment_type"]');

    if (adjustmentValue) {
        adjustmentValue.addEventListener('input', updatePreview);
    }

    adjustmentTypeRadios.forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });
});

function sendEmail(email, name, sessionName) {
    const subject = encodeURIComponent(`Information sur la session ${sessionName}`);
    const body = encodeURIComponent(`Bonjour ${name},\n\nNous vous contactons concernant votre inscription à la session "${sessionName}".\n\nCordialement,\nL'équipe DJOK PRESTIGE`);

    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
}
</script>
@endpush
@endsection
