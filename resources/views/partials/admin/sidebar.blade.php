<!-- Desktop Sidebar -->
<aside class="z-30 hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
    <div class="flex flex-col w-64 h-full text-white bg-gray-800">
        <!-- Header -->
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-r from-djok-yellow to-yellow-600 hover-lift">
                    <i class="text-xl text-white fas fa-crown"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">DJOK PRESTIGE</h1>
                    <p class="text-sm text-gray-400">Administration</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 overflow-y-auto sidebar-scroll">
            @php
            $currentRoute = request()->route()->getName();
            $pendingCount = \App\Models\Contact::where('status', 'new')->count();
            $pendingInternationalCount = \App\Models\DemandeFormationInternationale::where('statut', 'nouveau')->count();
            $pendingConciergerieCount = \App\Models\ConciergerieDemande::where('statut', 'nouvelle')->count();
            $pendingParticipantsCount = \App\Models\Participant::where('statut', 'en_attente')->count();

            // Compteur articles
            $pendingArticlesCount = \App\Models\Article::where('published', false)->count();
            $draftArticlesCount = \App\Models\Article::where('published', false)->count();
            $publishedArticlesCount = \App\Models\Article::where('published', true)->count();

            // Compteurs newsletter
            $pendingNewsletterCount = \App\Models\NewsletterSubscription::where('status', 'pending')->count();
            $draftCampaignsCount = \App\Models\NewsletterCampaign::where('status', 'draft')->count();
            $scheduledCampaignsCount = \App\Models\NewsletterCampaign::where('status', 'scheduled')->count();

            // Compteur paiements
            $pendingPaiementsCount = \App\Models\Paiement::where('status', 'pending')->count();

            // Compteur réservations en attente
            $pendingReservationsCount = \App\Models\Reservation::where('status', 'pending')->count();

            // Compteur des sessions actives à venir
            $activeSessionsCount = \App\Models\FormationSession::where('is_active', true)
                ->where('start_date', '>=', now())
                ->count();
            @endphp

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>

            <!-- Utilisateurs -->
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>

            <!-- Services -->
            <a href="{{ route('admin.services.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.services') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-concierge-bell"></i>
                <span>Services</span>
            </a>

            <!-- Formations -->
            <a href="{{ route('admin.formations.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.formations') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-graduation-cap"></i>
                <span>Formations</span>
            </a>

            <!-- SESSIONS DE FORMATION - NOUVEAU LIEN -->
            <a href="{{ route('admin.sessions.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.sessions') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-calendar-alt"></i>
                <span class="flex-1">Sessions de formation</span>
                @if($activeSessionsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-green-500 rounded-full min-w-6">
                    {{ $activeSessionsCount }}
                </span>
                @endif
            </a>

            <!-- PAIEMENTS -->
            <a href="{{ route('admin.paiements.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.paiements') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-credit-card"></i>
                <span class="flex-1">Paiements</span>
                @if($pendingPaiementsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingPaiementsCount }}
                </span>
                @endif
            </a>

            <!-- PARTICIPANTS -->
            <a href="{{ route('admin.participants.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.participants') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-user-graduate"></i>
                <span class="flex-1">Participants Formation</span>
                @if($pendingParticipantsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingParticipantsCount }}
                </span>
                @endif
            </a>

            <!-- ============================================ -->
            <!-- ARTICLES DU BLOG - SECTION -->
            <!-- ============================================ -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Blog</p>

                <!-- Articles -->
                <a href="{{ route('admin.articles.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.articles') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-newspaper"></i>
                    <span class="flex-1">Articles</span>
                    @if($pendingArticlesCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingArticlesCount }}
                    </span>
                    @endif
                </a>

                <!-- Nouvel article -->
                <a href="{{ route('admin.articles.create') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.articles.create') ? 'bg-gray-700' : '' }} mb-2 bg-green-900 bg-opacity-30 hover:bg-green-800">
                    <i class="w-5 mr-3 text-center fas fa-plus-circle"></i>
                    <span>Nouvel article</span>
                </a>
            </div>
            <!-- ============================================ -->

            <!-- Formation Internationale -->
            <a href="{{ route('admin.demandes-formation-internationale.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.demandes-formation-internationale') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-globe-americas"></i>
                <span class="flex-1">Formation Internationale</span>
                @if($pendingInternationalCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingInternationalCount }}
                </span>
                @endif
            </a>

            <!-- Conciergerie -->
            <a href="{{ route('admin.conciergerie-demandes.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.conciergerie-demandes') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-concierge-bell"></i>
                <span class="flex-1">Conciergerie</span>
                @if($pendingConciergerieCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingConciergerieCount }}
                </span>
                @endif
            </a>

            <!-- SECTION VÉHICULES -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Véhicules</p>

                <!-- Véhicules -->
                <a href="{{ route('admin.vehicles.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.vehicles') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-car"></i>
                    <span>Véhicules</span>
                </a>

                <!-- Catégories de véhicules -->
                <a href="{{ route('admin.vehicle-categories.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.vehicle-categories') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-tags"></i>
                    <span>Catégories véhicules</span>
                </a>

                <!-- Réservations Location -->
                <a href="{{ route('admin.location-reservations.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.location-reservations') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-calendar-check"></i>
                    <span>Réservations Location</span>
                </a>

                <!-- RÉSERVATIONS VTC -->
                <a href="{{ route('admin.reservations.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.reservations') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-taxi"></i>
                    <span class="flex-1">Réservations VTC</span>
                    @if($pendingReservationsCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingReservationsCount }}
                    </span>
                    @endif
                </a>
            </div>

            <!-- Section E-learning -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">E-learning</p>

                <!-- Dashboard E-learning -->
                <a href="{{ route('admin.elearning.dashboard') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-laptop-house"></i>
                    <span>Dashboard E-learning</span>
                </a>

                <!-- Forfaits E-learning -->
                <a href="{{ route('admin.elearning.forfaits') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.forfaits') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-gift"></i>
                    <span>Forfaits</span>
                </a>

                <!-- Accès E-learning -->
                <a href="{{ route('admin.elearning.acces') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.acces') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-users"></i>
                    <span>Accès</span>
                </a>

                <!-- Cours E-learning -->
                <a href="{{ route('admin.elearning.cours') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.cours') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-book"></i>
                    <span>Cours</span>
                </a>

                <!-- QCM E-learning -->
                <a href="{{ route('admin.elearning.qcms') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.qcms') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-question-circle"></i>
                    <span>QCM</span>
                </a>

                <!-- SESSIONS ACTIVES E-LEARNING -->
                <a href="{{ route('admin.elearning.sessions.active') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.sessions') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-user-clock"></i>
                    <span>Sessions actives</span>
                </a>
            </div>

            <!-- NEWSLETTER - SECTION -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Newsletter</p>

                <!-- Abonnés -->
                <a href="{{ route('admin.newsletter.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-users"></i>
                    <span class="flex-1">Abonnés</span>
                    @if($pendingNewsletterCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingNewsletterCount }}
                    </span>
                    @endif
                </a>

                <!-- Campagnes -->
                <a href="{{ route('admin.newsletter.campaigns') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.campaigns') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-paper-plane"></i>
                    <span class="flex-1">Campagnes</span>
                    @if($draftCampaignsCount > 0 || $scheduledCampaignsCount > 0)
                    <div class="flex space-x-1">
                        @if($draftCampaignsCount > 0)
                        <span class="px-2 py-1 text-xs text-center text-white bg-blue-500 rounded-full min-w-6"
                            title="Brouillons">
                            {{ $draftCampaignsCount }}
                        </span>
                        @endif
                        @if($scheduledCampaignsCount > 0)
                        <span class="px-2 py-1 text-xs text-center text-white bg-orange-500 rounded-full min-w-6"
                            title="Planifiées">
                            {{ $scheduledCampaignsCount }}
                        </span>
                        @endif
                    </div>
                    @endif
                </a>

                <!-- Statistiques Newsletter -->
                <a href="{{ route('admin.newsletter.statistics') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.statistics') ? 'bg-gray-700' : '' }} mb-2">
                    <i class="w-5 mr-3 text-center fas fa-chart-bar"></i>
                    <span>Statistiques Newsletter</span>
                </a>

                <!-- Nouvelle campagne -->
                <a href="{{ route('admin.newsletter.campaigns.create') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.campaigns.create') ? 'bg-gray-700' : '' }} mb-2 bg-green-900 bg-opacity-30 hover:bg-green-800">
                    <i class="w-5 mr-3 text-center fas fa-plus-circle"></i>
                    <span>Nouvelle campagne</span>
                </a>
            </div>

            <!-- Messages -->
            <a href="{{ route('admin.contacts.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.contacts') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-envelope"></i>
                <span class="flex-1">Messages</span>
                @if($pendingCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-red-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingCount }}
                </span>
                @endif
            </a>

            <!-- Statistiques -->
            <a href="{{ route('admin.statistics') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.statistics') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-chart-line"></i>
                <span>Statistiques</span>
            </a>

            <!-- Paramètres / Mon Profil -->
            <a href="{{ route('admin.profile.edit') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.profile.*') ? 'bg-gray-700' : '' }} mb-2">
                <i class="w-5 mr-3 text-center fas fa-user-circle"></i>
                <span>Mon Profil</span>
            </a>

            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-3 text-left text-red-300 rounded-lg hover:bg-gray-700">
                    <i class="w-5 mr-3 text-center fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}"
                class="flex items-center px-4 py-3 text-blue-300 rounded-lg hover:bg-gray-700">
                <i class="w-5 mr-3 text-center fas fa-arrow-left"></i>
                <span>Retour au site</span>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
    @click="sidebarOpen = false">
