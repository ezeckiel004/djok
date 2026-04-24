@extends('layouts.client')

@section('title', 'Ma salle virtuelle - DJOK PRESTIGE')
@section('page-title', 'Ma salle virtuelle')
@section('page-description', 'Accédez à vos cours et QCM')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Messages flash -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">{{ session('info') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header avec infos utilisateur -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ $acces->prenom }} {{ $acces->nom }}</h1>
                <p class="text-gray-600 mt-1">Code salle: <span class="font-mono">{{ $acces->virtual_room_code }}</span></p>

                <!-- Badge pour accès promo -->
                @if(isset($isPromoAccess) && $isPromoAccess)
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        <i class="fas fa-ticket-alt mr-1"></i> Accès offert - Code promo: {{ $acces->promo_code_used }}
                    </span>
                </div>
                @endif

                <!-- Badge pour accès payant -->
                @if(isset($isPromoAccess) && !$isPromoAccess && $acces->payment_mode === 'payment')
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        <i class="fas fa-credit-card mr-1"></i> Accès payant
                    </span>
                </div>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-sm text-gray-500">Accès jusqu'au</div>
                    <div class="font-semibold text-gray-900">{{ $acces->access_end->format('d/m/Y') }}</div>
                </div>
                <a href="{{ route('client.elearning.logout') }}"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                   onclick="return confirm('Êtes-vous sûr de vouloir quitter la salle virtuelle ?')">
                    <i class="fas fa-sign-out-alt mr-2"></i> Quitter
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $acces->cours_completed }}/{{ $acces->total_cours }}</div>
            <div class="text-sm text-gray-600">Cours complétés</div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ $acces->total_cours > 0 ? ($acces->cours_completed / $acces->total_cours) * 100 : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($acces->average_qcm_score ?? 0, 1) }}%</div>
            <div class="text-sm text-gray-600">Score moyen QCM</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ max(0, $acces->access_end->diffInDays(now())) }}</div>
            <div class="text-sm text-gray-600">Jours restants</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $acces->progression_percentage }}%</div>
            <div class="text-sm text-gray-600">Progression globale</div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $acces->progression_percentage }}%"></div>
            </div>
        </div>
    </div>

    <!-- Tabs navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <button type="button" data-tab="cours"
                class="tab-button py-3 px-1 border-b-2 font-medium text-sm transition-colors border-yellow-500 text-yellow-600">
                <i class="fas fa-book mr-2"></i> Mes cours
                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">{{ $cours->count() }}</span>
            </button>
            @if(isset($qcmsNormaux) && $qcmsNormaux->count() > 0)
            <button type="button" data-tab="qcms"
                class="tab-button py-3 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-question-circle mr-2"></i> QCM
                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">{{ $qcmsNormaux->count() }}</span>
            </button>
            @endif
            @if(isset($examensBlancs) && $examensBlancs->count() > 0)
            <button type="button" data-tab="examens"
                class="tab-button py-3 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-star mr-2"></i> Examens blancs
                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">{{ $examensBlancs->count() }}</span>
            </button>
            @endif
        </nav>
    </div>

    <!-- Tab Content: Cours -->
    <div id="tab-cours" class="tab-content">
        @if($cours->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($cours as $coursItem)
            @php
            $progression = isset($progressions[$coursItem->id]) ? $progressions[$coursItem->id] : null;
            $isCompleted = $progression && $progression->cours_completed;
            $completedAt = $progression ? $progression->cours_completed_at : null;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                <div class="relative h-32 bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-video text-white text-4xl opacity-50"></i>
                    @if($isCompleted)
                    <div class="absolute top-2 right-2 bg-green-500 text-white rounded-full p-1 shadow-lg">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ $coursItem->title }}</h3>
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="far fa-clock mr-1"></i> {{ $coursItem->duration_formatted ?? 'Durée variable' }}
                    </p>
                    <div class="flex items-center justify-between">
                        @if($isCompleted)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Terminé
                            </span>
                            @if($completedAt)
                            <span class="text-xs text-gray-400">Le {{ $completedAt->format('d/m/Y') }}</span>
                            @endif
                        @else
                            <a href="{{ route('client.elearning.cours.show', $coursItem->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-sm font-semibold rounded-lg hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-play mr-1"></i> Commencer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
            <i class="fas fa-book-open text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Aucun cours disponible</h3>
            <p class="text-gray-500 mt-2">Aucun cours n'est inclus dans votre forfait actuellement.</p>
        </div>
        @endif
    </div>

    <!-- Tab Content: QCM -->
    @if(isset($qcmsNormaux) && $qcmsNormaux->count() > 0)
    <div id="tab-qcms" class="tab-content hidden">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($qcmsNormaux as $qcm)
            @php
            $progression = $qcmsProgressions[$qcm->id] ?? null;
            $isCompleted = $progression && $progression->qcm_completed == 1;
            $score = $progression ? $progression->qcm_score : null;
            $attemptsLeft = $qcm->attempts_allowed > 0 ? max(0, $qcm->attempts_allowed - ($progression->qcm_attempts ?? 0)) : null;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle text-green-500 text-xl mr-2"></i>
                            <h3 class="font-bold text-gray-900">{{ $qcm->title }}</h3>
                        </div>
                        @if($isCompleted)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Complété
                        </span>
                        @endif
                    </div>

                    @if($qcm->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $qcm->description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-4">
                        <span><i class="fas fa-list mr-1"></i> {{ $qcm->questions_count }} questions</span>
                        <span><i class="fas fa-chart-line mr-1"></i> Score requis: {{ $qcm->passing_score }}%</span>
                        @if($attemptsLeft !== null)
                        <span><i class="fas fa-redo-alt mr-1"></i> {{ $attemptsLeft }} tentative(s) restante(s)</span>
                        @endif
                    </div>

                    @if($isCompleted && $score !== null)
                    <div class="mb-3 p-2 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Votre score</span>
                            <span class="font-bold {{ $score >= $qcm->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($score, 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="{{ $score >= $qcm->passing_score ? 'bg-green-500' : 'bg-red-500' }} h-1.5 rounded-full"
                                 style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        @if($isCompleted && $attemptsLeft === 0)
                        <span class="text-sm text-gray-400">Nombre maximum de tentatives atteint</span>
                        @elseif($isCompleted && $attemptsLeft > 0)
                        <a href="{{ route('client.elearning.qcm.show', $qcm->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                            <i class="fas fa-redo-alt mr-1"></i> Revoir le QCM
                        </a>
                        @else
                        <a href="{{ route('client.elearning.qcm.show', $qcm->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-play mr-1"></i> Commencer le QCM
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tab Content: Examens Blancs -->
    @if(isset($examensBlancs) && $examensBlancs->count() > 0)
    <div id="tab-examens" class="tab-content hidden">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($examensBlancs as $examen)
            @php
            $progression = $qcmsProgressions[$examen->id] ?? null;
            $isCompleted = $progression && $progression->qcm_completed == 1;
            $score = $progression ? $progression->qcm_score : null;
            $attemptsLeft = $examen->attempts_allowed > 0 ? max(0, $examen->attempts_allowed - ($progression->qcm_attempts ?? 0)) : null;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fas fa-star text-purple-500 text-xl mr-2"></i>
                            <h3 class="font-bold text-gray-900">{{ $examen->title }}</h3>
                        </div>
                        @if($isCompleted)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Complété
                        </span>
                        @endif
                    </div>

                    @if($examen->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $examen->description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-4">
                        <span><i class="fas fa-list mr-1"></i> {{ $examen->questions_count }} questions</span>
                        <span><i class="fas fa-chart-line mr-1"></i> Score requis: {{ $examen->passing_score }}%</span>
                        @if($attemptsLeft !== null)
                        <span><i class="fas fa-redo-alt mr-1"></i> {{ $attemptsLeft }} tentative(s) restante(s)</span>
                        @endif
                    </div>

                    @if($isCompleted && $score !== null)
                    <div class="mb-3 p-2 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Votre score</span>
                            <span class="font-bold {{ $score >= $examen->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($score, 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="{{ $score >= $examen->passing_score ? 'bg-green-500' : 'bg-red-500' }} h-1.5 rounded-full"
                                 style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        @if($isCompleted && $attemptsLeft === 0)
                        <span class="text-sm text-gray-400">Nombre maximum de tentatives atteint</span>
                        @elseif($isCompleted && $attemptsLeft > 0)
                        <a href="{{ route('client.elearning.qcm.show', $examen->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-purple-500 text-white text-sm font-semibold rounded-lg hover:bg-purple-600 transition-colors">
                            <i class="fas fa-redo-alt mr-1"></i> Revoir l'examen
                        </a>
                        @else
                        <a href="{{ route('client.elearning.qcm.show', $examen->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-purple-500 text-white text-sm font-semibold rounded-lg hover:bg-purple-600 transition-colors">
                            <i class="fas fa-play mr-1"></i> Commencer l'examen
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = {
        cours: document.getElementById('tab-cours'),
        qcms: document.getElementById('tab-qcms'),
        examens: document.getElementById('tab-examens')
    };

    function switchTab(tabName) {
        // Masquer tous les contenus
        Object.values(tabContents).forEach(content => {
            if (content) content.classList.add('hidden');
        });

        // Afficher le contenu sélectionné
        if (tabContents[tabName]) {
            tabContents[tabName].classList.remove('hidden');
        }

        // Mettre à jour le style des boutons
        tabButtons.forEach(button => {
            const buttonTab = button.getAttribute('data-tab');
            if (buttonTab === tabName) {
                button.classList.remove('border-transparent', 'text-gray-500');
                button.classList.add('border-yellow-500', 'text-yellow-600');
            } else {
                button.classList.remove('border-yellow-500', 'text-yellow-600');
                button.classList.add('border-transparent', 'text-gray-500');
            }
        });

        // Sauvegarder l'onglet actif dans localStorage
        localStorage.setItem('elearning_active_tab', tabName);
    }

    // Ajouter les écouteurs d'événements
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            if (tabName) switchTab(tabName);
        });
    });

    // Restaurer l'onglet actif depuis localStorage
    const savedTab = localStorage.getItem('elearning_active_tab');
    if (savedTab && tabContents[savedTab]) {
        switchTab(savedTab);
    }
});
</script>
@endpush

@push('styles')
<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tab-button {
    transition: all 0.2s ease;
}

.tab-button i {
    transition: transform 0.2s ease;
}

.tab-button:hover i {
    transform: translateY(-2px);
}
</style>
@endpush
@endsection
