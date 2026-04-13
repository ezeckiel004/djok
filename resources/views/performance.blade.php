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
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Carte Personnes Formées -->
                        <div class="relative overflow-hidden transition-all duration-500 group rounded-2xl"
                            style="background: linear-gradient(135deg, rgba(182, 146, 70, 0.1) 0%, rgba(0, 0, 0, 0.6) 100%); border: 1px solid rgba(182, 146, 70, 0.3);">
                            <div class="p-8 text-center">
                                <div class="flex items-center justify-center mb-6">
                                    <div class="relative">
                                        <div class="absolute inset-0 rounded-full animate-ping"
                                            style="background: rgba(182, 146, 70, 0.3); width: 80px; height: 80px; border-radius: 50%;"></div>
                                        <div class="relative flex items-center justify-center w-20 h-20 rounded-full"
                                            style="background: linear-gradient(135deg, #b69246 0%, #d4af37 100%);">
                                            <i class="text-3xl fas fa-users" style="color: #000;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <span class="text-6xl font-bold" style="color: #b69246; text-shadow: 0 0 20px rgba(182, 146, 70, 0.5);">
                                        <span class="counter" data-target="8">0</span>
                                    </span>
                                    <span class="text-3xl font-bold" style="color: #b69246;">+</span>
                                </div>
                                <h3 class="mb-3 text-2xl font-bold text-white">Personnes Formées</h3>

                                <div class="mt-6">
                                    <div class="h-1 overflow-hidden rounded-full bg-gray-700">
                                        <div class="h-full transition-all duration-1000 rounded-full" style="width: 100%; background: #b69246;"></div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Objectif 2026</p>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Taux de Satisfaction -->
                        <div class="relative overflow-hidden transition-all duration-500 group rounded-2xl"
                            style="background: linear-gradient(135deg, rgba(182, 146, 70, 0.1) 0%, rgba(0, 0, 0, 0.6) 100%); border: 1px solid rgba(182, 146, 70, 0.3);">
                            <div class="p-8 text-center">
                                <div class="flex items-center justify-center mb-6">
                                    <div class="relative">
                                        <div class="absolute inset-0 rounded-full animate-ping"
                                            style="background: rgba(182, 146, 70, 0.3); width: 80px; height: 80px; border-radius: 50%;"></div>
                                        <div class="relative flex items-center justify-center w-20 h-20 rounded-full"
                                            style="background: linear-gradient(135deg, #b69246 0%, #d4af37 100%);">
                                            <i class="text-3xl fas fa-star" style="color: #000;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <span class="text-6xl font-bold" style="color: #b69246; text-shadow: 0 0 20px rgba(182, 146, 70, 0.5);">
                                        <span class="counter" data-target="100">0</span>
                                    </span>
                                    <span class="text-3xl font-bold" style="color: #b69246;">%</span>
                                </div>
                                <h3 class="mb-3 text-2xl font-bold text-white">Taux de Satisfaction</h3>
                                <p class="text-gray-400">
                                    De nos apprenants, basé sur nos enquêtes de satisfaction post-formation
                                </p>
                                <div class="mt-6">
                                    <div class="flex justify-center gap-1">
                                        <i class="fas fa-star" style="color: #b69246;"></i>
                                        <i class="fas fa-star" style="color: #b69246;"></i>
                                        <i class="fas fa-star" style="color: #b69246;"></i>
                                        <i class="fas fa-star" style="color: #b69246;"></i>
                                        <i class="fas fa-star" style="color: #b69246;"></i>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Note moyenne : 5/5 sur 12 avis</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bandeau récapitulatif -->
<div class="max-w-3xl mx-auto mt-12 text-center">
    <div class="p-6 rounded-xl" style="background: rgba(182, 146, 70, 0.05); border: 1px solid rgba(182, 146, 70, 0.2);">
        <div class="space-y-3 text-center">
            <p class="text-lg text-gray-300">
                <i class="mr-2 fas fa-chart-line" style="color: #b69246;"></i>
                <strong class="text-white">Taux de présentation à l'examen :</strong>
                <span class="text-white"></span>
            </p>
            <p class="text-lg text-gray-300">
                <i class="mr-2 fas fa-certificate" style="color: #b69246;"></i>
                <strong class="text-white">Taux d'obtention de la certification :</strong>
                <span class="text-white"></span>
            </p>
            <div class="pt-2 mt-2 border-t border-gray-700">
                <p class="text-sm text-gray-500">
                    <i class="mr-1 far fa-calendar-alt"></i>
                    Prochaine mise à jour : <strong class="text-gray-400">Décembre 2026</strong>
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

            <!-- BANNIÈRE QUALIOPI HORIZONTALE FIDÈLE À L'ORIGINAL -->
            <div class="qualiopi-banner">

                <!-- BLOC GAUCHE -->
                <div class="qualiopi-left">
                    <img src="{{ asset('Qualiopi2.png') }}"
                         alt="Qualiopi processus certifié"
                         class="qualiopi-logo-img">

                    <div class="republique-row">
                        <img src="{{ asset('Marianne2.png') }}"
                             alt="Marianne République Française"
                             class="marianne-img">
                        <span class="republique-label">République Française</span>
                    </div>

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

            <!-- Date de mise à jour -->
            {{-- <div class="p-4 mt-8 rounded-lg bg-gray-100">
                <div class="flex items-center justify-center">
                    <i class="mr-3 text-gray-600 {{ __('performance.mise_a_jour.icon') }}"></i>
                    <p class="text-sm text-gray-600">
                        <strong>{{ __('performance.mise_a_jour.last_update') }}</strong>
                        {{ __('performance.mise_a_jour.last_update_date') }} |
                        <strong>{{ __('performance.mise_a_jour.next_update') }}</strong>
                        {{ __('performance.mise_a_jour.next_update_date') }}
                    </p>
                </div>
            </div> --}}

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
        gap: 14px;
        flex: 1;
    }
    .qualiopi-logo-img {
        height: 150px;
        object-fit: contain;
        object-position: left center;
    }
    .republique-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .marianne-img {
        height: 12px;
        object-fit: contain;
    }
    .republique-label {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.05em;
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
        .qualiopi-logo-img { height: 120px; }
        .marianne-img { height: 12px; }
    }

    @media (max-width: 768px) {
        .p-8 {
            padding: 1.5rem;
        }
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Script pour l'animation des compteurs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des compteurs
        const counters = document.querySelectorAll('.counter');

        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        };

        // Observer pour déclencher l'animation des compteurs
        const counterObserverOptions = {
            threshold: 0.3,
            rootMargin: '0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionCounters = entry.target.querySelectorAll('.counter');
                    sectionCounters.forEach(counter => {
                        if (counter.innerText === '0') {
                            animateCounter(counter);
                        }
                    });
                    counterObserver.unobserve(entry.target);
                }
            });
        }, counterObserverOptions);

        // Observer la section des indicateurs
        const indicatorsSection = document.querySelector('section[style*="linear-gradient"]');
        if (indicatorsSection) {
            counterObserver.observe(indicatorsSection);
        }

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

        document.querySelectorAll('.group').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    });
</script>
@endsection
