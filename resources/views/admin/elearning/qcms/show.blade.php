@extends('layouts.admin')

@section('title', 'Détails du QCM | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-6">
        <a href="{{ route('admin.elearning.qcms') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux QCM
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="text-lg font-semibold text-gray-900 break-words max-w-2xl">{{ $qcm->title }}</h2>
                <div class="flex flex-wrap gap-2">
                    @if($qcm->is_active)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 whitespace-nowrap">
                        Actif
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 whitespace-nowrap">
                        Inactif
                    </span>
                    @endif

                    @if($qcm->is_examen_blanc)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 whitespace-nowrap">
                        Examen blanc
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                        <p class="text-gray-900 break-words whitespace-pre-wrap">{{ $qcm->description ?: 'Aucune description' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Cours associé</h3>
                        <p class="text-gray-900 break-words">
                            @if($qcm->cours)
                            <a href="{{ route('admin.elearning.cours.edit', $qcm->cours->id) }}"
                                class="text-blue-600 hover:text-blue-800 break-words">
                                {{ $qcm->cours->title }}
                            </a>
                            @else
                            <span class="text-gray-500">Aucun cours associé</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Nombre de questions</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $qcm->questions_count }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Note minimale</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $qcm->passing_score }}%</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Temps limite</h3>
                            <p class="text-gray-900 break-words">
                                {{ $qcm->time_limit_minutes ? $qcm->time_limit_minutes . ' minutes' : 'Illimité' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tentatives autorisées</h3>
                            <p class="text-gray-900">{{ $qcm->attempts_allowed }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap justify-between items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Créé le</span>
                            <span class="text-sm text-gray-500">{{ $qcm->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex flex-wrap justify-between items-center gap-2 mt-2">
                            <span class="text-sm font-medium text-gray-700">Dernière modification</span>
                            <span class="text-sm text-gray-500">{{ $qcm->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Complétions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $qcm->stats['progressions_count'] ?? 0 }}</p>
                    </div>

                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Taux de réussite</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $qcm->stats['completion_rate'] ?? 0 }}%</p>
                    </div>

                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Score moyen</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $qcm->stats['average_score'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Questions ({{ count($questions) }})</h3>
                </div>

                <div class="space-y-6">
                    @forelse($questions as $index => $question)
                    <div class="border border-gray-200 rounded-lg p-6 bg-white overflow-hidden">
                        <!-- En-tête de la question -->
                        <div class="flex items-start space-x-3 mb-4 pb-4 border-b border-gray-200">
                            <div
                                class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center font-semibold flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <h4 class="text-md font-medium text-gray-900 break-words whitespace-pre-wrap flex-1">
                                {{ $question['text'] ?? 'Question sans texte' }}
                            </h4>
                        </div>

                        <!-- Réponses -->
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-3">Réponses :</h5>
                            <div class="space-y-2">
                                @php
                                    // Récupérer les réponses (supports différents formats)
                                    $answers = [];
                                    if (isset($question['options'])) {
                                        $answers = $question['options'];
                                    } elseif (isset($question['answers'])) {
                                        $answers = $question['answers'];
                                    }

                                    // Récupérer la ou les bonnes réponses
                                    $correctAnswers = [];
                                    if (isset($question['correct_answer'])) {
                                        $correctAnswers = [$question['correct_answer']];
                                    } elseif (isset($question['correct_answers'])) {
                                        $correctAnswers = is_array($question['correct_answers']) ? $question['correct_answers'] : [$question['correct_answers']];
                                    } elseif (isset($question['correct'])) {
                                        $correctAnswers = is_array($question['correct']) ? $question['correct'] : [$question['correct']];
                                    }

                                    // Si le QCM permet plusieurs réponses correctes
                                    $allowMultiple = $qcm->allow_multiple_correct ?? false;
                                @endphp

                                @foreach($answers as $key => $answer)
                                @php
                                    $isCorrect = in_array($key, $correctAnswers);
                                @endphp
                                <div
                                    class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg
                                                    {{ $isCorrect ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                    <div class="flex items-start space-x-2 flex-shrink-0">
                                        @if($isCorrect)
                                        <span class="text-green-600 mt-0.5">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        @endif
                                        <span class="text-sm font-medium text-gray-700 w-6">{{ $key }}.</span>
                                    </div>
                                    <span
                                        class="text-gray-900 break-words whitespace-pre-wrap flex-1 {{ $isCorrect ? 'font-semibold' : '' }}">
                                        {{ $answer }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Explication -->
                        @if(!empty($question['explanation']))
                        <div class="mt-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Explication :</h5>
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <p class="text-sm text-gray-700 break-words whitespace-pre-wrap">{{ $question['explanation'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-question-circle text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucune question dans ce QCM</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.elearning.qcms') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                    Retour à la liste
                </a>
                <a href="{{ route('admin.elearning.qcms.edit', $qcm->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Gestion des textes longs */
    .break-words {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .whitespace-pre-wrap {
        white-space: pre-wrap;
    }

    /* Responsive pour les petits écrans */
    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .p-6 {
            padding: 1rem;
        }

        .space-y-6 > :not([hidden]) ~ :not([hidden]) {
            margin-top: 1rem;
        }
    }

    /* Amélioration de la lisibilité sur mobile */
    @media (max-width: 480px) {
        .text-lg {
            font-size: 1rem;
        }

        .text-2xl {
            font-size: 1.25rem;
        }

        .flex.items-start.space-x-3 {
            gap: 0.5rem;
        }

        .w-6 {
            width: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page détails du QCM chargée');

        // Optionnel : ajouter des tooltips pour les textes longs
        const longTexts = document.querySelectorAll('.break-words');
        longTexts.forEach(el => {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent);
            }
        });
    });
</script>
@endpush
