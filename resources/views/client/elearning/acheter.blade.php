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
                        <div class="text-2xl font-bold text-yellow-600" id="originalPrice">{{ $forfait->formatted_price }}</div>
                        <div class="text-sm text-gray-500">{{ $forfait->duration_days }} jours d'accès</div>
                    </div>
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
                        <i class="mt-1 mr-3 fas fa-lock text-yellow-500"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Sécurité</h4>
                            <p class="text-gray-600">{{ $forfait->max_concurrent_connections }} connexion(s) simultanée(s) autorisée(s)</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="flex items-center">
                        <i class="mr-3 fas fa-info-circle text-blue-500"></i>
                        <div>
                            <p class="text-sm text-blue-800">
                                <strong>Important :</strong> Vous recevrez vos codes d'accès par email immédiatement après validation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte récapitulative des informations client -->
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

                <!-- Section Code Promo -->
                <div class="mb-6 bg-purple-50 rounded-lg p-4 border border-purple-200" id="promoCodeSection">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-ticket-alt text-purple-600 mr-2"></i>
                        <h4 class="font-semibold text-gray-900">Code promo ?</h4>
                    </div>

                    <div class="flex gap-3">
                        <input type="text" id="promo_code"
                            placeholder="Saisissez votre code promo"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            autocomplete="off">
                        <button type="button" id="applyPromoCodeBtn"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-check mr-1"></i> Appliquer
                        </button>
                    </div>

                    <div id="promoCodeMessage" class="mt-2 text-sm hidden"></div>
                </div>

                <!-- Message de confirmation code promo appliqué -->
                <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg" id="promoSuccessMessage" style="display: none;">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm text-green-700">
                            Code promo appliqué avec succès ! Votre accès sera gratuit.
                        </p>
                    </div>
                </div>

                <!-- Formulaire -->
                <form id="paymentForm" action="{{ route('client.elearning.process-payment', $forfait->slug) }}" method="POST">
                    @csrf
                    <input type="hidden" name="prenom" value="{{ explode(' ', $user->name)[0] ?? '' }}">
                    <input type="hidden" name="nom" value="{{ explode(' ', $user->name)[1] ?? $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="telephone" value="{{ $user->phone }}">

                    <!-- Champs pour le code promo -->
                    <input type="hidden" name="access_mode" id="access_mode" value="payment">
                    <input type="hidden" name="promo_code_used" id="promo_code_used" value="">

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-6" id="priceContainer">
                            <span class="text-lg font-semibold text-gray-900">Total à payer</span>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-yellow-600" id="totalPrice">{{ $forfait->formatted_price }}</span>
                            </div>
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
            <p class="text-sm text-gray-600">Code d'accès envoyé par email après validation</p>
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

