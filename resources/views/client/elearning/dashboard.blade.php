@extends('layouts.client')

@section('title', 'Ma salle virtuelle - DJOK PRESTIGE')
@section('page-title', 'Ma salle virtuelle')
@section('page-description', 'Accédez à vos cours et QCM')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header avec infos utilisateur -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ $acces->prenom }} {{ $acces->nom }}</h1>
                <p class="text-gray-600 mt-1">Code salle: <span class="font-mono">{{ $acces->virtual_room_code }}</span></p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-sm text-gray-500">Accès jusqu'au</div>
                    <div class="font-semibold text-gray-900">{{ $acces->access_end->format('d/m/Y') }}</div>
                </div>
                <a href="{{ route('client.elearning.logout') }}" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($acces->average_qcm_score ?? 0, 1) }}%</div>
            <div class="text-sm text-gray-600">Score moyen QCM</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $acces->access_end->diffInDays(now()) }}</div>
            <div class="text-sm text-gray-600">Jours restants</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $acces->progression_percentage }}%</div>
            <div class="text-sm text-gray-600">Progression globale</div>
        </div>
    </div>

    <!-- Cours disponibles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">Mes cours</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($cours as $coursItem)
            @php
            $progression = isset($progressions[$coursItem->id]) ? $progressions[$coursItem->id] : null;
            @endphp
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="flex-shrink-0 mr-4">
                            @if($progression && $progression->cours_completed)
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $coursItem->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $coursItem->duration_formatted ?? 'Durée non définie' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('client.elearning.cours.show', $coursItem->id) }}"
                       class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        {{ $progression && $progression->cours_completed ? 'Réviser' : 'Commencer' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- QCM disponibles -->
    @if(isset($qcmsNormaux) && $qcmsNormaux->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">QCM d'entraînement</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($qcmsNormaux as $qcm)
            @php
            $progression = $qcmsProgressions[$qcm->id] ?? null;
            $isCompleted = $progression && $progression->qcm_completed == 1;
            @endphp
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900">{{ $qcm->title }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500">{{ $qcm->questions_count }} questions</span>
                            <span class="text-sm text-gray-500">Score requis: {{ $qcm->passing_score }}%</span>
                            @if($isCompleted)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Complété ({{ $progression->qcm_score }}%)
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(!$isCompleted)
                    <a href="{{ route('client.elearning.qcm.show', $qcm->id) }}"
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Commencer le QCM
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
