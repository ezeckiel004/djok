@extends('layouts.main')

@section('title', __('cgv.title'))

@section('content')
<div class="min-h-screen py-12 bg-gray-50">
    <div class="max-w-4xl px-4 mx-auto">
        <div class="p-8 bg-white rounded-lg shadow-lg">
            <h1 class="mb-8 text-3xl font-bold text-center text-gray-900">{{ __('cgv.main_title') }}</h1>

            <div class="prose prose-lg max-w-none">
                <p class="mb-6 text-center text-gray-600">
                    {{ __('cgv.subtitle') }}
                </p>

                <div class="space-y-8">
                    <!-- Article 1 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_1') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_1_content') }}
                        </p>
                    </div>

                    <!-- Article 2 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_2') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_2_content') }}
                        </p>
                    </div>

                    <!-- Article 3 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_3') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_3_content') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_3_additional') }}
                        </p>
                    </div>

                    <!-- Article 4 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_4') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_4_content') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_4_consumer') }}
                        </p>
                    </div>

                    <!-- Article 5 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_5') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_5_content') }}
                        </p>
                    </div>

                    <!-- Article 6 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_6') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_6_content') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_6_cancellation_1') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_6_postponement') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_6_force_majeure') }}
                        </p>
                    </div>

                    <!-- Article 7 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_7') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_7_content') }}
                        </p>
                    </div>

                    <!-- Article 8 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_8') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_8_content') }}
                        </p>
                    </div>

                    <!-- Article 9 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_9') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_9_content') }}
                        </p>
                    </div>

                    <!-- Article 10 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_10') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_10_content') }}
                        </p>
                    </div>

                    <!-- Article 11 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_11') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_11_content') }}
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ __('cgv.article_11_content_2') }}
                        </p>
                        <p class="mt-2 text-gray-700 font-semibold">
                            {{ __('cgv.article_11_mediator') }}
                        </p>
                        <p class="text-gray-700">
                            <a href="{{ __('cgv.article_11_mediator_link') }}" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 hover:underline">
                                {{ __('cgv.article_11_mediator_link') }}
                            </a><br>
                            {{ __('cgv.article_11_mediator_address') }}
                        </p>
                    </div>

                    <!-- Article 12 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_12') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_12_content') }}
                        </p>
                    </div>

                    <!-- Article 13 -->
                    <div>
                        <h2 class="mb-4 text-xl font-bold text-gray-900">{{ __('cgv.article_13') }}</h2>
                        <p class="text-gray-700">
                            {{ __('cgv.article_13_content') }}
                        </p>
                    </div>
                </div>

                <!-- Informations de mise à jour -->
                <div class="p-6 mt-12 rounded-lg bg-gray-50">
                    <p class="text-sm text-gray-600">
                        <strong>{{ __('cgv.last_update') }}</strong> {{ __('cgv.last_update_date') }}<br>
                        <strong>{{ __('cgv.contact') }}</strong> {{ __('cgv.contact_email') }}<br>
                        <strong>{{ __('cgv.phone') }}</strong> {{ __('cgv.phone_number') }}
                    </p>
                </div>

                <!-- Bouton de retour uniquement -->
                <div class="mt-8">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('cgv.back_to_home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles pour améliorer la lisibilité -->
<style>
    .prose p {
        line-height: 1.8;
        margin-bottom: 1.2rem;
    }

    .prose h2 {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.5rem;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
    }

    .prose h2:first-of-type {
        margin-top: 0;
    }
</style>
@endsection