@push('scripts')
<script>
// Script pour la gestion du code promo
(function() {
    console.log('Script promo chargé - Démarrage');

    // Attendre que le DOM soit complètement chargé
    function init() {
        console.log('DOM chargé - Initialisation du système de code promo');

        var btn = document.getElementById('applyPromoCodeBtn');
        var promoCodeInput = document.getElementById('promo_code');
        var promoCodeMessage = document.getElementById('promoCodeMessage');
        var promoSuccessMessage = document.getElementById('promoSuccessMessage');
        var promoCodeSection = document.getElementById('promoCodeSection');
        var submitBtn = document.getElementById('submitBtn');
        var accessModeInput = document.getElementById('access_mode');
        var promoCodeUsedInput = document.getElementById('promo_code_used');
        var totalPriceSpan = document.getElementById('totalPrice');

        if (!btn) {
            console.error('Bouton applyPromoCodeBtn non trouvé !');
            return;
        }

        console.log('Bouton trouvé, attachement de l\'événement');

        var originalPrice = document.getElementById('originalPrice') ? document.getElementById('originalPrice').innerText : '{{ $forfait->formatted_price }}';
        var appliedPromoCode = null;

        function showMessage(message, type) {
            if (!promoCodeMessage) return;
            promoCodeMessage.textContent = message;
            promoCodeMessage.classList.remove('hidden', 'text-green-600', 'text-red-600');
            promoCodeMessage.classList.add(type === 'success' ? 'text-green-600' : 'text-red-600');
            promoCodeMessage.classList.remove('hidden');
            setTimeout(function() {
                if (type !== 'success' && promoCodeMessage) {
                    promoCodeMessage.classList.add('hidden');
                }
            }, 5000);
        }

        function getCsrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) return meta.getAttribute('content');
            var tokenInput = document.querySelector('input[name="_token"]');
            if (tokenInput) return tokenInput.value;
            return '';
        }

        function applyPromoCodeUI(code) {
            appliedPromoCode = code;
            if (promoCodeUsedInput) promoCodeUsedInput.value = code;
            if (accessModeInput) accessModeInput.value = 'promo';

            if (promoCodeSection) promoCodeSection.style.display = 'none';
            if (promoSuccessMessage) promoSuccessMessage.style.display = 'block';

            if (totalPriceSpan) {
                totalPriceSpan.textContent = '0 €';
                totalPriceSpan.classList.remove('text-yellow-600');
                totalPriceSpan.classList.add('text-green-600');
            }

            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-gift mr-2"></i> Profiter de l\'offre gratuite';
                submitBtn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
                submitBtn.classList.add('bg-green-500', 'hover:bg-green-600');
            }

            console.log('Mode promo activé, access_mode =', accessModeInput ? accessModeInput.value : 'non trouvé');
        }

        function resetToPaymentMode() {
            appliedPromoCode = null;
            if (promoSuccessMessage) promoSuccessMessage.style.display = 'none';
            if (promoCodeSection) promoCodeSection.style.display = 'block';
            if (promoCodeInput) {
                promoCodeInput.disabled = false;
                promoCodeInput.value = '';
            }
            if (promoCodeMessage) promoCodeMessage.classList.add('hidden');
            if (promoCodeUsedInput) promoCodeUsedInput.value = '';
            if (accessModeInput) accessModeInput.value = 'payment';

            if (totalPriceSpan) {
                totalPriceSpan.textContent = originalPrice;
                totalPriceSpan.classList.remove('text-green-600');
                totalPriceSpan.classList.add('text-yellow-600');
            }

            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i> Confirmer et payer ' + originalPrice;
                submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
                submitBtn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
            }

            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check mr-1"></i> Appliquer';
            }

            console.log('Mode paiement réactivé');
        }

        btn.onclick = function(e) {
            e.preventDefault();
            console.log('Clic sur le bouton promo - Vérification du code');

            var code = promoCodeInput ? promoCodeInput.value.trim().toUpperCase() : '';
            console.log('Code saisi:', code);

            if (!code) {
                showMessage('Veuillez saisir un code promo', 'error');
                if (promoCodeInput) promoCodeInput.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Vérification...';
            if (promoCodeMessage) promoCodeMessage.classList.add('hidden');

            // Récupérer le token CSRF
            var token = getCsrfToken();
            console.log('Token CSRF:', token ? 'présent' : 'manquant');

            // Faire la requête fetch
            fetch('{{ route("client.elearning.check-promo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    forfait_slug: '{{ $forfait->slug }}',
                    promo_code: code
                })
            })
            .then(function(response) {
                console.log('Réponse HTTP status:', response.status);
                return response.json();
            })
            .then(function(data) {
                console.log('Réponse serveur:', data);
                if (data.valid) {
                    showMessage(data.message || 'Code promo valide ! Accès gratuit activé.', 'success');
                    applyPromoCodeUI(code);
                } else {
                    showMessage(data.message || 'Code promo invalide ou expiré', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-1"></i> Appliquer';
                }
            })
            .catch(function(error) {
                console.error('Erreur fetch:', error);
                showMessage('Erreur de connexion. Veuillez réessayer.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check mr-1"></i> Appliquer';
            });
        };

        if (promoCodeInput) {
            promoCodeInput.oninput = function() {
                if (appliedPromoCode) {
                    resetToPaymentMode();
                }
            };

            promoCodeInput.onkeydown = function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (btn) btn.click();
                }
            };
        }

        var form = document.getElementById('paymentForm');
        if (form) {
            form.onsubmit = function(e) {
                console.log('Soumission formulaire, mode:', accessModeInput ? accessModeInput.value : 'non trouvé');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (accessModeInput && accessModeInput.value === 'promo') {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Activation en cours...';
                    } else {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement en cours...';
                    }
                }
                return true;
            };
        }

        console.log('Initialisation terminée - Système de code promo prêt');
    }

    // Si le DOM est déjà chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
