@extends('layouts.main')

@section('title', __('index.page_title'))

@section('content')
<!-- Messages de succès/erreur - Style sobre -->
<div class="container px-4 mx-auto md:px-6">
    @if(session('success'))
    <div class="mt-6 mb-6">
        <div class="p-4" style="background: #111; border-left: 4px solid #b89449;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle" style="color: #b89449;"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-white">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mt-6 mb-6">
        <div class="p-4" style="background: #2a0f0f; border-left: 4px solid #f56565;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle" style="color: #f56565;"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-white">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Hero Section - Style sobre -->
<header class="relative flex items-center min-h-screen" style="background: #000;">
    <div class="absolute inset-0 bg-black">
        <img src="https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&dpr=1"
            alt="Formation E-learning" class="object-cover w-full h-full opacity-40">
        <div class="absolute inset-0" style="background: rgba(0, 0, 0, 0.7);"></div>
    </div>

    <div class="container relative z-10 px-4 py-20 mx-auto md:px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="mb-8 text-3xl font-bold md:text-4xl lg:text-5xl" style="color: #b89449;">
                {{ __('index.main_title') }}
            </h1>

            <p class="mb-10 text-lg text-gray-300 md:text-xl">
                {{ __('index.main_subtitle') }}
            </p>

            <p class="mb-12 text-lg" style="color: #b89449;">
                {{ __('index.certified_training') }}
            </p>

            <!-- Avantages clés - Style sobre -->
            <div class="grid grid-cols-1 gap-6 mb-16 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col items-center text-white">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-laptop"></i>
                    </div>
                    <span class="text-sm text-center">{{ __('index.fully_online') }}</span>
                </div>
                <div class="flex flex-col items-center text-white">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-clock"></i>
                    </div>
                    <span class="text-sm text-center">{{ __('index.access_24_7') }}</span>
                </div>
                <div class="flex flex-col items-center text-white">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-chalkboard-teacher"></i>
                    </div>
                    <span class="text-sm text-center">{{ __('index.expert_trainers') }}</span>
                </div>
                <div class="flex flex-col items-center text-white">
                    <div class="flex items-center justify-center mb-4 w-14 h-14" style="background: #b89449;">
                        <i class="text-xl text-black fas fa-certificate"></i>
                    </div>
                    <span class="text-sm text-center">{{ __('index.certification_included') }}</span>
                </div>
            </div>

            <!-- Boutons - Style sobre -->
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#forfaits" class="w-full px-8 py-3 font-semibold text-center transition duration-300 sm:w-auto"
                    style="background: #b89449; color: black;">
                    {{ __('index.view_packages') }}
                </a>
                <a href="{{ route('elearning.salle') }}"
                    class="w-full px-8 py-3 font-semibold text-center transition duration-300 border sm:w-auto"
                    style="border-color: #b89449; color: #b89449;">
                    {{ __('index.access_my_room') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute transform -translate-x-1/2 bottom-8 left-1/2">
        <a href="#forfaits" class="text-white transition duration-300 hover:text-b89449"
            aria-label="{{ __('index.scroll_down') }}">
            <i class="text-xl fas fa-chevron-down"></i>
        </a>
    </div>
</header>

<!-- Forfaits - Style sobre -->
<section id="forfaits" class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{ __('index.our_packages') }}</h2>
            <p class="max-w-3xl mx-auto text-gray-400">
                {{ __('index.packages_subtitle') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 max-w-6xl mx-auto md:grid-cols-3">
            @foreach($forfaits as $forfait)
            <div class="overflow-hidden rounded-lg" style="background: #111; border: 1px solid #333;">
                <!-- Header du forfait -->
                <div class="p-6" style="background: #1a1a1a;">
                    <h3 class="mb-2 text-xl font-bold text-white">{{ $forfait->name }}</h3>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold" style="color: #b89449;">{{ $forfait->formatted_price }}</span>
                        <span class="ml-2 text-gray-400">/ {{ $forfait->duration_days }} {{ __('index.days_access')
                            }}</span>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                    <p class="mb-6 text-gray-300">{{ $forfait->description }}</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                            <span class="text-gray-300">{{ __('index.all_courses_available') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                            <span class="text-gray-300">{{ __('index.all_courses_available') }}</span>
                        </li>
                        @if($forfait->includes_qcm)
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                            <span class="text-gray-300">{{ __('index.self_assessment_qcm') }}</span>
                        </li>
                        @endif
                        @if($forfait->includes_examens_blancs)
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                            <span class="text-gray-300">{{ __('index.corrected_practice_exams') }}</span>
                        </li>
                        @endif
                        @if($forfait->includes_certification)
                        <li class="flex items-center">
                            <i class="mr-3 fas fa-check" style="color: #46b94c;"></i>
                            <span class="text-gray-300">{{ __('index.training_certificate') }}</span>
                        </li>
                        @endif
                    </ul>

                    <a href="{{ route('elearning.acheter', $forfait->slug) }}"
                        class="block w-full py-3 text-center font-semibold transition duration-300"
                        style="background: #b89449; color: black;">
                        {{ __('index.choose_this_package') }}
                    </a>
                </div>
            </div>
            @endforeach

            @if($forfaits->count() === 0)
            <div class="col-span-3 py-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4" style="background: #111;">
                    <i class="text-2xl fas fa-book" style="color: #b89449;"></i>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-white">{{ __('index.no_packages_available') }}</h3>
                <p class="text-gray-400">{{ __('index.packages_coming_soon') }}</p>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Comment ça marche - Style sobre -->
<section class="py-16" style="background: #111;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{ __('index.how_it_works') }}</h2>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-6" style="background: #b89449;">
                    <i class="text-2xl fas fa-shopping-cart"></i>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">1. {{ __('index.choose_your_package') }}</h3>
                <p class="text-gray-400">{{ __('index.choose_package_desc') }}</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-6" style="background: #b89449;">
                    <i class="text-2xl fas fa-credit-card"></i>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">2. {{ __('index.secure_payment') }}</h3>
                <p class="text-gray-400">{{ __('index.secure_payment_desc') }}</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-6" style="background: #b89449;">
                    <i class="text-2xl fas fa-laptop"></i>
                </div>
                <h3 class="mb-3 text-lg font-bold text-white">3. {{ __('index.immediate_access') }}</h3>
                <p class="text-gray-400">{{ __('index.immediate_access_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ - Style sobre -->
<section class="py-16" style="background: #000;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-2xl font-bold md:text-3xl" style="color: #b89449;">{{ __('index.faq_title') }}</h2>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            <div class="overflow-hidden rounded-lg" style="background: #111; border: 1px solid #333;">
                <div class="p-6">
                    <h3 class="mb-3 text-lg font-semibold text-white">{{ __('index.faq1_question') }}</h3>
                    <p class="text-gray-400">{{ __('index.faq1_answer') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg" style="background: #111; border: 1px solid #333;">
                <div class="p-6">
                    <h3 class="mb-3 text-lg font-semibold text-white">{{ __('index.faq2_question') }}</h3>
                    <p class="text-gray-400">{{ __('index.faq2_answer') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg" style="background: #111; border: 1px solid #333;">
                <div class="p-6">
                    <h3 class="mb-3 text-lg font-semibold text-white">{{ __('index.faq3_question') }}</h3>
                    <p class="text-gray-400">{{ __('index.faq3_answer') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg" style="background: #111; border: 1px solid #333;">
                <div class="p-6">
                    <h3 class="mb-3 text-lg font-semibold text-white">{{ __('index.faq4_question') }}</h3>
                    <p class="text-gray-400">{{ __('index.faq4_answer') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accès salle virtuelle - Style sobre -->
<section class="py-16" style="background: #b89449; color: black;">
    <div class="container px-4 mx-auto md:px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="mb-6 text-2xl font-bold md:text-3xl">{{ __('index.already_have_access') }}</h2>
            <p class="mb-8 text-lg">
                {{ __('index.connect_to_virtual_room') }}
            </p>
            <a href="{{ route('elearning.salle') }}"
                class="inline-flex items-center px-8 py-3 font-semibold transition-all duration-300"
                style="background: #000; color: white;">
                <i class="mr-3 fas fa-sign-in-alt"></i>{{ __('index.access_virtual_room') }}
            </a>
        </div>
    </div>
</section>
@endsection
