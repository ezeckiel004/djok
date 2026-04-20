@extends('layouts.client')

@section('title', 'Acheter ' . $forfait->name . ' - DJOK PRESTIGE')
@section('page-title', 'Acheter un forfait e-learning')
@section('page-description', 'Finalisez votre achat pour accéder à la formation e-learning')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-500">E-learning</span>
    </div>
</li>
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-500">Acheter</span>
    </div>
</li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('client.elearning.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux forfaits
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Forfait details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Votre sélection</h2>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $forfait->name }}</h3>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-yellow-600">{{ $forfait->formatted_price }}</div>
                        <div class="text-sm text-gray-500">{{ $forfait->duration_days }} jours d'accès</div>
                    </div>
                </div>

                <!-- Badge mode de sélection -->
                <div class="mb-4">
                    @if($forfait->include_all_cours && $forfait->include_all_qcms && $forfait->include_all_examens)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-layer-group mr-1"></i> Tout inclus
                        </span>
                        <p class="mt-2 text-sm text-gray-600">Accès à tout le contenu de la plateforme (tous les cours, QCM et examens)</p>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-check-double mr-1"></i> Sélection personnalisée
                        </span>
                        <p class="mt-2 text-sm text-gray-600">Contenu sélectionné spécialement pour ce forfait</p>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-clock text-yellow-500"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Durée d'accès</h4>
                            <p class="text-gray-600">{{ $forfait->duration_days }} jours à compter de l'achat</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-book text-yellow-500"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Contenu inclus</h4>
                            @if($forfait->include_all_cours && $forfait->include_all_qcms && $forfait->include_all_examens)
                                <p class="text-gray-600">Tous les cours, QCM et examens blancs</p>
                            @else
                                <ul class="mt-1 space-y-1 text-sm text-gray-600">
                                    @php
                                        $coursCount = $forfait->include_all_cours ? 'Tous les cours' : count($forfait->selected_cours_ids ?? []) . ' cours sélectionnés';
                                        $qcmsCount = $forfait->include_all_qcms ? 'Tous les QCM' : count($forfait->selected_qcms_ids ?? []) . ' QCM sélectionnés';
                                        $examensCount = $forfait->include_all_examens ? 'Tous les examens' : count($forfait->selected_examens_ids ?? []) . ' examens sélectionnés';
                                    @endphp
                                    <li><i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i> {{ $coursCount }}</li>
                                    @if($forfait->includes_qcm)
                                    <li><i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i> {{ $qcmsCount }}</li>
                                    @endif
                                    @if($forfait->includes_examens_blancs)
                                    <li><i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i> {{ $examensCount }}</li>
                                    @endif
                                    @if($forfait->includes_certification)
                                    <li><i class="fas fa-check-circle text-green-500 mr-2 text-xs"></i> Certification incluse</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start">
                        <i class="mt-1 mr-3 fas fa-lock text-yellow-500"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Sécurité</h4>
                            <p class="text-gray-600">1 seule connexion simultanée autorisée</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-info-circle text-blue-500"></i>
                        <div>
                            <p class="text-sm text-blue-800">
                                <strong>Important :</strong> Vous recevrez vos codes d'accès par email immédiatement après le paiement.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte récapitulative des informations client (non modifiable) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Vos informations</h2>
                <p class="text-sm text-gray-500 mt-1">Confirmation des informations de votre compte</p>
            </div>

            <div class="p-6">
                <!-- Carte des informations utilisateur -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Informations du compte</h3>
                            <p class="text-xs text-gray-500">Ces informations seront utilisées pour votre accès</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Prénom & Nom</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Email</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                        </div>
                        @if($user->phone)
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Téléphone</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Message de confirmation -->
                <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm text-green-700">
                            Le paiement sera effectué avec votre email <strong>{{ $user->email }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Formulaire caché avec les données pré-remplies -->
                <form id="paymentForm" action="{{ route('client.elearning.process-payment', $forfait->slug) }}" method="POST">
                    @csrf
                    <input type="hidden" name="prenom" value="{{ explode(' ', $user->name)[0] ?? '' }}">
                    <input type="hidden" name="nom" value="{{ explode(' ', $user->name)[1] ?? $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="telephone" value="{{ $user->phone }}">

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-lg font-semibold text-gray-900">Total à payer</span>
                            <span class="text-3xl font-bold text-yellow-600">{{ $forfait->formatted_price }}</span>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="w-full flex items-center justify-center py-3 px-4 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-lock mr-2"></i>
                            Confirmer et payer {{ $forfait->formatted_price }}
                        </button>

                        <p class="mt-4 text-xs text-center text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Paiement sécurisé par Stripe
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informations complémentaires -->
    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <i class="text-2xl fas fa-envelope text-yellow-500 mb-3"></i>
            <h4 class="font-bold text-gray-900 mb-2">Code immédiat</h4>
            <p class="text-sm text-gray-600">Code d'accès envoyé par email après paiement</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <i class="text-2xl fas fa-headset text-yellow-500 mb-3"></i>
            <h4 class="font-bold text-gray-900 mb-2">Support inclus</h4>
            <p class="text-sm text-gray-600">Assistance technique par email et téléphone</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <i class="text-2xl fas fa-sync text-yellow-500 mb-3"></i>
            <h4 class="font-bold text-gray-900 mb-2">Satisfait ou remboursé</h4>
            <p class="text-sm text-gray-600">14 jours pour changer d'avis</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement en cours...';
        return true;
    });
</script>
@endsection
