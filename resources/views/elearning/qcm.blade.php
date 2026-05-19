@extends('layouts.main')

@section('title', $qcm->title . ' - DJOK PRESTIGE')

@section('content')
<!-- Header -->
<div class="bg-black border-b border-gray-800">
    <div class="container px-4 mx-auto md:px-6">
        <div class="flex flex-col py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <a href="{{ route('elearning.virtual-room') }}" class="flex items-center">
                    <img src="{{ asset('DP2.webp') }}" alt="DJOK PRESTIGE" class="h-10">
                    <div class="ml-4">
                        <h1 class="text-lg font-bold text-white">QCM</h1>
                        <p class="text-sm text-gray-400">{{ $qcm->title }}</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden text-right md:block">
                    <div class="text-sm font-medium text-white">{{ $acces->prenom }} {{ $acces->nom }}</div>
                    <div class="text-xs text-gray-400">Salle: {{ $acces->virtual_room_code }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message d'alerte si déjà complété -->
@php
$progression = \App\Models\ElearningProgression::where('acces_id', $acces->id)
    ->where('qcm_id', $qcm->id)
    ->first();

// ✅ Utiliser les questions sélectionnées aléatoirement
$questions = $qcm->selected_questions ?? ($qcm->questions_data['questions'] ?? []);
$questionsCount = $qcm->selected_questions_count ?? count($questions);
$totalAvailable = $qcm->total_available_questions ?? $questionsCount;
$isRandomized = $qcm->is_randomized ?? false;
@endphp

@if($progression && $progression->qcm_completed)
<div class="container px-4 mx-auto mt-4 md:px-6">
    <div class="p-4 rounded-lg" style="background: #1e3a8a; border: 1px solid #2563eb;">
        <div class="flex items-center">
            <i class="mr-3 fas fa-info-circle" style="color: #60a5fa;"></i>
            <div>
                <h4 class="mb-1 font-bold text-white">Information importante</h4>
                <p class="text-blue-100">
                    Vous avez déjà complété ce QCM. Votre meilleur score est de
                    <strong>{{ $progression->qcm_score }}%</strong>
                    ({{ $progression->qcm_attempts }}/{{ $qcm->attempts_allowed == 0 ? '∞' : $qcm->attempts_allowed }}
                    tentatives).
                    @if($qcm->attempts_allowed > 0 && $progression->qcm_attempts >= $qcm->attempts_allowed)
                    <br><strong class="text-red-400">⚠️ Vous avez atteint le nombre maximum de tentatives.</strong>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- QCM Content -->
<div class="py-8" style="background: #000; min-height: calc(100vh - 200px);">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Timer (si limité) -->
            @if($qcm->time_limit_minutes && !($progression && $progression->qcm_completed))
            <div class="mb-6">
                <div class="flex items-center justify-center">
                    <div class="p-4 text-center rounded-lg" style="background: #111; border: 1px solid #333;">
                        <div class="mb-1 text-sm font-medium text-gray-400">Temps restant</div>
                        <div id="timer" class="text-3xl font-bold" style="color: #b89449;">
                            {{ sprintf('%02d:00', $qcm->time_limit_minutes) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Container du QCM mode jeu -->
            <div id="qcmApp" class="rounded-lg" style="background: #111; border: 1px solid #333;">
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i>
                    <p class="text-gray-400">Chargement du QCM...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup de confirmation personnalisé pour rechargement -->
<div id="reloadConfirmModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md rounded-lg" style="background: #111; border: 1px solid #333;">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <i class="mr-3 text-2xl fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                    <h3 class="text-xl font-bold text-white">Attention !</h3>
                </div>
                <div class="mb-6">
                    <p class="mb-3 text-gray-300">
                        Vous avez déjà commencé à répondre à ce QCM. Si vous rechargez la page, vous perdrez toutes vos réponses non enregistrées.
                    </p>
                    <p class="text-gray-300">
                        <strong>Que souhaitez-vous faire ?</strong>
                    </p>
                </div>
                <div class="flex justify-end pt-4 space-x-3 border-t border-gray-800">
                    <button type="button" id="cancelReloadBtn" class="px-6 py-2 font-medium transition-colors rounded" style="background: #333; color: white;">
                        Annuler
                    </button>
                    <button type="button" id="confirmReloadBtn" class="px-6 py-2 font-medium transition-colors rounded" style="background: #dc2626; color: white;">
                        <i class="mr-2 fas fa-external-link-alt"></i> Quitter le QCM
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        // ==============================================
        // DONNÉES PHP
        // ==============================================
        const RAW_QUESTIONS   = @json($questions);
        const ALLOW_MULTIPLE  = {{ $qcm->allow_multiple_correct ? 'true' : 'false' }};
        const QCM_ID          = {{ $qcm->id }};
        const PASSING_SCORE   = {{ $qcm->passing_score }};
        const HAS_TIME_LIMIT  = {{ $qcm->time_limit_minutes ? 'true' : 'false' }};
        const TIME_LIMIT_SECONDS = {{ $qcm->time_limit_minutes ? $qcm->time_limit_minutes * 60 : 0 }};
        const IS_ALREADY_COMPLETED = {{ $progression && $progression->qcm_completed ? 'true' : 'false' }};

        // ==============================================
        // CONFIGURATION
        // ==============================================
        const MAX_QUESTIONS_PER_SESSION = 25;
        const TOTAL_AVAILABLE = Array.isArray(RAW_QUESTIONS) ? RAW_QUESTIONS.length : Object.values(RAW_QUESTIONS).length;

        // Variables globales
        let activeQuestions = [];
        let totalQuestions = 0;
        let userAnswers = {};
        let currentQuestionIndex = 0;
        let container = null;
        let isWaitingForNext = false;
        let timerInterval = null;
        let timeLeft = TIME_LIMIT_SECONDS;
        let formSubmitted = false;

        // État question courante
        let currentQuestion = null;
        let showExplanation = false;
        let selectedAnswerValues = [];

        // ==============================================
        // REDIRECTION SI DÉJÀ COMPLÉTÉ
        // ==============================================
        if (IS_ALREADY_COMPLETED) {
            container = document.getElementById('qcmApp');
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-white mb-2">QCM déjà complété</h3>
                        <p class="text-gray-400 mb-6">Vous avez déjà terminé ce QCM.</p>
                        <a href="{{ route('elearning.virtual-room') }}" class="inline-block px-6 py-3 font-medium rounded-lg" style="background: #b89449; color: black;">
                            <i class="fas fa-arrow-left mr-2"></i> Retour à la salle virtuelle
                        </a>
                    </div>
                `;
            }
            throw new Error('QCM déjà complété');
        }

        // ==============================================
        // MÉLANGE ALÉATOIRE (FISHER-YATES)
        // ==============================================
        function fisherYates(arr) {
            const a = [...arr];
            for (let i = a.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [a[i], a[j]] = [a[j], a[i]];
            }
            return a;
        }

        // ==============================================
        // SÉLECTION ALEATOIRE DE 25 QUESTIONS PARMI TOUTES
        // ==============================================
        function selectRandomQuestions(allQuestions, maxCount) {
            const shuffled = fisherYates(allQuestions);
            return shuffled.slice(0, maxCount);
        }

        // ==============================================
        // NORMALISATION DES QUESTIONS
        // ==============================================
        function normalizeQuestions() {
            const raw = Array.isArray(RAW_QUESTIONS) ? RAW_QUESTIONS : Object.values(RAW_QUESTIONS);
            return raw.map((q, idx) => {
                let correctAnswerValues = [];
                if (ALLOW_MULTIPLE && q.correct_answers) {
                    correctAnswerValues = q.correct_answers.map(letter => q.answers[letter]);
                } else if (q.correct_answer) {
                    correctAnswerValues = [q.answers[q.correct_answer]];
                }

                return {
                    id: q.id || idx,
                    text: q.text || 'Question sans texte',
                    originalAnswers: { ...(q.answers || {}) },
                    correctAnswerValues: correctAnswerValues,
                    explanation: q.explanation || 'Aucune explication disponible.'
                };
            });
        }

        // ==============================================
        // MÉLANGE DES RÉPONSES D'UNE QUESTION
        // ==============================================
        function shuffleAnswersForQuestion(question) {
            const entries = Object.entries(question.originalAnswers);
            const shuffledEntries = fisherYates(entries);

            const shuffledAnswers = {};
            const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

            shuffledEntries.forEach(([originalLetter, text], index) => {
                shuffledAnswers[letters[index]] = text;
            });

            return {
                ...question,
                shuffledAnswers: shuffledAnswers
            };
        }

        // ==============================================
        // VÉRIFIER SI UNE RÉPONSE EST CORRECTE
        // ==============================================
        function isAnswerCorrect(question, selectedTexts) {
            if (!selectedTexts || (Array.isArray(selectedTexts) && selectedTexts.length === 0)) {
                return false;
            }

            const correctValues = question.correctAnswerValues;

            if (ALLOW_MULTIPLE) {
                const selectedSet = new Set(selectedTexts);
                const correctSet = new Set(correctValues);

                if (selectedSet.size !== correctSet.size) return false;
                for (let val of selectedSet) {
                    if (!correctSet.has(val)) return false;
                }
                return true;
            } else {
                return selectedTexts === correctValues[0];
            }
        }

        // ==============================================
        // TIMER
        // ==============================================
        function startTimer() {
            if (!HAS_TIME_LIMIT) return;

            const timerElement = document.getElementById('timer');
            if (!timerElement) return;

            function updateTimer() {
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    if (!formSubmitted) {
                        alert('⏰ Temps écoulé ! Soumission automatique...');
                        submitQcm();
                    }
                    return;
                }

                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timerElement.textContent = timeString;

                if (minutes < 1) {
                    timerElement.style.color = '#ef4444';
                } else if (minutes < 5) {
                    timerElement.style.color = '#f59e0b';
                }
            }

            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        }

        // ==============================================
        // INITIALISATION
        // ==============================================
        function init() {
            console.log('=== INIT QCM Mode Jeu ===');
            console.log('Questions disponibles:', TOTAL_AVAILABLE);
            console.log('Mode aléatoire:', TOTAL_AVAILABLE > MAX_QUESTIONS_PER_SESSION);

            const normalized = normalizeQuestions();
            const randomlySelected = selectRandomQuestions(normalized, MAX_QUESTIONS_PER_SESSION);

            activeQuestions = randomlySelected.map(q => shuffleAnswersForQuestion(q));
            totalQuestions = activeQuestions.length;

            for (let i = 0; i < totalQuestions; i++) {
                userAnswers[i] = ALLOW_MULTIPLE ? [] : null;
            }

            container = document.getElementById('qcmApp');
            startTimer();
            showQuestion(0);
        }

        // ==============================================
        // AFFICHER LA QUESTION COURANTE
        // ==============================================
        function showQuestion(globalIndex) {
            if (isWaitingForNext) return;
            if (globalIndex >= totalQuestions) {
                submitQcm();
                return;
            }

            currentQuestionIndex = globalIndex;
            currentQuestion = activeQuestions[globalIndex];
            showExplanation = false;
            selectedAnswerValues = ALLOW_MULTIPLE ? [] : null;

            renderCurrentQuestion();
        }

        // ==============================================
        // AFFICHAGE HTML
        // ==============================================
        function renderCurrentQuestion() {
            const q = currentQuestion;
            const globalIdx = currentQuestionIndex;
            const progressPct = Math.round((globalIdx / totalQuestions) * 100);
            const isLastQuestion = (globalIdx === totalQuestions - 1);
            const answers = q.shuffledAnswers;

            let answersHtml = '';
            for (let [letter, text] of Object.entries(answers)) {
                const isChecked = !showExplanation && selectedAnswerValues && (
                    ALLOW_MULTIPLE
                        ? selectedAnswerValues.includes(text)
                        : selectedAnswerValues === text
                );
                const inputType = ALLOW_MULTIPLE ? 'checkbox' : 'radio';
                const disabled = showExplanation ? 'disabled' : '';

                answersHtml += `
                    <label class="flex items-start p-3 border border-gray-700 rounded-lg hover:bg-gray-800 cursor-pointer mb-2 transition-all duration-200 ${showExplanation ? 'opacity-75' : ''}">
                        <input type="${inputType}" name="answer" value="${escapeHtml(text)}" class="answer-input mt-1 mr-3 flex-shrink-0" ${isChecked ? 'checked' : ''} ${disabled}>
                        <div>
                            <span class="font-bold text-yellow-500">${escapeHtml(letter)}.</span>
                            <span class="ml-2 text-gray-300">${escapeHtml(text)}</span>
                        </div>
                    </label>
                `;
            }

            let explanationHtml = '';
            if (showExplanation) {
                const isCorrect = isAnswerCorrect(q, selectedAnswerValues);
                const userAnswerDisplay = formatUserAnswerDisplay(selectedAnswerValues);
                const correctAnswerDisplay = formatCorrectAnswerDisplay(q.correctAnswerValues);

                explanationHtml = `
                    <div class="mt-6 p-4 rounded-lg ${isCorrect ? 'bg-green-900/30 border border-green-700' : 'bg-red-900/30 border border-red-700'} animate-fadeIn">
                        <div class="flex items-start">
                            <i class="fas ${isCorrect ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500'} text-xl mr-3 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="font-bold ${isCorrect ? 'text-green-400' : 'text-red-400'} mb-2">
                                    ${isCorrect ? '✓ Bonne réponse !' : '✗ Mauvaise réponse'}
                                </h4>
                                <div class="text-sm text-gray-300 mb-2">
                                    <p><strong>Votre réponse :</strong> ${userAnswerDisplay}</p>
                                    <p><strong>Réponse correcte :</strong> ${correctAnswerDisplay}</p>
                                </div>
                                <div class="text-gray-400 border-t pt-2 mt-2 ${isCorrect ? 'border-green-700' : 'border-red-700'}">
                                    <p class="italic">📖 ${escapeHtml(q.explanation)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            const answeredCount = Object.values(userAnswers).filter(a => {
                if (a === null) return false;
                if (Array.isArray(a)) return a.length > 0;
                return true;
            }).length;

            // Info sélection aléatoire (si applicable)
            const randomInfo = (TOTAL_AVAILABLE > MAX_QUESTIONS_PER_SESSION) ? `
                <div class="mb-4 text-xs text-yellow-500">
                    <i class="fas fa-random mr-1"></i> ${totalQuestions} questions sélectionnées aléatoirement
                </div>
            ` : '';

            const html = `
                <div id="questionCard" class="p-6 transition-all duration-300" style="opacity:0; transform:translateY(20px);">
                    <!-- Barre de progression -->
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Question ${globalIdx + 1} / ${totalQuestions}</span>
                            <span>${progressPct}% complété</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-500" style="width:${progressPct}%"></div>
                        </div>
                    </div>

                    ${randomInfo}

                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-6 gap-4">
                        <div class="flex items-start flex-1">
                            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center mr-3 flex-shrink-0">
                                <span class="font-bold text-black text-sm">${globalIdx + 1}</span>
                            </div>
                            <h2 class="text-lg font-bold text-white leading-snug">${escapeHtml(q.text)}</h2>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs text-gray-500 mb-1">Progression</div>
                            <div class="text-lg font-mono font-bold text-green-400">${answeredCount}/${totalQuestions}</div>
                        </div>
                    </div>

                    <!-- Réponses -->
                    <div class="space-y-2 mb-6">
                        ${answersHtml}
                    </div>

                    ${ALLOW_MULTIPLE && !showExplanation ? '<p class="text-sm text-blue-400 mb-4"><i class="fas fa-info-circle mr-1"></i>Vous pouvez sélectionner plusieurs réponses.</p>' : ''}

                    <!-- Explication -->
                    ${explanationHtml}

                    <!-- Boutons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-800 mt-4">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            <span>${answeredCount}</span> répondue(s) sur ${totalQuestions}
                        </div>
                        <button id="actionBtn" class="px-6 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 transition-colors font-medium shadow-md">
                            ${!showExplanation ? '<i class="fas fa-check mr-2"></i>Valider ma réponse' : (isLastQuestion ? '<i class="fas fa-flag-checkered mr-2"></i>Terminer le QCM' : '<i class="fas fa-arrow-right mr-2"></i>Question suivante')}
                        </button>
                    </div>
                </div>`;

            container.innerHTML = html;

            setTimeout(() => {
                const card = document.getElementById('questionCard');
                if (card) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            }, 50);

            attachEvents();
        }

        // ==============================================
        // ATTACHER LES ÉVÉNEMENTS
        // ==============================================
        function attachEvents() {
            if (!showExplanation) {
                document.querySelectorAll('.answer-input').forEach(input => {
                    input.addEventListener('change', () => {
                        const value = input.value;
                        if (ALLOW_MULTIPLE) {
                            if (input.checked) {
                                if (!selectedAnswerValues.includes(value)) {
                                    selectedAnswerValues.push(value);
                                }
                            } else {
                                selectedAnswerValues = selectedAnswerValues.filter(v => v !== value);
                            }
                        } else {
                            if (input.checked) {
                                selectedAnswerValues = value;
                            }
                        }
                    });
                });
            }

            const actionBtn = document.getElementById('actionBtn');
            if (actionBtn) {
                actionBtn.addEventListener('click', () => {
                    if (!showExplanation) {
                        validateAndShowExplanation();
                    } else {
                        moveToNextQuestion();
                    }
                });
            }
        }

        // ==============================================
        // VALIDER ET AFFICHER L'EXPLICATION
        // ==============================================
        function validateAndShowExplanation() {
            if (ALLOW_MULTIPLE) {
                if (!selectedAnswerValues || selectedAnswerValues.length === 0) {
                    alert('Veuillez sélectionner au moins une réponse.');
                    return;
                }
            } else {
                if (!selectedAnswerValues) {
                    alert('Veuillez sélectionner une réponse.');
                    return;
                }
            }

            userAnswers[currentQuestionIndex] = ALLOW_MULTIPLE ? [...selectedAnswerValues] : selectedAnswerValues;
            showExplanation = true;
            renderCurrentQuestion();
        }

        // ==============================================
        // PASSER À LA QUESTION SUIVANTE
        // ==============================================
        function moveToNextQuestion() {
            if (isWaitingForNext) return;
            isWaitingForNext = true;

            const card = document.getElementById('questionCard');
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    isWaitingForNext = false;
                    showQuestion(currentQuestionIndex + 1);
                }, 300);
            } else {
                isWaitingForNext = false;
                showQuestion(currentQuestionIndex + 1);
            }
        }

        // ==============================================
        // FORMATAGE AFFICHAGE
        // ==============================================
        function formatUserAnswerDisplay(answerValues) {
            if (!answerValues) return 'Aucune réponse';
            if (ALLOW_MULTIPLE) {
                if (answerValues.length === 0) return 'Aucune réponse sélectionnée';
                return answerValues.join(', ');
            }
            return answerValues;
        }

        function formatCorrectAnswerDisplay(correctValues) {
            if (!correctValues || correctValues.length === 0) return 'Aucune';
            return correctValues.join(', ');
        }

        // ==============================================
        // SOUMISSION AU SERVEUR
        // ==============================================
        async function submitQcm() {
            if (isWaitingForNext) return;
            if (formSubmitted) return;

            formSubmitted = true;
            isWaitingForNext = true;

            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }

            const formattedAnswers = {};
            for (let i = 0; i < activeQuestions.length; i++) {
                const q = activeQuestions[i];
                const originalId = q.id;
                let answer = userAnswers[i];
                formattedAnswers[originalId] = answer;
            }

            console.log('📤 Envoi de', Object.keys(formattedAnswers).length, 'réponses');

            container.innerHTML = `<div class="text-center py-12"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i><p class="text-gray-400">Calcul de votre score...</p></div>`;

            try {
                const csrfToken = document.querySelector('input[name="_token"]')?.value;
                const response = await fetch("{{ route('elearning.qcm.submit', $qcm->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ answers: formattedAnswers })
                });

                const data = await response.json();
                if (data.success) {
                    showResults(data);
                } else {
                    alert('Erreur: ' + (data.error || 'Erreur lors de la soumission'));
                    window.location.reload();
                }
            } catch (error) {
                console.error(error);
                alert('Erreur réseau. Veuillez réessayer.');
                window.location.reload();
            }
        }

        // ==============================================
        // AFFICHAGE DES RÉSULTATS
        // ==============================================
        function showResults(data) {
            const score = Math.round(data.score);
            const passed = score >= PASSING_SCORE;
            const correctCount = data.details ? data.details.filter(d => d.correct === true).length : 0;

            let resultHtml = `
                <div class="text-center mb-6 p-6">
                    <i class="fas ${passed ? 'fa-trophy text-yellow-500' : 'fa-book-open text-red-500'} text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold text-white">Résultats du QCM</h2>
                    <p class="text-gray-400">${escapeHtml('{{ $qcm->title }}')}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 px-6">
                    <div class="p-4 text-center rounded-lg bg-gray-800">
                        <div class="text-3xl font-bold text-yellow-500">${score}%</div>
                        <div class="text-sm text-gray-400">Votre score</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-800">
                        <div class="text-3xl font-bold text-blue-400">${PASSING_SCORE}%</div>
                        <div class="text-sm text-gray-400">Score requis</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-800">
                        <div class="text-3xl font-bold text-green-400">${correctCount}</div>
                        <div class="text-sm text-gray-400">Bonnes réponses</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-800">
                        <div class="text-3xl font-bold text-gray-300">${totalQuestions}</div>
                        <div class="text-sm text-gray-400">Questions posées</div>
                    </div>
                </div>

                <div class="p-4 rounded-lg mb-6 mx-6 ${passed ? 'bg-green-900/50 border border-green-700' : 'bg-red-900/50 border border-red-700'}">
                    <div class="flex items-center">
                        <i class="fas ${passed ? 'fa-trophy text-green-400' : 'fa-exclamation-triangle text-red-400'} text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold ${passed ? 'text-green-400' : 'text-red-400'}">${passed ? 'Félicitations !' : 'Non réussi'}</h3>
                            <p class="${passed ? 'text-green-300' : 'text-red-300'}">${passed ? `Excellent travail ! Vous avez réussi avec ${score}%` : `Vous avez obtenu ${score}%. Score requis: ${PASSING_SCORE}%`}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-6 pt-0">
                    <a href="{{ route('elearning.qcm.show', $qcm->id) }}" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-redo-alt mr-2"></i> Recommencer
                    </a>
                    <a href="{{ route('elearning.virtual-room') }}" class="px-6 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-home mr-2"></i> Retour à la salle
                    </a>
                </div>
            `;

            container.innerHTML = resultHtml;
        }

        // ==============================================
        // GESTIONNAIRE DE RECHARGEMENT
        // ==============================================
        let reloadConfirmed = false;

        function showReloadConfirm() {
            if (hasAnswers() && !formSubmitted && !reloadConfirmed) {
                const modal = document.getElementById('reloadConfirmModal');
                if (modal) modal.classList.remove('hidden');
                return false;
            }
            return true;
        }

        function hasAnswers() {
            return Object.values(userAnswers).some(a => {
                if (a === null) return false;
                if (Array.isArray(a)) return a.length > 0;
                return true;
            });
        }

        document.getElementById('cancelReloadBtn')?.addEventListener('click', () => {
            const modal = document.getElementById('reloadConfirmModal');
            if (modal) modal.classList.add('hidden');
        });

        document.getElementById('confirmReloadBtn')?.addEventListener('click', () => {
            reloadConfirmed = true;
            const modal = document.getElementById('reloadConfirmModal');
            if (modal) modal.classList.add('hidden');
            window.location.href = '{{ route("elearning.virtual-room") }}';
        });

        window.addEventListener('beforeunload', function(e) {
            if (hasAnswers() && !formSubmitted && !reloadConfirmed) {
                e.preventDefault();
                e.returnValue = 'Vous avez des réponses non enregistrées. Êtes-vous sûr de vouloir quitter ?';
                return e.returnValue;
            }
        });

        // ==============================================
        // ÉCHAPPEMENT HTML
        // ==============================================
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
        }

        // ==============================================
        // DÉMARRAGE
        // ==============================================
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }

    .answer-input {
        accent-color: #b89449;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .answer-input:checked + div {
        background-color: rgba(184, 148, 73, 0.1);
    }

    #actionBtn {
        transition: all 0.2s ease;
    }

    #actionBtn:hover {
        transform: scale(1.02);
    }
</style>
@endsection
