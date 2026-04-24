<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" x-init="
        $watch('sidebarOpen', value => {
            if (value && window.innerWidth < 1024) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Fermer la sidebar si on redimensionne en mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebarOpen = true;
            } else {
                sidebarOpen = false;
            }
        });
    ">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Espace Client DJOK PRESTIGE</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'djok-yellow': '#f59e0b',
                        'djok-dark': '#1f2937',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js pour les interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Styles personnalisés pour client */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        /* Animation pour les cartes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Badge styles */
        .badge-success {
            @apply bg-green-100 text-green-800;
        }

        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }

        .badge-info {
            @apply bg-blue-100 text-blue-800;
        }

        .badge-danger {
            @apply bg-red-100 text-red-800;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full">
    <!-- Overlay pour mobile -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    @include('partials.client.sidebar')

    <!-- Main content -->
    <div class="lg:pl-64">
        <!-- Navbar -->
        @include('partials.client.navbar')

        <!-- Main content area -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('client.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-djok-yellow">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
                @endif

                <!-- Page header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="mt-2 text-sm text-gray-600">@yield('page-description', 'Bienvenue dans votre espace client')</p>
                </div>

                <!-- Session messages -->
                @if(session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('info'))
                <div class="rounded-md bg-blue-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Main content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} DJOK PRESTIGE. Tous droits réservés.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="{{ route('contact') }}" class="text-gray-500 hover:text-gray-900 text-sm">
                            <i class="fas fa-headset mr-1"></i> Support
                        </a>
                        <a href="{{ route('cgu') }}" class="text-gray-500 hover:text-gray-900 text-sm">
                            <i class="fas fa-file-contract mr-1"></i> CGU
                        </a>
                        <a href="{{ route('rgpd') }}" class="text-gray-500 hover:text-gray-900 text-sm">
                            <i class="fas fa-shield-alt mr-1"></i> RGPD
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toggle button pour mobile -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="fixed bottom-4 right-4 z-30 lg:hidden h-12 w-12 rounded-full bg-yellow-500 text-white shadow-lg flex items-center justify-center hover:bg-yellow-600 transition-colors">
        <i class="fas fa-bars" x-show="!sidebarOpen"></i>
        <i class="fas fa-times" x-show="sidebarOpen"></i>
    </button>

    <script>
        // Fermer la sidebar quand on clique sur un lien (mobile)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready - Sidebar initialisation');
            document.querySelectorAll('aside nav a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        const event = new CustomEvent('close-sidebar');
                        document.dispatchEvent(event);
                    }
                });
            });

            // Écouter l'événement de fermeture de sidebar
            document.addEventListener('close-sidebar', () => {
                if (window.innerWidth < 1024) {
                    if (typeof sidebarOpen !== 'undefined') {
                        sidebarOpen = false;
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
