@extends('layouts.main')

@section('title', __('contact.title'))

@section('content')
<style>
    /* Animation pour le champ "autre" */
    .other-field {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
    }

    .other-field.show {
        opacity: 1;
        max-height: 200px;
    }

    .service-radio:checked+.service-card {
        border-color: var(--gold);
        background-color: rgba(var(--gold-rgb), 0.1);
    }

    .service-card {
        transition: all 0.2s ease;
        cursor: pointer;
        background: #111;
        border: 1px solid #333;
        color: white;
    }

    .service-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(202, 162, 77, 0.3);
        border-color: var(--gold);
    }
</style>

<!-- Message de succès - Style sobre -->
@if(session('success'))
<div id="success-alert" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-2xl">
    <div class="mx-4 p-4" style="background: #064e3b; border-left: 4px solid #10b981;">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full" style="background: #047857;">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-white">{{ __('contact.success_title') }}</h3>
                    <div class="mt-1 text-green-100">
                        <p>{{ session('success') }}</p>
                        @if(session('email'))
                        <p class="text-sm mt-1">
                            {!! __('contact.confirmation_email', ['email' => session('email')]) !!}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove();"
                class="text-green-300 hover:text-white" aria-label="{{ __('contact.close_alert') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }
    }, 8000);
</script>
@endif

