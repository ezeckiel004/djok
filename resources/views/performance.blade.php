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
                                <p class="text-gray-400">
                                    Des professionnels formés avec succès à nos différentes certifications VTC
                                </p>
                                <div class="mt-6">
                                    <div class="h-1 overflow-hidden rounded-full bg-gray-700">
                                        <div class="h-full transition-all duration-1000 rounded-full" style="width: 100%; background: #b69246;"></div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Objectif 2025 : 25+ formés</p>
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
                            <p class="text-lg text-gray-300">
                                <i class="mr-2 fas fa-chart-line" style="color: #b69246;"></i>
                                <strong class="text-white">+8 professionnels formés</strong> et prêts à réussir dans le secteur VTC,
                                avec un <strong class="text-white">taux de satisfaction de 100%</strong> auprès de nos apprenants.
                            </p>
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

            <!-- Certification Qualiopi -->
            <div class="p-6 mb-8 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col items-center md:flex-row">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 md:mb-0 md:mr-6">
                        <i class="text-3xl text-blue-700 fas fa-award"></i>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="mb-2 text-xl font-bold text-gray-900">{{
                            __('performance.certification_qualiopi.title') }}</h3>
                        <p class="text-gray-700">
                            {{ __('performance.certification_qualiopi.content') }}
                        </p>
                        <a href="#" class="inline-flex items-center mt-3 text-blue-700 hover:underline">
                            <i class="mr-2 {{ __('performance.certification_qualiopi.download_icon') }}"></i>
                            {{ __('performance.certification_qualiopi.download_link') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Date de mise à jour -->
            <div class="p-4 rounded-lg bg-gray-100">
                <div class="flex items-center justify-center">
                    <i class="mr-3 text-gray-600 {{ __('performance.mise_a_jour.icon') }}"></i>
                    <p class="text-sm text-gray-600">
                        <strong>{{ __('performance.mise_a_jour.last_update') }}</strong>
                        {{ __('performance.mise_a_jour.last_update_date') }} |
                        <strong>{{ __('performance.mise_a_jour.next_update') }}</strong>
                        {{ __('performance.mise_a_jour.next_update_date') }}
                    </p>
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
