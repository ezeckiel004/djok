@extends('layouts.main')

@section('title', __('performance.title'))

@section('content')
<div class="min-h-screen py-12 bg-gray-50">
    <div class="max-w-6xl px-4 mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-lg">
            <h1 class="mb-4 text-3xl font-bold text-center text-gray-900">{{ __('performance.main_title') }}</h1>
            <p class="mb-8 text-lg text-center text-gray-600">
                {{ __('performance.subtitle') }}
            </p>

            <!-- Introduction -->
            <div class="p-6 mb-10 rounded-lg bg-blue-50">
                <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('performance.engagement_qualite.title') }}</h2>
                @foreach (__('performance.engagement_qualite.contents') as $paragraph)
                <p class="{{ !$loop->first ? 'mt-3' : '' }} text-gray-700">
                    {{ $paragraph }}
                </p>
                @endforeach
            </div>

            <!-- INDICATEURS DE PERFORMANCE -->
            <section class="py-12" style="background: linear-gradient(135deg, #0a0a0a 0%, #111 100%); border-radius: 1rem; margin-bottom: 2rem;">
                <div class="px-4 mx-auto max-w-7xl">

                    <!-- Section des cartes détaillées par formation -->
                    <div>
                        <h3 class="mb-8 text-2xl font-bold text-center text-white">Indicateurs Qualité et Résultats</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                            <!-- Carte Formation Création Micro-entreprise -->
                            <div class="overflow-hidden transition-all duration-300 rounded-xl hover:transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(182, 146, 70, 0.15) 0%, rgba(0, 0, 0, 0.7) 100%); border: 1px solid rgba(182, 146, 70, 0.3);">
                                <div class="p-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="p-3 rounded-full" style="background: rgba(182, 146, 70, 0.2);">
                                            <i class="text-3xl fas fa-chalkboard-user" style="color: #b69246;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mb-4 text-xl font-bold text-center text-white">Formation Création Micro-entreprise</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Nombre de stagiaires formés</span>
                                            <span class="font-bold text-white">1</span>
                                        </div>
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Taux de satisfaction</span>
                                            <span class="font-bold text-white">100%</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <div class="flex justify-center gap-1">
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Formation Clientèle Privée -->
                            <div class="overflow-hidden transition-all duration-300 rounded-xl hover:transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(182, 146, 70, 0.15) 0%, rgba(0, 0, 0, 0.7) 100%); border: 1px solid rgba(182, 146, 70, 0.3);">
                                <div class="p-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="p-3 rounded-full" style="background: rgba(182, 146, 70, 0.2);">
                                            <i class="text-3xl fas fa-handshake" style="color: #b69246;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mb-4 text-xl font-bold text-center text-white">Formation Clientèle Privée</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Nombre de stagiaires formés</span>
                                            <span class="font-bold text-white">1</span>
                                        </div>
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Taux de satisfaction</span>
                                            <span class="font-bold text-white">100%</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <div class="flex justify-center gap-1">
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                            <i class="fas fa-star" style="color: #b69246;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Formation VTC -->
                            <div class="overflow-hidden transition-all duration-300 rounded-xl hover:transform hover:scale-105"
                                style="background: linear-gradient(135deg, rgba(182, 146, 70, 0.15) 0%, rgba(0, 0, 0, 0.7) 100%); border: 1px solid rgba(182, 146, 70, 0.3);">
                                <div class="p-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="p-3 rounded-full" style="background: rgba(182, 146, 70, 0.2);">
                                            <i class="text-3xl fas fa-car" style="color: #b69246;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mb-4 text-xl font-bold text-center text-white">Formation VTC</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Nombre de stagiaires</span>
                                            <span class="font-bold text-yellow-500">données à venir</span>
                                        </div>
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Taux de satisfaction</span>
                                            <span class="font-bold text-yellow-500">données à venir</span>
                                        </div>
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Taux de présentation à l'examen</span>
                                            <span class="font-bold text-yellow-500">données à venir</span>
                                        </div>
                                        <div class="flex justify-between pb-2 border-b border-gray-700">
                                            <span class="text-gray-400">Taux d'obtention de la certification</span>
                                            <span class="font-bold text-yellow-500">données à venir</span>
                                        </div>
                                    </div>
                                    {{-- <div class="mt-4 text-center">
                                        <i class="text-gray-500 fas fa-clock"></i>
                                        <span class="text-sm text-gray-500"> En cours de déploiement</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Mention mise à jour -->
                        <div class="max-w-2xl mx-auto mt-10 text-center">
                            <div class="p-4 rounded-lg" style="background: rgba(182, 146, 70, 0.05); border: 1px solid rgba(182, 146, 70, 0.15);">
                                <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-center">
                                    <i class="text-gray-400 fas fa-chart-simple"></i>
                                    <p class="text-sm text-gray-400">
                                        Données mises à jour une fois par an.
                                    </p>
                                </div>
                                <div class="flex flex-col items-center gap-2 mt-2 sm:flex-row sm:justify-center">
                                    <i class="text-gray-400 far fa-calendar-alt"></i>
                                    <p class="text-sm text-gray-400">
                                        <strong>Dernière mise à jour : décembre 2025</strong>
                                        <span class="mx-2 text-gray-600">—</span>
                                        <strong>Prochaine mise à jour : décembre 2026</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Méthodologie et explications -->
            <div class="grid grid-cols-1 gap-8 mt-12 mb-12 md:grid-cols-2">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <h3 class="mb-4 text-xl font-bold text-gray-900">
                        <i class="mr-2 {{ __('performance.methodologie.icon') }}"></i>
                        {{ __('performance.methodologie.title') }}
                    </h3>
                    <ul class="space-y-3 text-gray-700">
                        @foreach (__('performance.methodologie.items') as $item)
                        <li class="flex items-start">
                            <i class="mt-1 mr-3 text-blue-600 fas fa-check-circle"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="p-6 bg-gray-50 rounded-lg">
                    <h3 class="mb-4 text-xl font-bold text-gray-900">
                        <i class="mr-2 {{ __('performance.objectifs_qualite.icon') }}"></i>
                        {{ __('performance.objectifs_qualite.title') }}
                    </h3>
                    <ul class="space-y-3 text-gray-700">
                        @foreach (__('performance.objectifs_qualite.items') as $item)
                        <li class="flex items-start">
                            <i class="mt-1 mr-3 text-green-600 fas fa-trophy"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- BANNIÈRE QUALIOPI -->
<div class="qualiopi-banner">

    <!-- BLOC GAUCHE : image tout-en-un -->
    <div class="qualiopi-left">
        <img src="{{ asset('qualiopi3.jpeg') }}"
             alt="Qualiopi processus certifié - République Française"
             class="qualiopi-logo-img">

        <p class="certification-mention">
            La certification qualité a été délivrée au titre de la catégorie d'action
            suivante&nbsp;: <strong>actions de formation</strong>.
        </p>
    </div>

    <!-- SÉPARATEUR VERTICAL -->
    <div class="qualiopi-separator"></div>

    <!-- BLOC DROIT -->
    <div class="qualiopi-right">
        <p class="download-title">Téléchargez notre certificat</p>
        <a href="{{ asset('Agrément_Qualiopi.pdf') }}" target="_blank" class="download-btn-qualiopi">
            Certificat Qualiopi &nbsp;<i class="fas fa-download"></i>
        </a>
    </div>
</div>

            <!-- Retour à l'accueil -->
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('performance.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Style supplémentaire -->
<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .animate-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

/* Styles pour la bannière Qualiopi */
.qualiopi-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    padding: 40px 60px;
    gap: 40px;
    border-radius: 4px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}
.qualiopi-left {
    display: flex;
    flex-direction: column;
    gap: 20px;
    flex: 1;
}
.qualiopi-logo-img {
    max-width: 420px;
    width: 100%;
    height: auto;
    object-fit: contain;
    object-position: left center;
}
.certification-mention {
    font-size: 14px;
    color: #333;
    line-height: 1.65;
    max-width: 480px;
}
.qualiopi-separator {
    width: 1px;
    min-height: 130px;
    align-self: stretch;
    background: #e0e0e0;
    flex-shrink: 0;
}
.qualiopi-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    min-width: 260px;
}
.download-title {
    font-size: 18px;
    font-weight: 700;
    color: #111;
    text-align: center;
    line-height: 1.3;
}
.download-btn-qualiopi {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 2px solid #111;
    border-radius: 50px;
    padding: 12px 30px;
    font-size: 14px;
    font-weight: 700;
    color: #111;
    text-decoration: none;
    background: transparent;
    transition: background 0.25s, color 0.25s;
    white-space: nowrap;
}
.download-btn-qualiopi:hover {
    background: #111;
    color: #fff;
}

/* Responsive bannière Qualiopi */
@media (max-width: 768px) {
    .qualiopi-banner {
        flex-direction: column;
        padding: 30px 24px;
        gap: 24px;
        align-items: flex-start;
    }
    .qualiopi-logo-img {
        max-width: 100%;
    }
    .qualiopi-separator {
        width: 100%;
        min-height: 1px;
        height: 1px;
        align-self: auto;
    }
    .qualiopi-right {
        width: 100%;
        min-width: unset;
        align-items: flex-start;
    }
    .download-title { text-align: left; }
}

    @media (max-width: 768px) {
        .p-8 {
            padding: 1.5rem;
        }
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Script pour l'animation des cartes -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'apparition au scroll pour les cartes
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.grid > div').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    });
</script>
@endsection
