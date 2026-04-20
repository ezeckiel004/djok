@extends('layouts.admin')

@section('title', 'Nouveau QCM E-learning | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('admin.elearning.qcms') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux QCM
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Créer un nouveau QCM</h2>
        </div>

        <form action="{{ route('admin.elearning.qcms.store') }}" method="POST" class="p-6" id="qcmForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre du QCM *</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ex: QCM Réglementation" value="{{ old('title') }}">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cours associé</label>
                    <select name="cours_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Aucun (QCM indépendant)</option>
                        @foreach($cours as $coursItem)
                        <option value="{{ $coursItem->id }}" {{ old('cours_id')==$coursItem->id ? 'selected' : '' }}>
                            {{ $coursItem->title }}
                        </option>
                        @endforeach
                    </select>
                    @error('cours_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Description du QCM...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de questions *</label>
                    <input type="number" name="questions_count" min="1" max="100" required readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                        value="{{ old('questions_count', 1) }}" id="questionsCount">
                    <p class="text-xs text-gray-500 mt-1">Calculé automatiquement</p>
                    @error('questions_count')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note minimale (%) *</label>
                    <input type="number" name="passing_score" min="0" max="100" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('passing_score', 70) }}">
                    @error('passing_score')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Limite de temps (minutes)</label>
                    <input type="number" name="time_limit_minutes" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Illimité" value="{{ old('time_limit_minutes') }}">
                    @error('time_limit_minutes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tentatives autorisées</label>
                    <input type="number" name="attempts_allowed" min="1" max="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('attempts_allowed', 3) }}">
                    @error('attempts_allowed')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center mb-4">
                    <input type="checkbox" name="is_examen_blanc" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_examen_blanc')
                        ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-medium text-gray-700">Examen blanc</span>
                </label>

                <label class="flex items-center mb-4">
                    <input type="checkbox" name="allow_multiple_correct" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{
                        old('allow_multiple_correct') ? 'checked' : '' }} id="allowMultipleCorrect">
                    <span class="ml-2 text-sm font-medium text-gray-700">Permettre plusieurs réponses correctes par
                        question</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true)
                        ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-medium text-gray-700">QCM actif</span>
                </label>
            </div>

            <!-- Questions -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Questions du QCM</h3>
                    <div class="flex items-center space-x-2">
                        <div class="text-sm text-gray-500">
                            <span id="questionCounter">0</span> question(s)
                        </div>
                        <button type="button" id="addQuestionBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter une question
                        </button>
                    </div>
                </div>

                <div id="questionsContainer" class="space-y-6">
                    <!-- Les questions seront ajoutées ici dynamiquement -->
                </div>

                <!-- Champ caché pour les données JSON des questions -->
                <input type="hidden" name="questions_data" id="questionsData" value="">

                <div class="text-sm text-gray-500 mt-4">
                    <p id="qcmInstructions">⚠️ Attention : Toutes les questions doivent avoir au moins 2 réponses et une
                        réponse correcte sélectionnée.</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.elearning.qcms') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Créer le QCM
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionsContainer = document.getElementById('questionsContainer');
        const addQuestionBtn = document.getElementById('addQuestionBtn');
        const questionsData = document.getElementById('questionsData');
        const questionCounter = document.getElementById('questionCounter');
        const questionsCountInput = document.getElementById('questionsCount');
        const form = document.getElementById('qcmForm');
        const allowMultipleCorrectCheckbox = document.getElementById('allowMultipleCorrect');
        const qcmInstructions = document.getElementById('qcmInstructions');

        let questionIndex = 0;
        const answerLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

        // Fonction pour mettre à jour les instructions
        function updateInstructions() {
            const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;
            if (allowMultiple) {
                qcmInstructions.textContent = '⚠️ Attention : Toutes les questions doivent avoir au moins 2 réponses et au moins une réponse correcte sélectionnée. Plusieurs réponses correctes sont autorisées.';
            } else {
                qcmInstructions.textContent = '⚠️ Attention : Toutes les questions doivent avoir au moins 2 réponses et une seule réponse correcte sélectionnée.';
            }
        }

        // Fonction pour créer le HTML d'une réponse
        function createAnswerHTML(questionId, letter, text = '', isCorrect = false) {
            const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;
            const inputType = allowMultiple ? 'checkbox' : 'radio';
            const inputName = allowMultiple ? `correct_answer_${questionId}[]` : `correct_answer_${questionId}`;

            return `
                <div class="answer-item flex items-center space-x-3 p-3 border border-gray-200 rounded-lg bg-gray-50" data-letter="${letter}">
                    <div class="flex items-center space-x-2">
                        <input type="${inputType}" name="${inputName}"
                               class="correct-answer-input h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                               value="${letter}" ${isCorrect ? 'checked' : ''}>
                        <span class="text-sm font-medium text-gray-700 w-4">${letter}.</span>
                    </div>
                    <input type="text" class="answer-text flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Texte de la réponse ${letter}" value="${text}">
                    <button type="button" class="delete-answer text-red-600 hover:text-red-800 transition-colors" data-question-id="${questionId}" data-letter="${letter}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }

        // Fonction pour créer une nouvelle question
        function addQuestion(questionData = null) {
            questionIndex++;
            const questionId = questionIndex;
            const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;

            const questionHTML = `
                <div class="border border-gray-300 rounded-lg p-6 bg-white question-card" data-question-id="${questionId}">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center font-semibold">
                                ${questionId}
                            </div>
                            <h4 class="text-md font-medium text-gray-900">Question ${questionId}</h4>
                        </div>
                        <button type="button" class="delete-question text-red-600 hover:text-red-800 transition-colors" data-question-id="${questionId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                        <input type="text" class="question-text w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Entrez le texte de la question"
                               value="${questionData?.text || ''}">
                        <p class="text-xs text-gray-500 mt-1">Formulez clairement votre question</p>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700">Réponses *</label>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-500" id="answer-type-hint-${questionId}">
                                    ${allowMultiple ? 'Sélection multiple autorisée' : 'Une seule réponse correcte'}
                                </span>
                                <button type="button" class="add-answer-btn text-sm text-blue-600 hover:text-blue-800 transition-colors" data-question-id="${questionId}">
                                    <i class="fas fa-plus mr-1"></i> Ajouter une réponse
                                </button>
                            </div>
                        </div>

                        <div class="answers-container space-y-3" id="answers-${questionId}">
                            <!-- Les réponses seront ajoutées ici -->
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Explication (optionnelle)</label>
                        <textarea class="explanation w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  rows="2" placeholder="Expliquez pourquoi cette/ces réponse(s) est/sont correcte(s)...">${questionData?.explanation || ''}</textarea>
                    </div>
                </div>
            `;

            questionsContainer.insertAdjacentHTML('beforeend', questionHTML);

            // Ajouter les réponses initiales
            const questionElement = document.querySelector(`[data-question-id="${questionId}"]`);
            const answersContainer = questionElement.querySelector('.answers-container');

            if (questionData?.answers) {
                Object.entries(questionData.answers).forEach(([letter, text], index) => {
                    if (index < answerLetters.length) {
                        // Gérer les deux formats : multiple ou unique
                        let isCorrect = false;
                        if (questionData.correct_answers) {
                            // Format multiple
                            isCorrect = Array.isArray(questionData.correct_answers)
                                ? questionData.correct_answers.includes(letter)
                                : false;
                        } else if (questionData.correct_answer) {
                            // Format unique
                            isCorrect = questionData.correct_answer === letter;
                        }
                        addAnswerToQuestion(questionId, letter, text, isCorrect);
                    }
                });
            } else {
                addAnswerToQuestion(questionId, 'A', '', false);
                addAnswerToQuestion(questionId, 'B', '', false);
            }

            updateQuestionCounter();
            updateQuestionsJson();
        }

        // Fonction pour ajouter une réponse à une question
        function addAnswerToQuestion(questionId, letter, text = '', isCorrect = false) {
            const answersContainer = document.querySelector(`[data-question-id="${questionId}"] .answers-container`);
            if (!answersContainer) return;

            const answerCount = answersContainer.children.length;
            if (answerCount >= answerLetters.length) {
                alert('Maximum 6 réponses par question');
                return;
            }

            const answerLetter = letter || answerLetters[answerCount];
            const answerHTML = createAnswerHTML(questionId, answerLetter, text, isCorrect);
            answersContainer.insertAdjacentHTML('beforeend', answerHTML);
        }

        // Fonction pour mettre à jour le type de sélection des réponses
        function updateAnswerSelectionType() {
            const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;
            const inputType = allowMultiple ? 'checkbox' : 'radio';

            document.querySelectorAll('.question-card').forEach(card => {
                const questionId = card.dataset.questionId;

                // Mettre à jour le texte d'information
                const answerHint = card.querySelector(`#answer-type-hint-${questionId}`);
                if (answerHint) {
                    answerHint.textContent = allowMultiple ? 'Sélection multiple autorisée' : 'Une seule réponse correcte';
                }

                // Mettre à jour tous les inputs de réponse
                card.querySelectorAll('.answer-item').forEach(answerItem => {
                    const letter = answerItem.dataset.letter;
                    const isChecked = answerItem.querySelector('.correct-answer-input')?.checked || false;

                    // Créer un nouvel input
                    const newInputName = allowMultiple ? `correct_answer_${questionId}[]` : `correct_answer_${questionId}`;
                    const newInput = document.createElement('input');
                    newInput.type = inputType;
                    newInput.name = newInputName;
                    newInput.value = letter;
                    newInput.className = 'correct-answer-input h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300';
                    if (isChecked) newInput.checked = true;

                    // Remplacer l'ancien input
                    const inputContainer = answerItem.querySelector('.flex.items-center.space-x-2');
                    const oldInput = answerItem.querySelector('.correct-answer-input');
                    if (oldInput && inputContainer) {
                        inputContainer.replaceChild(newInput, oldInput);
                    }
                });
            });

            updateInstructions();
        }

        // Fonction pour mettre à jour le compteur de questions
        function updateQuestionCounter() {
            const questionCount = document.querySelectorAll('.question-card').length;
            questionCounter.textContent = questionCount;
            questionsCountInput.value = questionCount;
        }

        // Fonction pour mettre à jour le JSON des questions
        function updateQuestionsJson() {
            const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;
            const questions = [];
            const questionCards = document.querySelectorAll('.question-card');

            questionCards.forEach((card) => {
                const questionId = card.dataset.questionId;
                const questionText = card.querySelector('.question-text')?.value.trim() || '';
                const explanation = card.querySelector('.explanation')?.value.trim() || '';

                // Récupérer les réponses
                const answers = {};
                const answerItems = card.querySelectorAll('.answer-item');
                const correctAnswers = [];

                answerItems.forEach(answerItem => {
                    const letter = answerItem.dataset.letter;
                    const text = answerItem.querySelector('.answer-text')?.value.trim() || '';
                    const isCorrect = answerItem.querySelector('.correct-answer-input')?.checked || false;

                    if (text) {
                        answers[letter] = text;
                        if (isCorrect) {
                            correctAnswers.push(letter);
                        }
                    }
                });

                // Validation
                const hasAtLeastOneCorrect = correctAnswers.length > 0;
                const hasEnoughAnswers = Object.keys(answers).length >= 2;
                const isValidForSingle = !allowMultiple && correctAnswers.length === 1;
                const isValidForMultiple = allowMultiple && correctAnswers.length >= 1;

                if (questionText && hasEnoughAnswers && hasAtLeastOneCorrect &&
                    (allowMultiple ? isValidForMultiple : isValidForSingle)) {

                    const questionData = {
                        id: parseInt(questionId),
                        text: questionText,
                        answers: answers,
                        explanation: explanation
                    };

                    if (allowMultiple) {
                        questionData.correct_answers = correctAnswers;
                    } else {
                        questionData.correct_answer = correctAnswers[0];
                    }

                    questions.push(questionData);
                }
            });

            // Mettre à jour le champ caché
            questionsData.value = JSON.stringify({
                questions: questions,
                allow_multiple_correct: allowMultiple
            });
        }

        // Délégation d'événements pour les éléments dynamiques
        questionsContainer.addEventListener('click', function(e) {
            const target = e.target;

            // Bouton supprimer une question
            if (target.closest('.delete-question')) {
                const button = target.closest('.delete-question');
                const questionId = button.dataset.questionId;

                if (document.querySelectorAll('.question-card').length <= 1) {
                    alert('Un QCM doit avoir au moins une question');
                    return;
                }

                if (confirm('Êtes-vous sûr de vouloir supprimer cette question ?')) {
                    const card = document.querySelector(`[data-question-id="${questionId}"]`);
                    if (card) {
                        card.remove();
                        renumberQuestions();
                        updateQuestionCounter();
                        updateQuestionsJson();
                    }
                }
                return;
            }

            // Bouton ajouter une réponse
            if (target.closest('.add-answer-btn')) {
                const button = target.closest('.add-answer-btn');
                const questionId = button.dataset.questionId;
                addAnswerToQuestion(questionId);
                updateQuestionsJson();
                return;
            }

            // Bouton supprimer une réponse
            if (target.closest('.delete-answer')) {
                const button = target.closest('.delete-answer');
                const questionId = button.dataset.questionId;
                const letter = button.dataset.letter;

                const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
                const answerItems = questionCard.querySelectorAll('.answer-item');

                if (answerItems.length <= 2) {
                    alert('Une question doit avoir au moins 2 réponses');
                    return;
                }

                const answerToRemove = questionCard.querySelector(`[data-letter="${letter}"]`);
                if (answerToRemove) {
                    answerToRemove.remove();
                    updateQuestionsJson();
                }
                return;
            }
        });

        // Fonction pour renuméroter les questions
        function renumberQuestions() {
            const questions = Array.from(document.querySelectorAll('.question-card'));
            questionIndex = 0;

            questions.forEach((question, index) => {
                questionIndex++;
                const newQuestionId = questionIndex;

                question.dataset.questionId = newQuestionId;

                const numberCircle = question.querySelector('.bg-blue-100');
                if (numberCircle) {
                    numberCircle.textContent = newQuestionId;
                }

                const title = question.querySelector('h4');
                if (title) {
                    title.textContent = `Question ${newQuestionId}`;
                }

                const deleteBtn = question.querySelector('.delete-question');
                if (deleteBtn) {
                    deleteBtn.dataset.questionId = newQuestionId;
                }

                const addAnswerBtn = question.querySelector('.add-answer-btn');
                if (addAnswerBtn) {
                    addAnswerBtn.dataset.questionId = newQuestionId;
                }

                const answersContainer = question.querySelector('.answers-container');
                if (answersContainer) {
                    answersContainer.id = `answers-${newQuestionId}`;
                }

                // Mettre à jour l'indicateur de type
                const answerHint = question.querySelector(`[id^="answer-type-hint-"]`);
                if (answerHint) {
                    answerHint.id = `answer-type-hint-${newQuestionId}`;
                }

                // Mettre à jour les noms des inputs de réponse
                const allowMultiple = allowMultipleCorrectCheckbox?.checked || false;
                const inputName = allowMultiple ? `correct_answer_${newQuestionId}[]` : `correct_answer_${newQuestionId}`;

                question.querySelectorAll('.correct-answer-input').forEach(input => {
                    input.name = inputName;
                });

                const deleteAnswerBtns = question.querySelectorAll('.delete-answer');
                deleteAnswerBtns.forEach(btn => {
                    btn.dataset.questionId = newQuestionId;
                });
            });
        }

        // Écouteurs d'événements pour la saisie
        questionsContainer.addEventListener('input', function(e) {
            const target = e.target;

            if (target.classList.contains('question-text') ||
                target.classList.contains('answer-text') ||
                target.classList.contains('explanation')) {
                updateQuestionsJson();
            }
        });

        // Écouteur pour les changements de sélection de réponse
        questionsContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('correct-answer-input')) {
                updateQuestionsJson();
            }
        });

        // Événement pour le bouton d'ajout de question
        addQuestionBtn.addEventListener('click', function() {
            addQuestion();
        });

        // Écouter les changements sur la checkbox
        if (allowMultipleCorrectCheckbox) {
            allowMultipleCorrectCheckbox.addEventListener('change', function() {
                updateAnswerSelectionType();
                updateQuestionsJson();
            });
        }

        // Événement de soumission du formulaire
        form.addEventListener('submit', function(e) {
            updateQuestionsJson();

            const data = JSON.parse(questionsData.value || '{"questions": []}');
            const questions = data.questions || [];
            const allowMultiple = data.allow_multiple_correct || false;

            if (questions.length === 0) {
                e.preventDefault();
                alert('Veuillez ajouter au moins une question valide au QCM');
                return;
            }

            // Validation des questions
            for (const question of questions) {
                const questionText = question.text || '';
                const answers = question.answers || {};
                const hasMultipleCorrect = allowMultiple && question.correct_answers;
                const hasSingleCorrect = !allowMultiple && question.correct_answer;

                if (Object.keys(answers).length < 2) {
                    e.preventDefault();
                    alert(`La question "${questionText.substring(0, 50)}..." doit avoir au moins 2 réponses`);
                    return;
                }

                if (allowMultiple) {
                    if (!question.correct_answers || question.correct_answers.length === 0) {
                        e.preventDefault();
                        alert(`La question "${questionText.substring(0, 50)}..." doit avoir au moins une réponse correcte sélectionnée`);
                        return;
                    }
                } else {
                    if (!question.correct_answer) {
                        e.preventDefault();
                        alert(`La question "${questionText.substring(0, 50)}..." doit avoir une réponse correcte sélectionnée`);
                        return;
                    }
                }
            }
        });

        // Ajouter une première question au chargement
        addQuestion();
        updateInstructions();
    });
</script>

<style>
    .question-card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.2s ease-in-out;
    }

    .question-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .answer-item {
        transition: all 0.2s ease-in-out;
    }

    .answer-item:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
    }

    .correct-answer-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
</style>
@endpush
