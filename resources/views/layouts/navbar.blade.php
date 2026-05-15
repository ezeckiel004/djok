<!-- resources/views/layouts/navbar.blade.php -->
<header class="header">
    <!-- Top -->
    <div class="header-top">
        <div class="phone">
            <i class="fa-solid fa-phone"></i> {{ __('navbar.phone') }}
        </div>
        <div class="social-top">
            <i class="fa-brands fa-facebook-f"></i>
            <i class="fa-brands fa-instagram"></i>
            <i class="fa-brands fa-snapchat"></i>
            <span class="custom-x-icon">𝕏</span>
            <i class="fa-brands fa-tiktok"></i>

            <!-- Sélecteur de langue -->
            {{-- <div class="language-selector">
                <form action="{{ route('language.switch') }}" method="POST" id="language-form">
                    @csrf
                    <select name="locale" id="language-switcher" class="language-select">
                        <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>
                            🇫🇷 FR
                        </option>
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                            🇬🇧 EN
                        </option>
                        <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>
                            🇪🇸 ES
                        </option>
                    </select>
                    <i class="fa-solid fa-chevron-down language-selector-icon"></i>
                </form>
            </div> --}}
        </div>
    </div>

    <!-- Logo -->
    <div class="logo-container">
        <div class="logo">
            <img src="{{ asset('DP2.webp') }}" alt="DJOK PRESTIGE">
        </div>
    </div>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav">
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            {{ __('navbar.home') }}
        </a>
        <a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">
            {{ __('navbar.about') }}
        </a>

        <!-- Formations Dropdown -->
        <div class="dropdown">
            <a href="#" class="dropdown-toggle {{ request()->is('formation*') ? 'active' : '' }}">
                {{ __('navbar.training') }} <i class="fa-solid fa-chevron-down ml-1"></i>
            </a>
            <div class="dropdown-menu">
                <a href="{{ route('formation') }}" class="dropdown-item">
                    {{ __('navbar.vtc_training') }}
                </a>
                <a href="{{ route('formation.international') }}" class="dropdown-item">
                    {{ __('navbar.international_training') }}
                </a>
                <a href="{{ route('elearning.index') }}" class="dropdown-item">
                    {{ __('navbar.elearning_training') }}
                </a>
            </div>
        </div>

        <!-- Services directs dans le menu -->
        <a href="{{ route('reservation') }}" class="{{ request()->is('reservation') ? 'active' : '' }}">
            {{ __('navbar.vtc_transport') }}
        </a>
        <a href="{{ route('location') }}" class="{{ request()->is('location') ? 'active' : '' }}">
            {{ __('navbar.car_rental') }}
        </a>
        <a href="{{ route('conciergerie') }}" class="{{ request()->is('conciergerie') ? 'active' : '' }}">
            {{ __('navbar.concierge') }}
        </a>

        <a href="{{ route('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">
            {{ __('navbar.contact') }}
        </a>
        <a href="{{ route('blog') }}" class="{{ request()->is('blog*') ? 'active' : '' }}">
            {{ __('navbar.blog') }}
        </a>
    </nav>

    <!-- Auth Section - CORRIGÉ -->
    <div class="auth-section">
        @if (Route::has('login'))
        <div class="auth-links">
            @auth
            {{-- CORRECTION: Utilisation de la route correcte pour le dashboard client --}}
            <a href="{{ route('client.dashboard') }}" class="dashboard-link">
                <i class="fa-solid fa-tachometer-alt"></i> {{ __('navbar.dashboard') }}
            </a>
            @else
            @if (Route::has('register'))
            <a href="{{ route('espaceclient') }}" class="client-btn">
                <i class="fa-solid fa-user-shield"></i> {{ __('navbar.client_space') }}
            </a>
            @endif
            @endauth
        </div>
        @endif
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="{{ __('navbar.mobile_menu') }}">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <!-- Social Icons in Mobile Menu -->
            <div class="mobile-social-top">
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-instagram"></i>
                <i class="fa-brands fa-snapchat"></i>
                <span class="custom-x-icon">𝕏</span>
                <i class="fa-brands fa-tiktok"></i>
            </div>

            <!-- Sélecteur de langue mobile -->
            {{-- <div class="mobile-language-selector">
                <form action="{{ route('language.switch') }}" method="POST">
                    @csrf
                    <select name="locale" class="language-select-mobile">
                        <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>
                            🇫🇷 Français
                        </option>
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                            🇬🇧 English
                        </option>
                        <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>
                            🇪🇸 Español
                        </option>
                    </select>
                </form>
            </div> --}}

            <a href="{{ url('/') }}" class="mobile-link">{{ __('navbar.home') }}</a>
            <a href="{{ route('about') }}" class="mobile-link">{{ __('navbar.about') }}</a>

            <!-- Formations Mobile -->
            <div class="mobile-dropdown">
                <button class="mobile-dropdown-btn" data-target="formations-dropdown">
                    {{ __('navbar.training') }} <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-content" id="formations-dropdown">
                    <a href="{{ route('formation') }}" class="mobile-sub-link">
                        {{ __('navbar.vtc_training') }}
                    </a>
                    <a href="{{ route('formation.international') }}" class="mobile-sub-link">
                        {{ __('navbar.international_training') }}
                    </a>
                    <a href="{{ route('elearning.index') }}" class="mobile-sub-link">
                        {{ __('navbar.elearning_training') }}
                    </a>
                </div>
            </div>

            <!-- Services directs dans le menu mobile -->
            <a href="{{ route('reservation') }}" class="mobile-link">{{ __('navbar.vtc_transport') }}</a>
            <a href="{{ route('location') }}" class="mobile-link">{{ __('navbar.car_rental') }}</a>
            <a href="{{ route('conciergerie') }}" class="mobile-link">{{ __('navbar.concierge') }}</a>

            <a href="{{ route('contact') }}" class="mobile-link">{{ __('navbar.contact') }}</a>
            <a href="{{ route('blog') }}" class="mobile-link">{{ __('navbar.blog') }}</a>

            <!-- Auth Mobile - CORRIGÉ -->
            <div class="mobile-auth">
                @auth
                {{-- CORRECTION: Utilisation de la route correcte pour le dashboard client --}}
                <a href="{{ route('client.dashboard') }}" class="mobile-dashboard">
                    <i class="fa-solid fa-tachometer-alt"></i> {{ __('navbar.dashboard') }}
                </a>
                @else
                <a href="{{ route('espaceclient') }}" class="mobile-client-btn">
                    <i class="fa-solid fa-user-shield"></i> {{ __('navbar.client_space') }}
                </a>
                @endauth
            </div>

            <!-- Contact Mobile -->
            <div class="mobile-contact">
                <div class="mobile-phone">
                    <i class="fa-solid fa-phone text-black-600"></i> {{ __('navbar.phone') }}
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Bannière défilante (SÉPARÉE du header pour éviter toute collision) -->
<div class="scrolling-banner-container">
    <div class="scrolling-banner">
        <div class="scrolling-content">
            <span>{{ __('banner.scrolling_text') }}</span>
            <span>{{ __('banner.scrolling_text') }}</span>
            <span>{{ __('banner.scrolling_text') }}</span>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Montserrat", sans-serif;
    }

    /* Header */
    .header {
        background: #000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        padding: 15px 50px;
        position: relative;
        width: 100%;
        z-index: 1000;
        /* Supprime le margin-bottom pour laisser la bannière en dessous */
    }

    /* Bannière défilante - SÉPARÉE COMPLÈTEMENT du header */
    .scrolling-banner-container {
        width: 100%;
        position: relative;
        z-index: 998;
        /* Plus bas que le header */
        margin-top: 0;
        /* Collée en dessous du header */
    }

    .scrolling-banner {
        background: #e6b85c;
        color: #000;
        height: 32px;
        /* Hauteur légèrement augmentée pour meilleure lisibilité */
        overflow: hidden;
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        /* Aucun positionnement relatif ou absolu qui pourrait causer des conflits */
    }

    .scrolling-content {
        display: flex;
        white-space: nowrap;
        animation: scroll-left 25s linear infinite;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.3px;
        padding: 0 20px;
        align-items: center;
    }

    .scrolling-content span {
        padding-right: 40px;
        display: inline-block;
        line-height: 1;
    }

    @keyframes scroll-left {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-33.33%);
        }
    }

    /* Pause l'animation au survol */
    .scrolling-banner:hover .scrolling-content {
        animation-play-state: paused;
    }

    /* Header Top */
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .phone {
        background: #caa24d;
        color: #000;
        padding: 8px 15px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Social Icons Top */
    .social-top {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .social-top i,
    .social-top .custom-x-icon {
        color: #caa24d;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
    }

    .social-top i:hover,
    .social-top .custom-x-icon:hover {
        opacity: 0.7;
        transform: translateY(-2px);
    }

    /* X personnalisé - Style élégant */
    .custom-x-icon {
        font-family: "Segoe UI", "Arial", sans-serif;
        font-weight: 600;
        font-size: 18px !important;
        color: #caa24d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        line-height: 1;
        transform: translateY(-1px);
    }

    /* Sélecteur de langue */
    .language-selector {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-left: 10px;
    }

    .language-select {
        background: transparent;
        color: #caa24d;
        border: 1px solid #caa24d;
        border-radius: 4px;
        padding: 6px 30px 6px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
        min-width: 80px;
        transition: all 0.3s ease;
    }

    .language-select:hover {
        background: rgba(202, 162, 77, 0.1);
    }

    .language-select:focus {
        box-shadow: 0 0 0 2px rgba(202, 162, 77, 0.3);
    }

    .language-select option {
        background: #000;
        color: #fff;
        padding: 10px;
    }

    .language-selector-icon {
        position: absolute;
        right: 10px;
        color: #caa24d;
        font-size: 10px;
        pointer-events: none;
    }

    /* Mobile Social Icons */
    .mobile-social-top {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mobile-social-top i,
    .mobile-social-top .custom-x-icon {
        color: #caa24d;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .mobile-social-top i:hover,
    .mobile-social-top .custom-x-icon:hover {
        opacity: 0.7;
    }

    /* X personnalisé pour mobile */
    .mobile-social-top .custom-x-icon {
        font-size: 22px !important;
        font-weight: 600;
    }

    /* Sélecteur de langue mobile */
    .mobile-language-selector {
        width: 100%;
        margin-bottom: 20px;
        text-align: center;
    }

    .mobile-language-selector select {
        width: 100%;
        max-width: 200px;
        margin: 0 auto;
        background: transparent;
        color: #caa24d;
        border: 1px solid #caa24d;
        border-radius: 4px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
    }

    .mobile-language-selector select option {
        background: #000;
        color: #fff;
        padding: 10px;
    }

    /* Logo Container */
    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 15px 0;
        margin-top: -50px;
        width: 100%;
    }

    /* Logo */
    .logo {
        text-align: center;
    }

    .logo img {
        max-width: 180px;
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* Desktop Navigation */
    .desktop-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 25px;
        /* Réduit de 35px à 25px pour accommoder plus d'éléments */
        margin-top: 10px;
        position: relative;
        z-index: 1001;
        flex-wrap: wrap;
        /* Permet le retour à la ligne sur petits écrans */
    }

    .desktop-nav a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        padding-bottom: 6px;
        position: relative;
        transition: color 0.3s ease;
        white-space: nowrap;
    }

    .desktop-nav a.active,
    .desktop-nav a:hover {
        color: #caa24d;
    }

    .desktop-nav a.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 100%;
        height: 2px;
        background: #caa24d;
    }

    /* Desktop Dropdown */
    .dropdown {
        position: relative;
        z-index: 1002;
    }

    .dropdown-toggle {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
        z-index: 1003;
    }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: -20px;
        background: #000;
        border: 1px solid #caa24d;
        border-radius: 4px;
        min-width: 220px;
        z-index: 9999;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        padding: 8px 0;
    }

    .dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    .dropdown-menu:hover {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }

    .dropdown-item {
        display: block;
        padding: 12px 20px;
        font-size: 13px;
        color: #fff !important;
        text-decoration: none;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: #000 !important;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: #caa24d !important;
        color: #000 !important;
        padding-left: 25px;
    }

    /* Auth Section - Position bien en bas */
    .auth-section {
        position: absolute;
        right: 50px;
        bottom: 20px;
        /* Position basse pour aligner avec le bas du header */
        display: flex;
        align-items: center;
        z-index: 1001;
    }

    .auth-links {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .dashboard-link {
        color: #caa24d;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border: 1px solid #caa24d;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .dashboard-link:hover {
        background: rgba(202, 162, 77, 0.1);
    }

    .client-btn {
        background: #caa24d;
        color: #000;
        padding: 8px 15px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }

    .client-btn:hover {
        background: #e6b85c;
        transform: translateY(-2px);
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        color: #caa24d;
        cursor: pointer;
        z-index: 10000;
        padding: 10px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        display: none;
    }

    .mobile-menu-btn:hover {
        background: rgba(202, 162, 77, 0.1);
        border-radius: 4px;
    }

    .mobile-menu-btn i {
        font-size: 24px;
    }

    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.98);
        z-index: 9999;
        overflow-y: auto;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-menu.active {
        display: block;
        opacity: 1;
    }

    .mobile-menu-content {
        padding: 100px 20px 40px;
        min-height: 100%;
        overflow-y: auto;
    }

    .mobile-link {
        display: block;
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .mobile-link:hover {
        color: #caa24d;
    }

    /* Mobile Dropdown */
    .mobile-dropdown {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mobile-dropdown-btn {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        padding: 15px 0;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .mobile-dropdown-btn:hover {
        color: #caa24d;
    }

    .mobile-dropdown-btn i {
        transition: transform 0.3s ease;
    }

    .mobile-dropdown-btn.active i {
        transform: rotate(180deg);
    }

    .mobile-dropdown-content {
        padding-left: 20px;
        display: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .mobile-dropdown-content.active {
        display: block;
        max-height: 300px;
    }

    .mobile-sub-link {
        display: block;
        color: #ccc !important;
        text-decoration: none;
        font-size: 14px;
        padding: 12px 0;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .mobile-sub-link:last-child {
        border-bottom: none;
    }

    .mobile-sub-link:hover {
        color: #caa24d !important;
        padding-left: 10px;
    }

    /* Mobile Auth */
    .mobile-auth {
        margin-top: 30px;
        padding: 20px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .mobile-dashboard {
        display: block;
        color: #caa24d;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        padding: 15px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #caa24d;
        border-radius: 4px;
        padding: 15px;
        text-align: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .mobile-client-btn {
        display: block;
        background: #caa24d;
        color: #000;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        padding: 15px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* Mobile Contact */
    .mobile-contact {
        margin-top: 30px;
    }

    .mobile-phone {
        background: #caa24d;
        color: #000;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .desktop-nav {
            gap: 22px;
        }
    }

    @media (max-width: 1024px) {
        .header {
            padding: 15px 30px;
        }

        .auth-section {
            right: 30px;
            bottom: 18px;
        }

        .desktop-nav {
            gap: 18px;
        }

        .desktop-nav a {
            font-size: 12px;
        }

        .scrolling-content {
            font-size: 12px;
        }

        .scrolling-banner {
            height: 30px;
        }
    }

    @media (max-width: 900px) {
        .desktop-nav {
            gap: 15px;
        }

        .desktop-nav a {
            font-size: 11px;
        }
    }

    @media (max-width: 768px) {

        .desktop-nav,
        .auth-section,
        .language-selector {
            display: none;
        }

        /* CACHER LES ICÔNES SOCIALES DANS LE HEADER-TOP SUR MOBILE */
        .social-top {
            display: none;
        }

        .mobile-menu-btn {
            display: flex;
        }

        .header {
            padding: 15px;
            margin-bottom: 0;
        }

        .logo-container {
            margin-top: 10px;
        }

        .logo img {
            max-width: 140px;
        }

        .header-top {
            justify-content: flex-start;
        }

        .mobile-menu-content {
            padding: 100px 20px 30px;
        }

        .mobile-social-top {
            display: flex;
        }

        .mobile-language-selector {
            display: block;
        }

        /* Afficher la bannière sur mobile */
        .scrolling-banner {
            height: 28px;
        }

        .scrolling-content {
            font-size: 11px;
            animation-duration: 20s;
        }
    }

    @media (max-width: 640px) {
        .header {
            padding: 12px;
        }

        .logo img {
            max-width: 120px;
        }

        .phone {
            font-size: 12px;
            padding: 8px 15px;
        }

        .mobile-menu-btn {
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
        }

        .mobile-social-top {
            gap: 15px;
        }

        .mobile-social-top i,
        .mobile-social-top .custom-x-icon {
            font-size: 18px;
        }

        .scrolling-banner {
            height: 26px;
        }

        .scrolling-content {
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 10px;
        }

        .logo img {
            max-width: 110px;
        }

        .phone {
            font-size: 11px;
            padding: 6px 12px;
        }

        .mobile-menu-btn {
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
        }

        .mobile-menu-btn i {
            font-size: 20px;
        }

        .mobile-social-top {
            gap: 12px;
        }

        .mobile-social-top i,
        .mobile-social-top .custom-x-icon {
            font-size: 16px;
        }

        .mobile-language-selector select {
            font-size: 13px;
            padding: 8px 12px;
        }

        .scrolling-banner {
            height: 24px;
        }

        .scrolling-content {
            font-size: 9px;
            animation-duration: 18s;
        }
    }

    /* Pour les écrans très larges */
    @media (min-width: 1400px) {
        .desktop-nav {
            gap: 30px;
        }
    }

    @media (min-width: 1600px) {
        .desktop-nav {
            gap: 35px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Éléments du DOM
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuIcon = mobileMenuBtn.querySelector('i');

        // Gestion du changement de langue desktop
        const languageSwitcher = document.getElementById('language-switcher');
        if (languageSwitcher) {
            languageSwitcher.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }

        // Gestion du changement de langue mobile
        const mobileLanguageSelect = document.querySelector('.language-select-mobile');
        if (mobileLanguageSelect) {
            mobileLanguageSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }

        // Ouvrir/fermer le menu mobile
        mobileMenuBtn.addEventListener('click', function() {
            const isMenuOpen = mobileMenu.classList.contains('active');

            if (isMenuOpen) {
                // Fermer le menu
                mobileMenu.classList.remove('active');
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                document.body.style.overflow = '';
            } else {
                // Ouvrir le menu
                mobileMenu.classList.add('active');
                mobileMenuIcon.classList.remove('fa-bars');
                mobileMenuIcon.classList.add('fa-times');
                document.body.style.overflow = 'hidden';
            }
        });

        // Fermer le menu mobile en cliquant sur un lien
        const mobileLinks = document.querySelectorAll('.mobile-link, .mobile-sub-link, .mobile-dashboard, .mobile-client-btn');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                document.body.style.overflow = '';
            });
        });

        // Gérer les dropdowns mobiles
        const mobileDropdownBtns = document.querySelectorAll('.mobile-dropdown-btn');
        mobileDropdownBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const dropdownContent = document.getElementById(targetId);

                // Fermer tous les autres dropdowns
                mobileDropdownBtns.forEach(otherBtn => {
                    if (otherBtn !== btn) {
                        const otherTargetId = otherBtn.getAttribute('data-target');
                        const otherContent = document.getElementById(otherTargetId);
                        otherContent.classList.remove('active');
                        otherBtn.classList.remove('active');
                    }
                });

                // Basculer le dropdown actuel
                dropdownContent.classList.toggle('active');
                this.classList.toggle('active');
            });
        });

        // Gestion des icônes sociales (clics)
        const socialIcons = document.querySelectorAll('.social-top i, .social-top .custom-x-icon, .mobile-social-top i, .mobile-social-top .custom-x-icon');
        socialIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                let url = '#';

                if (this.classList.contains('fa-facebook-f')) {
                    url = 'https://web.facebook.com/djokPrestige?_rdc=1&_rdr#';
                } else if (this.classList.contains('fa-instagram')) {
                    url = 'https://www.instagram.com/djok_prestige/';
                } else if (this.classList.contains('fa-snapchat')) {
                    url = 'https://www.snapchat.com/@djok_transp2020?share_id=om-FcniDZNw&locale=fr-FR+';
                } else if (this.classList.contains('custom-x-icon') || this.classList.contains('fa-x-twitter')) {
                    url = 'https://x.com/DjokPrestige?t=7WuNufsxSxAQPmdmzO1DUQ';
                } else if (this.classList.contains('fa-tiktok')) {
                    url = 'https://www.tiktok.com/@djokprestige?_r=1&_t=ZN-932NFlRHD9i';
                }

                window.open(url, '_blank');
            });
        });
    });
</script>
