<!-- Sidebar -->
<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" @keydown.escape="sidebarOpen = false">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-yellow-600 to-yellow-500">
        <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-2">
            <i class="fas fa-crown text-white text-xl"></i>
            <span class="text-white font-bold text-lg tracking-wider">DJOK</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- User profile -->
    <div class="px-4 py-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div
                class="h-10 w-10 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-400 flex items-center justify-center shadow-sm">
                <span class="text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-2 flex-1 overflow-y-auto">
        @php
        $currentRoute = request()->route()->getName();

        // Récupérer les statistiques
        $user = auth()->user();

        // Cache les compteurs pour éviter trop de requêtes
        $badgeCounts = [
        'formations_total' => cache()->remember("user_{$user->id}_formations_total", 60, function() use ($user) {
        return \App\Models\UserFormation::where('user_id', $user->id)->count();
        }),
        'formations_actives' => cache()->remember("user_{$user->id}_formations_active", 60, function() use ($user) {
        return \App\Models\UserFormation::where('user_id', $user->id)
        ->where('status', 'active')
        ->count();
        }),
        'formations_en_attente' => cache()->remember("user_{$user->id}_formations_pending", 60, function() use ($user) {
        return \App\Models\UserFormation::where('user_id', $user->id)
        ->where('status', 'pending')
        ->count();
        }),
        'locations' => cache()->remember("user_{$user->id}_locations", 60, function() use ($user) {
        return \App\Models\LocationReservation::where('user_id', $user->id)
        ->orWhere('email', $user->email)
        ->count();
        }),
        'conciergerie' => cache()->remember("user_{$user->id}_conciergerie", 60, function() use ($user) {
        return \App\Models\ConciergerieDemande::where('email', $user->email)->count();
        }),
        'reservations' => cache()->remember("user_{$user->id}_reservations", 60, function() use ($user) {
        return \App\Models\Reservation::where('user_id', $user->id)
        ->orWhere('email', $user->email)
        ->count();
        }),
        'factures' => cache()->remember("user_{$user->id}_factures", 60, function() use ($user) {
        return \App\Models\Paiement::where('user_id', $user->id)
        ->where('status', 'paid')
        ->count();
        }),
        ];
        @endphp

        <!-- Dashboard -->
        <div class="px-3 py-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Principal</span>
        </div>

        <a href="{{ route('client.dashboard') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.dashboard') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-home mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </div>
        </a>

        <!-- Section Formations -->
        <div class="px-3 py-2 mt-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Formations</span>
                @if($badgeCounts['formations_total'] > 0)
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                    {{ $badgeCounts['formations_total'] }}
                </span>
                @endif
            </div>
        </div>

        <!-- Mes Formations -->
        <a href="{{ route('client.formations.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.formations') && !str_contains($currentRoute, 'catalogue') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-graduation-cap mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Mes Formations</span>
            </div>
            @if($badgeCounts['formations_actives'] > 0)
            <div class="flex items-center space-x-1">
                @if($badgeCounts['formations_actives'] > 0)
                <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $badgeCounts['formations_actives'] }}
                </span>
                @endif
                @if($badgeCounts['formations_en_attente'] > 0)
                <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    {{ $badgeCounts['formations_en_attente'] }}
                </span>
                @endif
            </div>
            @endif
        </a>

        <!-- Catalogue des Formations -->
        @if(Route::has('client.formations.catalogue'))
        <a href="{{ route('client.formations.catalogue') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_contains($currentRoute, 'catalogue') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-book-open mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Catalogue</span>
            </div>
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                Nouveau
            </span>
        </a>
        @endif

        <!-- Section Services -->
        <div class="px-3 py-2 mt-4">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Services</span>
        </div>

        <!-- Locations -->
        <a href="{{ route('client.location-reservations.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.location-reservations') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-car mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Locations</span>
            </div>
            @if($badgeCounts['locations'] > 0)
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $badgeCounts['locations'] }}
            </span>
            @endif
        </a>

        <!-- Conciergerie -->
        <a href="{{ route('client.conciergerie-demandes.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.conciergerie-demandes') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-concierge-bell mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Conciergerie</span>
            </div>
            @if($badgeCounts['conciergerie'] > 0)
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                {{ $badgeCounts['conciergerie'] }}
            </span>
            @endif
        </a>

        <!-- Réservations VTC -->
        <a href="{{ route('client.reservations.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.reservations') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-calendar-check mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Réservations VTC</span>
            </div>
            @if($badgeCounts['reservations'] > 0)
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $badgeCounts['reservations'] }}
            </span>
            @endif
        </a>

        <!-- Section Finance -->
        <div class="px-3 py-2 mt-4">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Finance</span>
        </div>

        <!-- Factures -->
        <a href="{{ route('client.factures.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.factures') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <div class="flex items-center">
                <i class="fas fa-file-invoice mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Factures</span>
            </div>
            @if($badgeCounts['factures'] > 0)
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                {{ $badgeCounts['factures'] }}
            </span>
            @endif
        </a>

        <!-- Séparateur -->
        <div class="mx-3 my-4">
            <div class="border-t border-gray-200"></div>
        </div>

        <!-- Section Compte -->
        <div class="px-3 py-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Compte</span>
        </div>

        <!-- Mon profil -->
        <a href="{{ route('client.profile.edit') }}"
            class="flex items-center px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.profile') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <i class="fas fa-user-circle mr-3 w-5 text-center text-gray-400"></i>
            <span class="text-sm font-medium">Mon profil</span>
        </a>

        <!-- Paramètres -->
        {{-- @if(Route::has('client.settings'))
        <a href="{{ route('client.settings') }}"
            class="flex items-center px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.settings') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <i class="fas fa-cog mr-3 w-5 text-center text-gray-400"></i>
            <span class="text-sm font-medium">Paramètres</span>
        </a>
        @endif --}}

        <!-- Support -->
        <a href="{{ route('client.support') }}"
            class="flex items-center px-3 py-2.5 mx-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ str_starts_with($currentRoute, 'client.support') ? 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500' : '' }}">
            <i class="fas fa-headset mr-3 w-5 text-center text-gray-400"></i>
            <span class="text-sm font-medium">Support</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4 mx-2">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-3 w-5 text-center text-gray-400"></i>
                <span class="text-sm font-medium">Déconnexion</span>
            </button>
        </form>
    </nav>

    <!-- Sidebar footer -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} DJOK PRESTIGE<br>
                <span class="text-xs text-gray-400">v1.0.0</span>
            </p>
        </div>
    </div>
</aside>

<style>
    /* Styles pour améliorer l'apparence */
    aside nav {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f3f4f6;
    }

    aside nav::-webkit-scrollbar {
        width: 6px;
    }

    aside nav::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 3px;
    }

    aside nav::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 3px;
    }

    aside nav::-webkit-scrollbar-thumb:hover {
        background-color: #9ca3af;
    }

    /* Animation douce pour les liens */
    nav a {
        transition: all 0.2s ease-in-out;
    }

    /* Effet de survol amélioré */
    nav a:hover {
        transform: translateX(2px);
    }

    /* Style pour l'élément actif */
    nav a.bg-yellow-50 {
        box-shadow: 0 2px 4px rgba(248, 180, 0, 0.1);
    }

    /* Amélioration du badge "Nouveau" */
    span.bg-blue-100 {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-2px);
        }
    }

    /* Style pour l'avatar utilisateur */
    div.h-10.w-10.rounded-full {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
