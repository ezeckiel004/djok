@extends('layouts.client')

@section('title', 'Forfaits - DJOK PRESTIGE')
@section('page-title', 'Forfaits')
@section('page-description', 'Formations en ligne pour la préparation VTC')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-500">Forfaits</span>
    </div>
</li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
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
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
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
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
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

    {{-- Accès actif --}}
    @if($accesActif)
    <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center">
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-door-open text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Vous avez un accès actif !</h3>
                    <p class="text-green-700">
                        Forfait {{ $accesActif->forfait->name }} - Accès jusqu'au {{ $accesActif->access_end->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('client.elearning.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-play mr-2"></i> Accéder à ma salle virtuelle
            </a>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Forfaits</h1>
        <p class="mt-1 text-gray-600">Choisissez le forfait qui correspond à vos besoins</p>
    </div>

    {{-- Forfaits --}}
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        @foreach($forfaits as $forfait)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">{{ $forfait->name }}</h3>
                <div class="mt-2 flex items-baseline">
                    <span class="text-3xl font-bold text-yellow-600">{{ $forfait->formatted_price }}</span>
                    <span class="ml-2 text-gray-500">/ {{ $forfait->duration_days }} jours</span>
                </div>
            </div>

            <div class="p-6">
                <p class="text-gray-600 mb-4">{{ $forfait->description }}</p>

                @if($forfait->include_all_cours && $forfait->include_all_qcms && $forfait->include_all_examens)
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mb-4">
                        <i class="fas fa-layer-group mr-1"></i> Tout inclus
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-4">
                        <i class="fas fa-check-double mr-1"></i> Sélection personnalisée
                    </span>
                @endif

                <ul class="space-y-2 mb-6">
                    @if($forfait->include_all_cours)
                    <li class="flex items-center text-sm">
                        <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                        <span class="text-gray-600">Tous les cours disponibles</span>
                    </li>
                    @else
                        @php $coursCount = count($forfait->selected_cours_ids ?? []); @endphp
                        @if($coursCount > 0)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                            <span class="text-gray-600">{{ $coursCount }} cours sélectionnés</span>
                        </li>
                        @endif
                    @endif

                    @if($forfait->includes_qcm)
                        @if($forfait->include_all_qcms)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                            <span class="text-gray-600">Tous les QCM</span>
                        </li>
                        @else
                            @php $qcmsCount = count($forfait->selected_qcms_ids ?? []); @endphp
                            @if($qcmsCount > 0)
                            <li class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $qcmsCount }} QCM sélectionnés</span>
                            </li>
                            @endif
                        @endif
                    @endif

                    @if($forfait->includes_examens_blancs)
                        @if($forfait->include_all_examens)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                            <span class="text-gray-600">Tous les examens blancs</span>
                        </li>
                        @else
                            @php $examensCount = count($forfait->selected_examens_ids ?? []); @endphp
                            @if($examensCount > 0)
                            <li class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $examensCount }} examens blancs sélectionnés</span>
                            </li>
                            @endif
                        @endif
                    @endif

                    @if($forfait->includes_certification)
                    <li class="flex items-center text-sm">
                        <i class="fas fa-check text-green-500 w-5 mr-2"></i>
                        <span class="text-gray-600">Certification incluse</span>
                    </li>
                    @endif
                </ul>

                <a href="{{ route('client.elearning.acheter', $forfait->slug) }}"
                    class="block w-full text-center py-2 px-4 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-semibold">
                    Choisir ce forfait
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($forfaits->isEmpty())
    <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
            <i class="fas fa-graduation-cap text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Aucun forfait disponible</h3>
        <p class="mt-1 text-gray-500">De nouveaux forfaits seront bientôt disponibles.</p>
    </div>
    @endif

    {{-- Mes accès --}}
    @if($mesAcces->count() > 0)
    <div class="mt-12">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Mes accès</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Forfait</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'achat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progression</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($mesAcces as $acces)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $acces->forfait->name }}</div>
                            <div class="text-xs text-gray-500">Code: {{ $acces->access_code }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $acces->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm {{ $acces->access_end < now() ? 'text-red-600' : 'text-gray-600' }}">
                                {{ $acces->access_end->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($acces->isActive())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Actif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Expiré
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-2">
                                    <div class="h-full bg-yellow-500" style="width: {{ $acces->progression_percentage }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ number_format($acces->progression_percentage, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($acces->isActive())
                            <a href="{{ route('client.elearning.dashboard') }}"
                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-door-open mr-1"></i> Accéder
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