</div>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" class="fixed inset-0 z-40 flex md:hidden" x-cloak>

    <div class="relative flex flex-col flex-1 w-full max-w-xs bg-gray-900">
        <!-- Close Button -->
        <div class="absolute top-4 right-4">
            <button @click="sidebarOpen = false"
                class="p-2 text-gray-400 rounded-md hover:text-white hover:bg-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Logo Mobile -->
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-djok-yellow">
                    <i class="text-xl text-white fas fa-crown"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">DJOK PRESTIGE</h1>
                    <p class="text-xs text-gray-400">Administration</p>
                </div>
            </div>
        </div>

        <!-- Navigation Mobile -->
        <nav class="flex-1 p-4 overflow-y-auto">
            @php
            $currentRoute = request()->route()->getName();
            $pendingCount = \App\Models\Contact::where('status', 'new')->count();
            $pendingInternationalCount = \App\Models\DemandeFormationInternationale::where('statut', 'nouveau')->count();
            $pendingConciergerieCount = \App\Models\ConciergerieDemande::where('statut', 'nouvelle')->count();
            $pendingParticipantsCount = \App\Models\Participant::where('statut', 'en_attente')->count();

            // Compteur articles pour mobile
            $pendingArticlesCount = \App\Models\Article::where('published', false)->count();

            // Compteurs newsletter pour mobile
            $pendingNewsletterCount = \App\Models\NewsletterSubscription::where('status', 'pending')->count();
            $draftCampaignsCount = \App\Models\NewsletterCampaign::where('status', 'draft')->count();
            $scheduledCampaignsCount = \App\Models\NewsletterCampaign::where('status', 'scheduled')->count();

            // Compteur paiements pour mobile
            $pendingPaiementsCount = \App\Models\Paiement::where('status', 'pending')->count();

            // Compteur réservations pour mobile
            $pendingReservationsCount = \App\Models\Reservation::where('status', 'pending')->count();

            // Compteur des sessions actives à venir pour mobile
            $activeSessionsCount = \App\Models\FormationSession::where('is_active', true)
                ->where('start_date', '>=', now())
                ->count();
            @endphp

            <!-- Dashboard Mobile -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>

            <!-- Utilisateurs Mobile -->
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>

            <!-- Services Mobile -->
            <a href="{{ route('admin.services.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.services') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-concierge-bell"></i>
                <span>Services</span>
            </a>

            <!-- Formations Mobile -->
            <a href="{{ route('admin.formations.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.formations') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-graduation-cap"></i>
                <span>Formations</span>
            </a>

            <!-- SESSIONS DE FORMATION MOBILE - NOUVEAU LIEN -->
            <a href="{{ route('admin.sessions.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.sessions') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-calendar-alt"></i>
                <span class="flex-1">Sessions de formation</span>
                @if($activeSessionsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-green-500 rounded-full min-w-6">
                    {{ $activeSessionsCount }}
                </span>
                @endif
            </a>

            <!-- PAIEMENTS MOBILE -->
            <a href="{{ route('admin.paiements.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.paiements') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-credit-card"></i>
                <span class="flex-1">Paiements</span>
                @if($pendingPaiementsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingPaiementsCount }}
                </span>
                @endif
            </a>

            <!-- PARTICIPANTS MOBILE -->
            <a href="{{ route('admin.participants.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.participants') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-user-graduate"></i>
                <span class="flex-1">Participants Formation</span>
                @if($pendingParticipantsCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingParticipantsCount }}
                </span>
                @endif
            </a>

            <!-- ============================================ -->
            <!-- ARTICLES DU BLOG MOBILE - SECTION -->
            <!-- ============================================ -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Blog</p>

                <!-- Articles Mobile -->
                <a href="{{ route('admin.articles.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.articles') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-newspaper"></i>
                    <span class="flex-1">Articles</span>
                    @if($pendingArticlesCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingArticlesCount }}
                    </span>
                    @endif
                </a>

                <!-- Nouvel article Mobile -->
                <a href="{{ route('admin.articles.create') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.articles.create') ? 'bg-gray-700' : '' }} mb-2 bg-green-900 bg-opacity-30 hover:bg-green-800"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-plus-circle"></i>
                    <span>Nouvel article</span>
                </a>
            </div>
            <!-- ============================================ -->

            <!-- Formation Internationale Mobile -->
            <a href="{{ route('admin.demandes-formation-internationale.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.demandes-formation-internationale') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-globe-americas"></i>
                <span class="flex-1">Formation Internationale</span>
                @if($pendingInternationalCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingInternationalCount }}
                </span>
                @endif
            </a>

            <!-- Conciergerie Mobile -->
            <a href="{{ route('admin.conciergerie-demandes.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.conciergerie-demandes') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-concierge-bell"></i>
                <span class="flex-1">Conciergerie</span>
                @if($pendingConciergerieCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingConciergerieCount }}
                </span>
                @endif
            </a>

            <!-- SECTION VÉHICULES MOBILE -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Véhicules</p>

                <!-- Véhicules Mobile -->
                <a href="{{ route('admin.vehicles.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.vehicles') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-car"></i>
                    <span>Véhicules</span>
                </a>

                <!-- Catégories de véhicules Mobile -->
                <a href="{{ route('admin.vehicle-categories.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.vehicle-categories') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-tags"></i>
                    <span>Catégories véhicules</span>
                </a>

                <!-- Réservations Location Mobile -->
                <a href="{{ route('admin.location-reservations.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.location-reservations') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-calendar-check"></i>
                    <span>Réservations Location</span>
                </a>

                <!-- RÉSERVATIONS VTC MOBILE -->
                <a href="{{ route('admin.reservations.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.reservations') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-taxi"></i>
                    <span class="flex-1">Réservations VTC</span>
                    @if($pendingReservationsCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingReservationsCount }}
                    </span>
                    @endif
                </a>
            </div>

            <!-- Section E-learning Mobile -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">E-learning</p>

                <!-- Dashboard E-learning Mobile -->
                <a href="{{ route('admin.elearning.dashboard') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-laptop-house"></i>
                    <span>Dashboard E-learning</span>
                </a>

                <!-- Forfaits E-learning Mobile -->
                <a href="{{ route('admin.elearning.forfaits') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.forfaits') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-gift"></i>
                    <span>Forfaits</span>
                </a>

                <!-- Accès E-learning Mobile -->
                <a href="{{ route('admin.elearning.acces') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.acces') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-users"></i>
                    <span>Accès</span>
                </a>

                <!-- Cours E-learning Mobile -->
                <a href="{{ route('admin.elearning.cours') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.cours') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-book"></i>
                    <span>Cours</span>
                </a>

                <!-- QCM E-learning Mobile -->
                <a href="{{ route('admin.elearning.qcms') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.qcms') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-question-circle"></i>
                    <span>QCM</span>
                </a>

                <!-- SESSIONS ACTIVES E-LEARNING MOBILE -->
                <a href="{{ route('admin.elearning.sessions.active') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.elearning.sessions') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-user-clock"></i>
                    <span>Sessions actives</span>
                </a>
            </div>

            <!-- NEWSLETTER MOBILE - SECTION -->
            <div class="pt-4 mt-4 mb-4 border-t border-gray-700">
                <p class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Newsletter</p>

                <!-- Abonnés Mobile -->
                <a href="{{ route('admin.newsletter.index') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-users"></i>
                    <span class="flex-1">Abonnés</span>
                    @if($pendingNewsletterCount > 0)
                    <span class="px-2 py-1 text-xs text-center text-white bg-yellow-500 rounded-full min-w-6 animate-pulse">
                        {{ $pendingNewsletterCount }}
                    </span>
                    @endif
                </a>

                <!-- Campagnes Mobile -->
                <a href="{{ route('admin.newsletter.campaigns') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.campaigns') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-paper-plane"></i>
                    <span class="flex-1">Campagnes</span>
                    @if($draftCampaignsCount > 0 || $scheduledCampaignsCount > 0)
                    <div class="flex space-x-1">
                        @if($draftCampaignsCount > 0)
                        <span class="px-2 py-1 text-xs text-center text-white bg-blue-500 rounded-full min-w-6"
                            title="Brouillons">
                            {{ $draftCampaignsCount }}
                        </span>
                        @endif
                        @if($scheduledCampaignsCount > 0)
                        <span class="px-2 py-1 text-xs text-center text-white bg-orange-500 rounded-full min-w-6"
                            title="Planifiées">
                            {{ $scheduledCampaignsCount }}
                        </span>
                        @endif
                    </div>
                    @endif
                </a>

                <!-- Statistiques Newsletter Mobile -->
                <a href="{{ route('admin.newsletter.statistics') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.statistics') ? 'bg-gray-700' : '' }} mb-2"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-chart-bar"></i>
                    <span>Statistiques Newsletter</span>
                </a>

                <!-- Nouvelle campagne Mobile -->
                <a href="{{ route('admin.newsletter.campaigns.create') }}"
                    class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.newsletter.campaigns.create') ? 'bg-gray-700' : '' }} mb-2 bg-green-900 bg-opacity-30 hover:bg-green-800"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-plus-circle"></i>
                    <span>Nouvelle campagne</span>
                </a>
            </div>

            <!-- Messages Mobile -->
            <a href="{{ route('admin.contacts.index') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.contacts') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-envelope"></i>
                <span class="flex-1">Messages</span>
                @if($pendingCount > 0)
                <span class="px-2 py-1 text-xs text-center text-white bg-red-500 rounded-full min-w-6 animate-pulse">
                    {{ $pendingCount }}
                </span>
                @endif
            </a>

            <!-- Statistiques Mobile -->
            <a href="{{ route('admin.statistics') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ str_starts_with($currentRoute, 'admin.statistics') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-chart-line"></i>
                <span>Statistiques</span>
            </a>

            <!-- Mon Profil Mobile -->
            <a href="{{ route('admin.profile.edit') }}"
                class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.profile.*') ? 'bg-gray-700' : '' }} mb-2"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-user-circle"></i>
                <span>Mon Profil</span>
            </a>

            <!-- Déconnexion mobile -->
            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-3 text-left text-red-300 rounded-lg hover:bg-gray-700"
                    @click="sidebarOpen = false">
                    <i class="w-5 mr-3 text-center fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>

        <!-- Footer mobile -->
        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-blue-300 rounded-lg hover:bg-gray-700"
                @click="sidebarOpen = false">
                <i class="w-5 mr-3 text-center fas fa-arrow-left"></i>
                <span>Retour au site</span>
            </a>
        </div>
    </div>
</div>
