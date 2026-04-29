@extends('layouts.admin')

@section('title', 'Sessions actives E-learning | Admin DJOK PRESTIGE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sessions actives</h1>
                <p class="text-gray-600 mt-1">Utilisateurs actuellement connectés</p>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-users mr-2"></i>
                {{ $activeSessions->count() }} session(s) active(s)
            </div>
        </div>
    </div>

    <!-- Liste des sessions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($activeSessions->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-users-slash text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune session active</h3>
            <p class="text-gray-600">Aucun utilisateur n'est actuellement connecté.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durée
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP & Navigateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Activité
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activeSessions as $session)
                    @php
                    $duration = now()->diffInMinutes($session->login_at);
                    $isInactive = now()->diffInMinutes($session->last_activity_at) > 30;
                    @endphp
                    <tr class="{{ $isInactive ? 'bg-yellow-50' : 'hover:bg-gray-50' }}">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $session->acces->prenom }} {{ $session->acces->nom }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $session->acces->email }}</div>
                            <div class="text-xs">
                                <span class="text-gray-500">Forfait:</span>
                                <span class="font-medium">{{ $session->acces->forfait->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ floor($duration / 60) }}h{{ $duration % 60 }} min
                            </div>
                            <div class="text-xs text-gray-500">
                                Depuis {{ $session->login_at->format('H:i') }}
                            </div>
                            @if($isInactive)
                            <div class="text-xs text-yellow-600">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Inactif depuis {{ now()->diffInMinutes($session->last_activity_at) }} min
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $session->ip_address }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                {{ $session->user_agent }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($session->activity_log)
                            <div class="text-xs text-gray-600">
                                Dernière action:
                                @php
                                $lastActivity = end($session->activity_log);
                                echo $lastActivity['action'] ?? 'N/A';
                                @endphp
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $session->last_activity_at->format('H:i:s') }}
                            </div>
                            @else
                            <div class="text-xs text-gray-500">Aucune activité enregistrée</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('admin.elearning.sessions.force-logout', $session->id) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Forcer la déconnexion de cet utilisateur ?')"
                                    title="Forcer la déconnexion">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-user-clock text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_active'] }}</div>
                    <div class="text-gray-600">Sessions actives</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['inactive_sessions'] }}</div>
                    <div class="text-gray-600">Sessions inactives</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-history text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['avg_session_duration'] }} min</div>
                    <div class="text-gray-600">Durée moyenne</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions récemment terminées -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Sessions récemment terminées</h2>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($recentSessionsList as $session)  <!-- ✅ ICI : recentSessionsList au lieu de recentSessions -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $session->acces->prenom }} {{ $session->acces->nom }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $session->login_at->format('d/m H:i') }} -
                            {{ $session->logout_at ? $session->logout_at->format('H:i') : 'N/A' }}
                            @if($session->forced_logout)
                            <span class="text-red-600 ml-2">
                                <i class="fas fa-ban"></i> Forcée
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">{{ $session->duration }}</div>
                </div>
                @empty
                <div class="text-center py-6 text-gray-500">
                    <i class="fas fa-history text-2xl mb-2 opacity-50"></i>
                    <p>Aucune session terminée récemment</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
