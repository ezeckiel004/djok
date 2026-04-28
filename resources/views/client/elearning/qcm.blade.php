@extends('layouts.client')

@section('title', $qcm->title . ' - DJOK PRESTIGE')
@section('page-title', $qcm->title)
@section('page-description', 'QCM e-learning - Mode jeu')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('client.elearning.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour à ma salle
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 break-words">{{ $qcm->title }}</h1>
            @if($qcm->description)
            <p class="text-gray-600 mt-2 break-words">{{ $qcm->description }}</p>
            @endif
            <div class="flex flex-wrap gap-3 mt-3">
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                    <i class="fas fa-list-ol mr-1"></i>
                    {{ count($questions) }} questions disponibles
                </span>
                @if($qcm->allow_multiple_correct)
                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                    <i class="fas fa-check-double mr-1"></i> Réponses multiples
                </span>
                @endif
                @if($qcm->is_examen_blanc)
                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-star mr-1"></i> Examen blanc
                </span>
                @endif
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-random mr-1"></i>
                    25 questions aléatoires / {{ count($questions) }} disponibles
                </span>
            </div>
        </div>

        <div id="qcmApp" class="p-6">
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                <p class="mt-3 text-gray-500">Chargement du QCM...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // ==============================================
        // DONNÉES PHP
        // ==============================================
        const RAW_QUESTIONS   = @json($questions);
        const ALLOW_MULTIPLE  = {{ $qcm->allow_multiple_correct ? 'true' : 'false' }};
        const QCM_ID          = {{ $qcm->id }};
        const PASSING_SCORE   = {{ $qcm->passing_score }};

        // ==============================================
        // CONFIGURATION
        // ==============================================
        const MAX_QUESTIONS_PER_SESSION = 25;  // ✅ Maximum 25 questions par session
        const TOTAL_AVAILABLE = Array.isArray(RAW_QUESTIONS) ? RAW_QUESTIONS.length : Object.values(RAW_QUESTIONS).length;

        // Variables globales
        let activeQuestions = [];      // Les 25 questions sélectionnées aléatoirement
        let totalQuestions = 0;
        let userAnswers = {};          // Stocke les réponses utilisateur par index
        let currentQuestionIndex = 0;
        let container = null;
        let isWaitingForNext = false;

        // État question courante
        let currentQuestion = null;
        let showExplanation = false;
        let selectedAnswerValues = [];

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
        // ✅ SÉLECTION ALEATOIRE DE 25 QUESTIONS PARMI TOUTES
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
                // Déterminer les réponses correctes (en VALEURS TEXTE)
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
        // ✅ MÉLANGE DES RÉPONSES D'UNE QUESTION (A/B/C/D)
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
        // ✅ INITIALISATION AVEC SÉLECTION ALEATOIRE DE 25 QUESTIONS
        // ==============================================
        function init() {
            console.log('=== INIT QCM ===');
            console.log('Questions disponibles:', TOTAL_AVAILABLE);
            console.log('Questions à utiliser (max):', MAX_QUESTIONS_PER_SESSION);

            // 1. Normaliser toutes les questions
            const normalized = normalizeQuestions();

            // 2. ✅ Sélectionner aléatoirement 25 questions (ou moins si total < 25)
            const randomlySelected = selectRandomQuestions(normalized, MAX_QUESTIONS_PER_SESSION);
            console.log('✅ Questions sélectionnées:', randomlySelected.length);

            // 3. Pour chaque question sélectionnée, mélanger SES réponses
            activeQuestions = randomlySelected.map(q => shuffleAnswersForQuestion(q));
            totalQuestions = activeQuestions.length;

            console.log('📝 Total questions à afficher:', totalQuestions);

            // 4. Initialiser les réponses utilisateur
            for (let i = 0; i < totalQuestions; i++) {
                userAnswers[i] = ALLOW_MULTIPLE ? [] : null;
            }

            container = document.getElementById('qcmApp');
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

            // Construire les réponses HTML
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
                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-yellow-50 cursor-pointer mb-2 transition-all duration-200 ${showExplanation ? 'opacity-75' : ''}">
                        <input type="${inputType}" name="answer" value="${escapeHtml(text)}" class="answer-input mt-1 mr-3 flex-shrink-0" ${isChecked ? 'checked' : ''} ${disabled}>
                        <div>
                            <span class="font-bold text-yellow-600">${escapeHtml(letter)}.</span>
                            <span class="ml-2 text-gray-700">${escapeHtml(text)}</span>
                        </div>
                    </label>
                `;
            }

            // Affichage de l'explication après validation
            let explanationHtml = '';
            if (showExplanation) {
                const isCorrect = isAnswerCorrect(q, selectedAnswerValues);
                const userAnswerDisplay = formatUserAnswerDisplay(selectedAnswerValues);
                const correctAnswerDisplay = formatCorrectAnswerDisplay(q.correctAnswerValues);

                explanationHtml = `
                    <div class="mt-6 p-4 rounded-lg ${isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'} animate-fadeIn">
                        <div class="flex items-start">
                            <i class="fas ${isCorrect ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600'} text-xl mr-3 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="font-bold ${isCorrect ? 'text-green-800' : 'text-red-800'} mb-2">
                                    ${isCorrect ? '✓ Bonne réponse !' : '✗ Mauvaise réponse'}
                                </h4>
                                <div class="text-sm text-gray-700 mb-2">
                                    <p><strong>Votre réponse :</strong> ${userAnswerDisplay}</p>
                                    <p><strong>Réponse correcte :</strong> ${correctAnswerDisplay}</p>
                                </div>
                                <div class="text-gray-600 border-t pt-2 mt-2 ${isCorrect ? 'border-green-200' : 'border-red-200'}">
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

            const html = `
                <div id="questionCard" class="transition-all duration-300" style="opacity:0; transform:translateY(20px);">
                    <!-- Barre de progression -->
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Question ${globalIdx + 1} / ${totalQuestions}</span>
                            <span>${progressPct}% complété</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500" style="width:${progressPct}%"></div>
                        </div>
                    </div>

                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-6 gap-4">
                        <div class="flex items-start flex-1">
                            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center mr-3 flex-shrink-0">
                                <span class="font-bold text-white text-sm">${globalIdx + 1}</span>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 leading-snug">${escapeHtml(q.text)}</h2>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs text-gray-500 mb-1">Progression</div>
                            <div class="text-lg font-mono font-bold text-green-600">${answeredCount}/${totalQuestions}</div>
                        </div>
                    </div>

                    <!-- Réponses -->
                    <div class="space-y-2 mb-6">
                        ${answersHtml}
                    </div>

                    ${ALLOW_MULTIPLE && !showExplanation ? '<p class="text-sm text-blue-600 mb-4"><i class="fas fa-info-circle mr-1"></i>Vous pouvez sélectionner plusieurs réponses.</p>' : ''}

                    <!-- Explication -->
                    ${explanationHtml}

                    <!-- Boutons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-4">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            <span>${answeredCount}</span> répondue(s) sur ${totalQuestions}
                        </div>
                        <button id="actionBtn" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium shadow-md">
                            ${!showExplanation ? '<i class="fas fa-check mr-2"></i>Valider ma réponse' : (isLastQuestion ? '<i class="fas fa-flag-checkered mr-2"></i>Terminer le QCM' : '<i class="fas fa-arrow-right mr-2"></i>Question suivante')}
                        </button>
                    </div>
                </div>`;

            container.innerHTML = html;

            // Animation d'entrée
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

            // Sauvegarder les VALEURS sélectionnées
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
        // ✅ SOUMISSION AU SERVEUR (25 questions seulement)
        // ==============================================
        async function submitQcm() {
            if (isWaitingForNext) return;
            isWaitingForNext = true;

            // Formater les réponses avec les IDs d'origine des questions sélectionnées
            const formattedAnswers = {};
            for (let i = 0; i < activeQuestions.length; i++) {
                const q = activeQuestions[i];
                const originalId = q.id;
                let answer = userAnswers[i];
                formattedAnswers[originalId] = answer;
            }

            console.log('📤 Envoi de', Object.keys(formattedAnswers).length, 'réponses au serveur');

            container.innerHTML = `<div class="text-center py-12"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i><p>Calcul de votre score...</p></div>`;

            try {
                const csrfToken = document.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch("{{ route('client.elearning.qcm.submit', $qcm->id) }}", {
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
                <div class="text-center mb-6">
                    <i class="fas ${passed ? 'fa-trophy text-yellow-500' : 'fa-book-open text-red-500'} text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">Résultats du QCM</h2>
                    <p class="text-gray-600">{{ $qcm->title }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-3xl font-bold text-yellow-600">${score}%</div>
                        <div class="text-sm text-gray-600">Votre score</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-3xl font-bold text-blue-600">${PASSING_SCORE}%</div>
                        <div class="text-sm text-gray-600">Score requis</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-3xl font-bold text-green-600">${correctCount}</div>
                        <div class="text-sm text-gray-600">Bonnes réponses</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-3xl font-bold text-gray-600">${totalQuestions}</div>
                        <div class="text-sm text-gray-600">Questions posées</div>
                    </div>
                </div>

                <div class="p-4 rounded-lg mb-6 ${passed ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}">
                    <div class="flex items-center">
                        <i class="fas ${passed ? 'fa-trophy text-green-600' : 'fa-exclamation-triangle text-red-600'} text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold ${passed ? 'text-green-800' : 'text-red-800'}">${passed ? 'Félicitations !' : 'Non réussi'}</h3>
                            <p class="${passed ? 'text-green-700' : 'text-red-700'}">${passed ? `Excellent travail ! Vous avez réussi avec ${score}%` : `Vous avez obtenu ${score}%. Score requis: ${PASSING_SCORE}%`}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('client.elearning.qcm.show', $qcm->id) }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-redo-alt mr-2"></i> Recommencer
                    </a>
                    <a href="{{ route('client.elearning.dashboard') }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-home mr-2"></i> Retour au tableau de bord
                    </a>
                </div>
            `;

            container.innerHTML = resultHtml;
        }

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

    .answer-input:checked + div {
        background-color: #fef3c7;
    }

    #actionBtn {
        transition: all 0.2s ease;
    }

    #actionBtn:hover {
        transform: scale(1.02);
    }
</style>
@endpush
