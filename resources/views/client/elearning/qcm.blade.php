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
                    <i class="fas fa-list-ol mr-1"></i> {{ count($questions) }} questions
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
        // Données PHP
        const RAW_QUESTIONS   = @json($questions);
        const ALLOW_MULTIPLE  = {{ $qcm->allow_multiple_correct ? 'true' : 'false' }};
        const QCM_ID          = {{ $qcm->id }};
        const PASSING_SCORE   = {{ $qcm->passing_score }};
        const TIME_LIMIT      = 25;

        // Normalisation
        const QUESTIONS = Array.isArray(RAW_QUESTIONS) ? RAW_QUESTIONS : Object.values(RAW_QUESTIONS);
        const TOTAL_QUESTIONS = QUESTIONS.length;

        let shuffledQuestions = [];
        let currentIndex = 0;
        let userAnswers = {};
        let timerInterval = null;
        let isWaiting = false;
        let container = null;

        if (TOTAL_QUESTIONS === 0) {
            document.getElementById('qcmApp').innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
                    <p class="text-gray-600">Aucune question disponible pour ce QCM.</p>
                    <a href="{{ route('client.elearning.dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded-lg">Retour</a>
                </div>`;
            return;
        }

        function fisherYates(arr) {
            const a = [...arr];
            for (let i = a.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [a[i], a[j]] = [a[j], a[i]];
            }
            return a;
        }

        function init() {
            shuffledQuestions = fisherYates(QUESTIONS);
            for (let i = 0; i < shuffledQuestions.length; i++) {
                userAnswers[i] = null;
            }
            container = document.getElementById('qcmApp');
            showQuestion(0);
        }

        function showQuestion(index) {
            if (isWaiting) return;
            if (index >= shuffledQuestions.length) {
                submitQcm();
                return;
            }

            currentIndex = index;
            const q = shuffledQuestions[index];

            let answersHtml = '';
            const answers = q.answers || {};

            for (let [letter, text] of Object.entries(answers)) {
                const isChecked = userAnswers[index] && (ALLOW_MULTIPLE ? userAnswers[index].includes(letter) : userAnswers[index] === letter);
                const inputType = ALLOW_MULTIPLE ? 'checkbox' : 'radio';
                answersHtml += `
                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-yellow-50 cursor-pointer mb-2 transition-all duration-200">
                        <input type="${inputType}" name="answer" value="${letter}" class="answer-input mt-1 mr-3 flex-shrink-0" ${isChecked ? 'checked' : ''}>
                        <div>
                            <span class="font-bold text-yellow-600">${escapeHtml(letter)}.</span>
                            <span class="ml-2 text-gray-700">${escapeHtml(text)}</span>
                        </div>
                    </label>
                `;
            }

            const progressPct = Math.round((index / shuffledQuestions.length) * 100);

            const html = `
                <div id="questionCard" class="transition-all duration-300" style="opacity:0; transform:translateY(20px);">
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Question ${index + 1} / ${shuffledQuestions.length}</span>
                            <span>${progressPct}% complété</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500" style="width:${progressPct}%"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-start mb-6 gap-4">
                        <div class="flex items-start flex-1">
                            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center mr-3 flex-shrink-0">
                                <span class="font-bold text-white text-sm">${index + 1}</span>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900 leading-snug">${escapeHtml(q.text)}</h2>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs text-gray-500 mb-1">Temps restant</div>
                            <div id="timerDisplay" class="text-2xl font-mono font-bold text-green-600">00:${String(TIME_LIMIT).padStart(2,'0')}</div>
                        </div>
                    </div>

                    <div class="space-y-2 mb-6">
                        ${answersHtml}
                    </div>

                    ${ALLOW_MULTIPLE ? '<p class="text-sm text-blue-600 mb-4"><i class="fas fa-info-circle mr-1"></i>Vous pouvez sélectionner plusieurs réponses.</p>' : ''}

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            <span id="answeredCount">${getAnsweredCount()}</span> répondue(s)
                        </div>
                        <button id="nextBtn" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium">
                            ${index === shuffledQuestions.length - 1 ? '<i class="fas fa-flag-checkered mr-2"></i>Terminer' : 'Question suivante <i class="fas fa-arrow-right ml-2"></i>'}
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

            attachEvents(index);
            startTimer(index);
        }

        function getAnsweredCount() {
            return Object.values(userAnswers).filter(a => a !== null && (Array.isArray(a) ? a.length > 0 : true)).length;
        }

        function attachEvents(index) {
            document.querySelectorAll('.answer-input').forEach(input => {
                input.addEventListener('change', () => saveAnswer(index));
            });

            const nextBtn = document.getElementById('nextBtn');
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (!isWaiting) {
                        saveAnswer(index);
                        if (index === shuffledQuestions.length - 1) {
                            submitQcm();
                        } else {
                            moveToNextQuestion();
                        }
                    }
                });
            }
        }

        function saveAnswer(index) {
            const inputs = document.querySelectorAll('.answer-input');
            if (ALLOW_MULTIPLE) {
                userAnswers[index] = Array.from(inputs).filter(i => i.checked).map(i => i.value);
            } else {
                const selected = Array.from(inputs).find(i => i.checked);
                userAnswers[index] = selected ? selected.value : null;
            }

            const countSpan = document.getElementById('answeredCount');
            if (countSpan) countSpan.textContent = getAnsweredCount();
        }

        function startTimer(index) {
            if (timerInterval) clearInterval(timerInterval);

            let timeLeft = TIME_LIMIT;
            const timerDisplaySpan = document.getElementById('timerDisplay');

            timerInterval = setInterval(() => {
                if (isWaiting) return;

                timeLeft--;
                if (timerDisplaySpan) {
                    const mins = Math.floor(timeLeft / 60);
                    const secs = timeLeft % 60;
                    timerDisplaySpan.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

                    if (timeLeft <= 5) {
                        timerDisplaySpan.style.color = '#ef4444';
                    } else if (timeLeft <= 10) {
                        timerDisplaySpan.style.color = '#f59e0b';
                    }
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    if (!isWaiting) {
                        // Si pas de réponse, enregistrer comme non répondue
                        if (userAnswers[index] === null || (Array.isArray(userAnswers[index]) && userAnswers[index].length === 0)) {
                            userAnswers[index] = ALLOW_MULTIPLE ? [] : null;
                        }
                        // Passer à la question suivante ou terminer
                        if (index === shuffledQuestions.length - 1) {
                            submitQcm();
                        } else {
                            moveToNextQuestion();
                        }
                    }
                }
            }, 1000);
        }

        function moveToNextQuestion() {
            if (isWaiting) return;

            isWaiting = true;

            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }

            // Animation de sortie
            const card = document.getElementById('questionCard');
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    isWaiting = false;
                    showQuestion(currentIndex + 1);
                }, 300);
            } else {
                isWaiting = false;
                showQuestion(currentIndex + 1);
            }
        }

        async function submitQcm() {
            if (isWaiting) return;
            isWaiting = true;
            if (timerInterval) clearInterval(timerInterval);

            const formattedAnswers = {};
            for (let i = 0; i < shuffledQuestions.length; i++) {
                const q = shuffledQuestions[i];
                const originalId = q.id || i;
                const answer = userAnswers[i];
                formattedAnswers[originalId] = (answer === null || (Array.isArray(answer) && answer.length === 0)) ? (ALLOW_MULTIPLE ? [] : null) : answer;
            }

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

        function showResults(data) {
            const score = Math.round(data.score);
            const passed = score >= PASSING_SCORE;

            let resultHtml = `
                <div class="text-center mb-6">
                    <i class="fas ${passed ? 'fa-trophy text-yellow-500' : 'fa-book-open text-red-500'} text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">Résultats du QCM</h2>
                    <p class="text-gray-600">{{ $qcm->title }}</p>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-5xl font-bold text-yellow-600">${score}%</div>
                        <div class="text-sm text-gray-600">Votre score</div>
                    </div>
                    <div class="p-4 text-center rounded-lg bg-gray-50">
                        <div class="text-5xl font-bold text-blue-600">${PASSING_SCORE}%</div>
                        <div class="text-sm text-gray-600">Score requis</div>
                    </div>
                </div>
                <div class="p-4 rounded-lg mb-6 ${passed ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}">
                    <div class="flex items-center">
                        <i class="fas ${passed ? 'fa-trophy text-green-600' : 'fa-exclamation-triangle text-red-600'} text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold ${passed ? 'text-green-800' : 'text-red-800'}">${passed ? 'Félicitations !' : 'Non réussi'}</h3>
                            <p class="${passed ? 'text-green-700' : 'text-red-700'}">${passed ? `Vous avez réussi avec ${score}%` : `Vous avez obtenu ${score}%. Score requis: ${PASSING_SCORE}%`}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('client.elearning.dashboard') }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-home mr-2"></i> Retour au tableau de bord
                    </a>
                </div>
            `;

            container.innerHTML = resultHtml;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
@endpush
