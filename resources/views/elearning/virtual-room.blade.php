@extends('layouts.main')

@section('title', __('virtual-room.page_title'))

@section('content')
<!-- Header -->
<div class="bg-black border-b border-gray-800">
    <div class="container px-4 mx-auto md:px-6">
        <div class="flex flex-col py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <img src="{{ asset('DP2.webp') }}" alt="DJOK PRESTIGE" class="h-10">
                <div class="ml-4">
                    <h1 class="text-lg font-bold text-white">{{ __('virtual-room.virtual_room_title') }}</h1>
                    <p class="text-sm text-gray-400">{{ $acces->virtual_room_code }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden text-right md:block">
                    <div class="text-sm font-medium text-white">{{ $acces->prenom }} {{ $acces->nom }}</div>
                    <div class="text-xs text-gray-400">{{ $acces->email }}</div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('elearning.logout') }}"
                        class="px-4 py-2 text-sm font-medium transition-colors rounded"
                        style="background: #7f1d1d; color: white;">
                        <i class="mr-1 fas fa-sign-out-alt"></i> {{ __('virtual-room.logout') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="bg-gray-900 border-b border-gray-800">
    <div class="container px-4 py-4 mx-auto md:px-6">
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="text-center">
                <div class="text-2xl font-bold" style="color: #b89449;">{{ $acces->cours_completed }}/{{ $acces->total_cours }}</div>
                <div class="text-xs text-gray-400">{{ __('virtual-room.courses_completed') }}</div>
                <div class="h-1 mt-1 overflow-hidden bg-gray-800 rounded-full">
                    <div class="h-full" style="background: #b89449; width: {{ $acces->progression_percentage ?? 0 }}%">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold" style="color: #60a5fa;">{{ number_format($acces->average_qcm_score ?? 0, 1) }}%
                </div>
                <div class="text-xs text-gray-400">{{ __('virtual-room.average_qcm_score') }}</div>
            </div>

            <div class="text-center">
                <div class="text-2xl font-bold" style="color: #10b981;">{{
                    $acces->forfait->duration_days - now()->diffInDays($acces->access_start) }}</div>
                <div class="text-xs text-gray-400">{{ __('virtual-room.days_remaining') }}</div>
            </div>

            <div class="text-center">
                <div class="text-lg font-bold" style="color: #ddd;">{{ $acces->forfait->name }}</div>
                <div class="text-xs text-gray-400">{{ __('virtual-room.package') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Messages d'alerte -->
@if(session('error'))
<div class="container px-4 mx-auto mt-4 md:px-6">
    <div class="p-4 rounded-lg" style="background: #7f1d1d; border: 1px solid #ef4444;">
        <div class="flex items-center">
            <i class="mr-3 fas fa-exclamation-circle" style="color: #fca5a5;"></i>
            <div>
                <p class="font-medium text-white">{{ session('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('info'))
<div class="container px-4 mx-auto mt-4 md:px-6">
    <div class="p-4 rounded-lg" style="background: #064e3b; border: 1px solid #047857;">
        <div class="flex items-center">
            <i class="mr-3 fas fa-info-circle" style="color: #a7f3d0;"></i>
            <div>
                <p class="font-medium text-white">{{ session('info') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Content -->
<div class="py-8" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Cours -->
            <div class="lg:col-span-2">
                <div class="rounded-lg" style="background: #111; border: 1px solid #333;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h2 class="text-lg font-bold text-white">{{ __('virtual-room.available_courses') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('virtual-room.click_to_start') }}</p>
                    </div>

                    <div class="divide-y divide-gray-800">
                        @foreach($cours as $coursItem)
                        @php
                        $progression = isset($progressions[$coursItem->id]) ? $progressions[$coursItem->id] : null;
                        @endphp
                        <div class="px-6 py-4 transition-colors hover:bg-gray-900">
                            <div class="flex flex-col justify-between md:flex-row md:items-center">
                                <div class="flex items-start mb-3 md:items-center md:mb-0">
                                    <div class="flex-shrink-0 mr-4">
                                        @if($progression && $progression->cours_completed)
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full"
                                            style="background: #064e3b;">
                                            <i class="fas fa-check" style="color: #a7f3d0;"></i>
                                        </div>
                                        @else
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full"
                                            style="background: #1e40af;">
                                            <i class="fas fa-book" style="color: #60a5fa;"></i>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="mb-1 text-sm font-medium text-white break-words">{{ $coursItem->title }}</h3>
                                        <div class="flex flex-wrap items-center text-xs text-gray-400">
                                            <i class="mr-1 fas fa-clock"></i>
                                            <span class="mr-3">{{ $coursItem->duration_formatted ?? __('virtual-room.not_defined') }}</span>
                                            @if($coursItem->hasVideo() ?? false)
                                            <i class="mr-1 fas fa-video"></i>
                                            <span>{{ __('virtual-room.video') }}</span>
                                            @endif
                                        </div>
                                        @if($coursItem->description)
                                        <p class="mt-2 text-xs text-gray-500 break-words line-clamp-2">{{ $coursItem->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 mt-2 md:mt-0">
                                    @if($progression && $progression->qcm_completed)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                        style="{{ $progression->qcm_score >= 70 ? 'background: #064e3b; color: #a7f3d0;' : 'background: #7f1d1d; color: #fca5a5;' }}">
                                        QCM: {{ $progression->qcm_score ?? 0 }}%
                                    </span>
                                    @endif

                                    <a href="{{ route('elearning.cours.show', $coursItem->id) }}"
                                        class="px-3 py-1 text-sm font-medium transition-colors rounded whitespace-nowrap"
                                        style="background: #b89449; color: black;">
                                        {{ $progression && $progression->cours_completed ? __('virtual-room.review') : __('virtual-room.start') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- QCM NORMEAUX DISPONIBLES -->
                @if($qcmsNormaux->count() > 0)
                <div class="mt-8 rounded-lg" style="background: #111; border: 1px solid #333;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h2 class="text-lg font-bold text-white">{{ __('virtual-room.training_qcm') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('virtual-room.test_knowledge') }}</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach($qcmsNormaux as $qcm)
                            @php
                            // Récupérer la progression depuis le tableau indexé par qcm_id
                            $qcmProgression = $qcmsProgressions[$qcm->id] ?? null;

                            // VÉRIFICATION MULTIPLE pour être sûr
                            $isCompleted = false;

                            // Méthode 1: Vérifier dans la collection des complétés
                            if ($allQcmsCompletes->contains('id', $qcm->id)) {
                                $isCompleted = true;
                            }

                            // Méthode 2: Vérifier dans la progression (avec == 1 pour gérer les types)
                            if ($qcmProgression && ($qcmProgression->qcm_completed == 1 || $qcmProgression->qcm_completed === true)) {
                                $isCompleted = true;
                            }

                            // Compter les tentatives
                            $attemptsCount = $qcmProgression ? (int)$qcmProgression->qcm_attempts : 0;

                            // Récupérer le score si disponible
                            $qcmScore = $qcmProgression ? $qcmProgression->qcm_score : null;
                            @endphp
                            <div class="flex flex-col h-full p-4 transition-colors rounded hover:bg-gray-900"
                                style="background: #1a1a1a; border: 1px solid #333;">
                                <div class="flex items-center mb-3">
                                    <i class="flex-shrink-0 mr-3 fas fa-question-circle" style="color: #60a5fa;"></i>
                                    <h3 class="font-medium text-white break-words">{{ $qcm->title }}</h3>
                                    @if($isCompleted)
                                    <span class="px-2 py-1 ml-2 text-xs rounded whitespace-nowrap"
                                        style="background: #064e3b; color: #a7f3d0;">
                                        <i class="mr-1 fas fa-check"></i> {{ __('virtual-room.completed') }}
                                        @if($qcmScore)
                                        <span class="ml-1">({{ $qcmScore }}%)</span>
                                        @endif
                                    </span>
                                    @endif
                                </div>

                                @if($qcm->description)
                                <div class="mb-3 flex-1 min-h-[60px]">
                                    <p class="text-sm text-gray-400 break-words line-clamp-3">{{ $qcm->description }}</p>
                                </div>
                                @endif

                                <div class="flex items-center mb-2">
                                    <div class="mr-3 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-list-ol"></i>
                                        {{ $qcm->questions_count }} {{ __('virtual-room.questions') }}
                                    </div>
                                    <div class="mr-3 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-chart-line"></i>
                                        {{ __('virtual-room.minimum_score') }}: {{ $qcm->passing_score }}%
                                    </div>
                                    @if($qcm->time_limit_minutes)
                                    <div class="text-xs text-gray-500">
                                        <i class="mr-1 fas fa-clock"></i>
                                        {{ $qcm->time_limit_minutes }} min
                                    </div>
                                    @endif
                                </div>

                                @if($qcm->allow_multiple_correct)
                                <div class="mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                        style="background: #a855f7; color: white;">
                                        <i class="mr-1 fas fa-check-double"></i> {{ __('virtual-room.multiple_answers') }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex items-center justify-between pt-3 border-t border-gray-700">
                                    <div class="text-xs text-gray-500">
                                        @if($isCompleted)
                                        <div class="text-green-500">
                                            <i class="mr-1 fas fa-check-circle"></i>
                                            {{ __('virtual-room.qcm_completed') }}
                                            @if($qcmScore)
                                            <span class="ml-1">({{ $qcmScore }}%)</span>
                                            @endif
                                        </div>
                                        @elseif($attemptsCount > 0)
                                        <div class="text-yellow-500">
                                            <i class="mr-1 fas fa-exclamation-triangle"></i>
                                            {{ __('virtual-room.already_attempted') }}: {{ $attemptsCount }} {{ __('virtual-room.times') }}
                                        </div>
                                        @else
                                        <div class="text-blue-500">
                                            <i class="mr-1 fas fa-play-circle"></i>
                                            {{ __('virtual-room.new') }}
                                        </div>
                                        @endif
                                    </div>

                                    @if($isCompleted)
                                    <span class="px-3 py-1 text-sm font-medium rounded whitespace-nowrap"
                                        style="background: #064e3b; color: #a7f3d0; cursor: not-allowed;">
                                        <i class="mr-1 fas fa-check"></i> {{ __('virtual-room.completed') }}
                                    </span>
                                    @else
                                    <a href="{{ route('elearning.qcm.show', $qcm->id) }}"
                                        class="px-3 py-1 text-sm font-medium transition-colors rounded whitespace-nowrap hover:bg-blue-600"
                                        style="background: #1e40af; color: white;">
                                        @if($attemptsCount > 0)
                                        {{ __('virtual-room.resume') }}
                                        @else
                                        {{ __('virtual-room.take') }}
                                        @endif
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- EXAMENS BLANCS DISPONIBLES -->
                @if($examensBlancs->count() > 0)
                <div class="mt-8 rounded-lg" style="background: #111; border: 1px solid #7f1d1d;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h2 class="text-lg font-bold text-white">{{ __('virtual-room.practice_exams') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('virtual-room.prepare_certifications') }}</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach($examensBlancs as $examen)
                            @php
                            // Récupérer la progression depuis le tableau indexé par qcm_id
                            $examenProgression = $qcmsProgressions[$examen->id] ?? null;

                            // VÉRIFICATION MULTIPLE
                            $isCompleted = false;

                            if ($allQcmsCompletes->contains('id', $examen->id)) {
                                $isCompleted = true;
                            }

                            if ($examenProgression && ($examenProgression->qcm_completed == 1 || $examenProgression->qcm_completed === true)) {
                                $isCompleted = true;
                            }

                            $attemptsCount = $examenProgression ? (int)$examenProgression->qcm_attempts : 0;
                            $examenScore = $examenProgression ? $examenProgression->qcm_score : null;
                            @endphp
                            <div class="flex flex-col h-full p-4 transition-colors rounded hover:bg-gray-900"
                                style="background: #1a1a1a; border: 1px solid #7f1d1d;">
                                <div class="flex items-center mb-3">
                                    <i class="flex-shrink-0 mr-3 fas fa-file-alt" style="color: #f56565;"></i>
                                    <h3 class="font-medium text-white break-words">{{ $examen->title }}</h3>
                                    <div class="flex flex-col ml-2 space-y-1">
                                        <span class="px-2 py-1 text-xs rounded whitespace-nowrap"
                                            style="background: #7f1d1d; color: #fca5a5;">
                                            {{ __('virtual-room.practice_exam') }}
                                        </span>
                                        @if($isCompleted)
                                        <span class="px-2 py-1 text-xs rounded whitespace-nowrap"
                                            style="background: #064e3b; color: #a7f3d0;">
                                            <i class="mr-1 fas fa-check"></i> {{ __('virtual-room.completed') }}
                                            @if($examenScore)
                                            <span class="ml-1">({{ $examenScore }}%)</span>
                                            @endif
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                @if($examen->description)
                                <div class="mb-3 flex-1 min-h-[60px]">
                                    <p class="text-sm text-gray-400 break-words line-clamp-3">{{ $examen->description }}</p>
                                </div>
                                @endif

                                <div class="flex items-center mb-2">
                                    <div class="mr-3 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-list-ol"></i>
                                        {{ $examen->questions_count }} {{ __('virtual-room.questions') }}
                                    </div>
                                    <div class="mr-3 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-chart-line"></i>
                                        {{ __('virtual-room.minimum_score') }}: {{ $examen->passing_score }}%
                                    </div>
                                    @if($examen->time_limit_minutes)
                                    <div class="text-xs text-gray-500">
                                        <i class="mr-1 fas fa-clock"></i>
                                        {{ $examen->time_limit_minutes }} min
                                    </div>
                                    @endif
                                </div>

                                @if($examen->allow_multiple_correct)
                                <div class="mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                        style="background: #a855f7; color: white;">
                                        <i class="mr-1 fas fa-check-double"></i> {{ __('virtual-room.multiple_answers') }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex items-center justify-between pt-3 border-t border-gray-700">
                                    <div class="text-xs text-gray-500">
                                        @if($isCompleted)
                                        <div class="text-green-500">
                                            <i class="mr-1 fas fa-check-circle"></i>
                                            {{ __('virtual-room.exam_completed') }}
                                            @if($examenScore)
                                            <span class="ml-1">({{ $examenScore }}%)</span>
                                            @endif
                                        </div>
                                        @elseif($attemptsCount > 0)
                                        <div class="text-yellow-500">
                                            <i class="mr-1 fas fa-exclamation-triangle"></i>
                                            {{ __('virtual-room.already_attempted') }}: {{ $attemptsCount }} {{ __('virtual-room.times') }}
                                        </div>
                                        @else
                                        <div class="text-red-500">
                                            <i class="mr-1 fas fa-play-circle"></i>
                                            {{ __('virtual-room.new_exam') }}
                                        </div>
                                        @endif
                                    </div>

                                    @if($isCompleted)
                                    <span class="px-3 py-1 text-sm font-medium rounded whitespace-nowrap"
                                        style="background: #064e3b; color: #a7f3d0; cursor: not-allowed;">
                                        <i class="mr-1 fas fa-check"></i> {{ __('virtual-room.completed') }}
                                    </span>
                                    @else
                                    <a href="{{ route('elearning.qcm.show', $examen->id) }}"
                                        class="px-3 py-1 text-sm font-medium transition-colors rounded whitespace-nowrap hover:bg-red-700"
                                        style="background: #7f1d1d; color: white;">
                                        @if($attemptsCount > 0)
                                        {{ __('virtual-room.retake') }}
                                        @else
                                        {{ __('virtual-room.practice') }}
                                        @endif
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- QCM COMPLÉTÉS (Terminés) -->
                @if($allQcmsCompletes->count() > 0)
                <div class="mt-8 rounded-lg" style="background: #111; border: 1px solid #064e3b;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h2 class="text-lg font-bold text-white">{{ __('virtual-room.completed_qcm') }}</h2>
                        <p class="text-sm text-gray-400">{{ __('virtual-room.history_qcm_exams') }}</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach($allQcmsCompletes as $qcm)
                            @php
                            $progression = $qcmsProgressions[$qcm->id] ?? null;
                            $isExamenBlanc = $qcm->is_examen_blanc;
                            @endphp
                            <div class="flex flex-col h-full p-4 transition-colors rounded"
                                style="background: #1a1a1a; border: 1px solid #064e3b; opacity: 0.9;">
                                <div class="flex items-center mb-3">
                                    <i class="flex-shrink-0 mr-3 fas fa-check-circle"
                                        style="color: {{ $progression && $progression->qcm_score >= 70 ? '#10b981' : '#f56565' }};"></i>
                                    <h3 class="font-medium text-white break-words">{{ $qcm->title }}</h3>
                                    @if($isExamenBlanc)
                                    <span class="px-2 py-1 ml-2 text-xs rounded whitespace-nowrap"
                                        style="background: #7f1d1d; color: #fca5a5;">
                                        {{ __('virtual-room.practice_exam') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="mb-3 flex-1 min-h-[60px]">
                                    <p class="text-sm text-gray-400 break-words">
                                        <strong class="{{ $progression && $progression->qcm_score >= 70 ? 'text-green-500' : 'text-red-500' }}">
                                            {{ __('virtual-room.final_score') }}: {{ $progression->qcm_score ?? 0 }}%
                                        </strong>
                                        @if($progression && $progression->qcm_score >= 70)
                                        <span class="ml-2 text-green-500">
                                            <i class="fas fa-check"></i> {{ __('virtual-room.passed') }}
                                        </span>
                                        @else
                                        <span class="ml-2 text-red-500">
                                            <i class="fas fa-times"></i> {{ __('virtual-room.failed') }}
                                        </span>
                                        @endif
                                    </p>
                                    @if($progression && $progression->qcm_completed_at)
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-history"></i>
                                        {{ __('virtual-room.completed_on') }} {{ $progression->qcm_completed_at->format('d/m/Y H:i') }}
                                    </p>
                                    @endif
                                    @if($progression && $progression->qcm_attempts > 0)
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-redo"></i>
                                        {{ __('virtual-room.attempt') }}: {{ $progression->qcm_attempts }}
                                    </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-700">
                                    <div class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $qcm->questions_count }} {{ __('virtual-room.questions') }}
                                    </div>
                                    <span class="px-3 py-1 text-sm font-medium rounded whitespace-nowrap"
                                        style="background: #064e3b; color: #a7f3d0;">
                                        <i class="mr-1 fas fa-check-circle"></i> {{ __('virtual-room.completed') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Infos accès -->
                <div class="mb-6 rounded-lg" style="background: #111; border: 1px solid #333;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h3 class="font-bold text-white">{{ __('virtual-room.your_access') }}</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <p class="mb-1 text-sm text-gray-400">{{ __('virtual-room.access_code') }}</p>
                            <div class="flex items-center">
                                <code class="px-3 py-2 font-mono text-sm break-all rounded"
                                    style="background: #000; color: white;">
                                    {{ $acces->access_code }}
                                </code>
                                <button onclick="copyToClipboard('{{ $acces->access_code }}')" class="flex-shrink-0 ml-2">
                                    <i class="fas fa-copy" style="color: #b89449;"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <p class="mb-1 text-sm text-gray-400">{{ __('virtual-room.virtual_room') }}</p>
                            <p class="font-medium text-white break-words">{{ $acces->virtual_room_code }}</p>
                        </div>

                        <div>
                            <p class="mb-1 text-sm text-gray-400">{{ __('virtual-room.overall_progress') }}</p>
                            <div class="flex items-center">
                                <div class="flex-1 h-2 overflow-hidden rounded-full" style="background: #333;">
                                    <div class="h-full"
                                        style="background: #b89449; width: {{ $acces->progression_percentage ?? 0 }}%">
                                    </div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-white whitespace-nowrap">{{
                                    number_format($acces->progression_percentage ?? 0, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques QCM -->
                @php
                $totalQcmsActifs = \App\Models\ElearningQcm::where('is_active', true)->count();
                $completedCount = $allQcmsCompletes->count();
                $availableCount = $qcmsNormaux->count() + $examensBlancs->count();
                @endphp

                <div class="mb-6 rounded-lg" style="background: #111; border: 1px solid #333;">
                    <div class="px-6 py-4 border-b border-gray-800">
                        <h3 class="font-bold text-white">{{ __('virtual-room.qcm_statistics') }}</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-400">{{ __('virtual-room.available_qcm') }}</span>
                                <span class="text-sm font-medium text-white">{{ $availableCount }}/{{ $totalQcmsActifs }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full" style="background: #333;">
                                <div class="h-full"
                                    style="background: #1e40af; width: {{ $totalQcmsActifs > 0 ? ($availableCount/$totalQcmsActifs*100) : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-400">{{ __('virtual-room.completed_qcm_stat') }}</span>
                                <span class="text-sm font-medium text-white">{{ $completedCount }}/{{ $totalQcmsActifs }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full" style="background: #333;">
                                <div class="h-full"
                                    style="background: {{ $completedCount > 0 ? '#064e3b' : '#7f1d1d' }}; width: {{ $totalQcmsActifs > 0 ? ($completedCount/$totalQcmsActifs*100) : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-800">
                            <div class="text-xs text-gray-400">
                                <i class="mr-1 fas fa-info-circle"></i>
                                {{ __('virtual-room.qcm_once') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aide -->
                <div class="p-6 rounded-lg" style="background: #1e3a8a; border: 1px solid #2563eb;">
                    <h3 class="mb-4 font-bold text-white">{{ __('virtual-room.need_help') }}</h3>

                    <div class="space-y-4">
                        <a href="mailto:support@djokprestige.com"
                            class="flex items-center text-blue-100 break-words hover:text-white">
                            <i class="flex-shrink-0 mr-3 fas fa-envelope"></i>
                            <span>{{ __('virtual-room.contact_support') }}</span>
                        </a>

                        <div class="flex items-center text-blue-100">
                            <i class="flex-shrink-0 mr-3 fas fa-phone"></i>
                            <span>{{ __('virtual-room.phone') }}</span>
                        </div>

                        <div class="pt-4 border-t border-blue-400">
                            <p class="text-sm text-blue-200 break-words">
                                <i class="flex-shrink-0 mr-1 fas fa-info-circle"></i>
                                {{ __('virtual-room.qcm_once_warning') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('{{ __("virtual-room.copy_clipboard") }}');
        }, function(err) {
            console.error('{{ __("virtual-room.copy_error") }}: ', err);
        });
    }
</script>
@endsection

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .break-words {
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }
</style>
@endsection
