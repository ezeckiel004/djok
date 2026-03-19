<!-- resources/views/partials/client/navbar.blade.php -->

<!-- Navbar -->
<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Page title -->
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500 hidden md:block">
                        @yield('page-description', 'Bienvenue dans votre espace client')
                    </p>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Search (desktop) -->
                <div class="hidden md:block relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Rechercher..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-djok-yellow focus:border-djok-yellow">
                </div>

                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-900 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">
                            3
                        </span>
                    </button>

                    <!-- Notifications dropdown -->
                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-2">
                            <div class="px-4 py-2 border-b">
                                <h3 class="font-semibold text-gray-900">Notifications</h3>
                            </div>

                            <!-- Notification items -->
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-car text-djok-yellow mt-1"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Nouvelle réservation</p>
                                        <p class="text-xs text-gray-500">Votre location a été confirmée</p>
                                        <p class="text-xs text-gray-400 mt-1">Il y a 2 heures</p>
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-graduation-cap text-blue-500 mt-1"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Formation disponible</p>
                                        <p class="text-xs text-gray-500">Accédez à votre nouvelle formation</p>
                                        <p class="text-xs text-gray-400 mt-1">Il y a 1 jour</p>
                                    </div>
                                </div>
                            </a>

                            <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-file-invoice-dollar text-green-500 mt-1"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Facture disponible</p>
                                        <p class="text-xs text-gray-500">Téléchargez votre dernière facture</p>
                                        <p class="text-xs text-gray-400 mt-1">Il y a 3 jours</p>
                                    </div>
                                </div>
                            </a>

                            <div class="px-4 py-2 border-t">
                                <a href="#" class="text-sm text-djok-yellow hover:text-yellow-700 font-medium">
                                    Voir toutes les notifications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                        <div class="h-8 w-8 rounded-full bg-djok-yellow flex items-center justify-center">
                            <span class="text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="{{ route('client.profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i>Mon profil
                            </a>
                            {{-- <a href="{{ route('client.settings') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>Paramètres
                            </a> --}}
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile search -->
<div class="md:hidden px-4 py-3 border-b bg-gray-50">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
        <input type="text" placeholder="Rechercher..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-djok-yellow focus:border-djok-yellow">
    </div>
</div>