<div class="container mx-auto px-4 md:px-6 py-12">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--gold);">{{ __('contact.hero_title') }}
            </h1>
            <p class="text-gray-400 max-w-3xl mx-auto">
                {{ __('contact.hero_description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire de contact - Style sobre -->
            <div class="lg:col-span-2">
                <div class="p-6 md:p-8" style="background: #111; border: 1px solid #333;">
                    <h2 class="text-2xl font-bold mb-6" style="color: var(--gold);">{{ __('contact.form_title') }}</h2>

                    @if($errors->any())
                    <div class="p-4 mb-6" style="background: #7f1d1d; border: 1px solid #991b1b;">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3"
                                style="background: #dc2626;">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">{{ __('contact.error_correction') }}</h4>
                                <ul class="text-red-100 list-disc list-inside mt-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" id="contact-form">
                        @csrf

                        <!-- Informations personnelles -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4" style="color: white;">{{ __('contact.personal_info')
                                }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        __('contact.full_name') }}</label>
                                    <input type="text" name="nom" required value="{{ old('nom') }}"
                                        class="w-full px-4 py-3 rounded @error('nom') border-red-500 @enderror"
                                        style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                        placeholder="{{ __('contact.full_name_placeholder') }}">
                                    @error('nom')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('contact.email')
                                        }}</label>
                                    <input type="email" name="email" required value="{{ old('email') }}"
                                        class="w-full px-4 py-3 rounded @error('email') border-red-500 @enderror"
                                        style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                        placeholder="{{ __('contact.email_placeholder') }}">
                                    @error('email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('contact.phone')
                                    }}</label>
                                <input type="tel" name="telephone" value="{{ old('telephone') }}"
                                    class="w-full px-4 py-3 rounded @error('telephone') border-red-500 @enderror"
                                    style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                    placeholder="{{ __('contact.phone_placeholder') }}">
                                @error('telephone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sélection du service -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4" style="color: white;">{{ __('contact.service_type')
                                }}</h3>
                            <p class="text-gray-400 text-sm mb-4">{{ __('contact.service_select_help') }}</p>

                            @error('service_id')
                            <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                @foreach($services as $service)
                                <label class="cursor-pointer" aria-label="{{ __('contact.select_service') }}">
                                    <input type="radio" name="service_id" value="{{ $service->id }}"
                                        class="service-radio hidden" {{ old('service_id')==$service->id ? 'checked' : ''
                                    }}>
                                    <div class="service-card rounded-lg p-4 transition-all duration-200">
                                        <div class="flex items-center">
                                            @if($service->icon)
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="{{ $service->icon }} text-lg"
                                                    style="color: {{ $service->color }}"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <h4 class="font-medium" style="color: white;">{{ $service->name }}</h4>
                                                @if($service->price_from)
                                                <p class="text-sm text-gray-400 mt-1">
                                                    {{ __('À partir de') }} {{ number_format($service->price_from, 0,
                                                    ',', ' ') }}€
                                                    @if($service->price_unit)
                                                    {{ __('contact.' . $service->price_unit) }}
                                                    @endif
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach

                                <!-- Option "Autre service" -->
                                <label class="cursor-pointer" aria-label="{{ __('contact.select_service') }}">
                                    <input type="radio" name="service_id" value="autre" class="service-radio hidden"
                                        id="autre-service" {{ old('service_id')=='autre' ? 'checked' : '' }}>
                                    <div class="service-card rounded-lg p-4 transition-all duration-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="fas fa-plus-circle text-lg" style="color: var(--gold);"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium" style="color: white;">{{
                                                    __('contact.other_service') }}</h4>
                                                <p class="text-sm text-gray-400 mt-1">{{
                                                    __('contact.other_service_desc') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Champ "Autre" qui apparaît -->
                            <div id="autre-field-container"
                                class="other-field {{ old('service_id') == 'autre' ? 'show' : '' }}">
                                <div class="mt-4">
                                    <label class="block mb-2 font-medium" style="color: #ddd;">{{
                                        __('contact.other_specify') }}</label>
                                    <input type="text" name="autre_service" value="{{ old('autre_service') }}"
                                        class="w-full px-4 py-3 rounded @error('autre_service') border-red-500 @enderror"
                                        style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                        placeholder="{{ __('contact.other_placeholder') }}">
                                    @error('autre_service')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4" style="color: white;">{{
                                __('contact.message_section') }}</h3>
                            <div>
                                <label class="block mb-2 font-medium" style="color: #ddd;">{{ __('contact.message')
                                    }}</label>
                                <textarea name="message" rows="6" required
                                    class="w-full px-4 py-3 rounded @error('message') border-red-500 @enderror"
                                    style="background: #1a1a1a; border: 1px solid #444; color: white;"
                                    placeholder="{{ __('contact.message_placeholder') }}">{{ old('message') }}</textarea>
                                @error('message')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-2">{{ __('contact.message_min_chars') }}</p>
                            </div>
                        </div>

                        <!-- Bouton d'envoi -->
                        <div class="relative">
                            <button type="submit" id="submit-btn"
                                class="w-full px-6 md:px-8 py-3 font-semibold rounded transition-all duration-300 flex items-center justify-center"
                                style="background: var(--gold); color: black;">
                                <span id="btn-text" class="flex items-center justify-center">
                                    <i class="fas fa-paper-plane mr-3"></i>
                                    {{ __('contact.submit_button') }}
                                </span>
                                <span id="btn-loading" class="hidden flex items-center justify-center">
                                    <i class="fas fa-spinner fa-spin mr-3"></i>
                                    {{ __('contact.sending') }}
                                </span>
                            </button>
                        </div>

                        <p class="text-center text-sm mt-4" style="color: #888;">
                            <i class="fas fa-shield-alt mr-2"></i>
                            {{ __('contact.security_notice') }}
                        </p>
                    </form>
                </div>
            </div>

            <!-- Informations de contact + Services populaires - Style sobre -->
            <div class="space-y-8">
                <!-- Coordonnées -->
                <div class="p-6" style="background: #111; border: 1px solid #333;">
                    <h3 class="text-xl font-bold mb-6" style="color: var(--gold);">{{ __('contact.contact_info') }}</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                    style="background: rgba(var(--gold-rgb), 0.2);">
                                    <i class="fas fa-phone-alt" style="color: var(--gold);"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold" style="color: white;">{{ __('contact.by_phone') }}</h4>
                                </div>
                            </div>
                            <a href="tel:0699164455" class="text-lg font-medium hover:text-var(--gold)"
                                style="color: var(--gold);">
                                06 99 16 44 55
                            </a>
                            <p class="text-sm text-gray-400 mt-2">{{ __('contact.phone_hours') }}</p>
                        </div>

                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                    style="background: rgba(59, 130, 246, 0.2);">
                                    <i class="fas fa-envelope" style="color: #3b82f6;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold" style="color: white;">{{ __('contact.by_email') }}</h4>
                                </div>
                            </div>
                            <a href="mailto:contact@djokprestige.com" class="text-lg font-medium hover:text-blue-400"
                                style="color: #3b82f6;">
                                contact@djokprestige.com
                            </a>
                        </div>

                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                    style="background: rgba(37, 211, 102, 0.2);">
                                    <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold" style="color: white;">{{ __('contact.by_whatsapp') }}</h4>
                                </div>
                            </div>
                            <a href="https://wa.me/33176380017" target="_blank"
                                class="text-lg font-medium hover:text-green-400" style="color: #25D366;">
                                06 99 16 44 55
                            </a>
                            <p class="text-sm text-gray-400 mt-2">{{ __('contact.whatsapp_available') }}</p>
                        </div>

                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full mr-3"
                                    style="background: rgba(139, 92, 246, 0.2);">
                                    <i class="fas fa-map-marker-alt" style="color: #8b5cf6;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold" style="color: white;">{{ __('contact.our_center') }}</h4>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium" style="color: white;">DJOK PRESTIGE</p>
                                <p class="text-sm text-gray-400 mt-1">{{ __('contact.center_address_line1') }}</p>
                                <p class="text-sm text-gray-400">{{ __('contact.center_address_line2') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services populaires -->
                <div class="p-6" style="background: #1a1a1a; border: 1px solid #333;">
                    <h3 class="text-lg font-bold mb-4" style="color: var(--gold);">{{ __('contact.popular_services') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($services->take(4) as $service)
                        <div class="flex items-center p-3 rounded-lg" style="background: #111; border: 1px solid #333;">
                            @if($service->icon)
                            <div class="flex-shrink-0 mr-3">
                                <i class="{{ $service->icon }}" style="color: {{ $service->color }}"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-medium text-sm" style="color: white;">{{ $service->name }}</h4>
                                @if($service->description)
                                <p class="text-xs text-gray-400 mt-1">{{ Str::limit($service->description, 40) }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            const autreServiceRadio = document.getElementById('autre-service');
            const autreFieldContainer = document.getElementById('autre-field-container');
            const autreFieldInput = document.querySelector('input[name="autre_service"]');

            function toggleOtherField() {
                if (autreServiceRadio && autreServiceRadio.checked) {
                    autreFieldContainer.classList.add('show');
                    if (autreFieldInput) autreFieldInput.required = true;
                } else {
                    autreFieldContainer.classList.remove('show');
                    if (autreFieldInput) autreFieldInput.required = false;
                }
            }

            document.querySelectorAll('input[name="service_id"]').forEach(radio => {
                radio.addEventListener('change', toggleOtherField);
            });

            toggleOtherField();

            const form = document.getElementById('contact-form');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');

            if (form) {
                form.addEventListener('submit', function() {
                    if (submitBtn) submitBtn.disabled = true;
                    if (btnText) btnText.classList.add('hidden');
                    if (btnLoading) btnLoading.classList.remove('hidden');
                });

                if (document.querySelector('.border-red-500')) {
                    if (submitBtn) submitBtn.disabled = false;
                    if (btnText) btnText.classList.remove('hidden');
                    if (btnLoading) btnLoading.classList.add('hidden');
                }
            }

            // Auto-hide du message de succès
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    successAlert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => successAlert.remove(), 500);
                }, 8000);
            }

            // Scroll vers le formulaire s'il y a des erreurs
            if (document.querySelector('[class*="border-red"]')) {
                setTimeout(() => {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 300);
            }

            // Scroll vers le message de succès s'il existe
            if (successAlert) {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        });
</script>
@endsection
