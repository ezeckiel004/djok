@extends('layouts.main')

@section('title', $article->title . ' - DJOK PRESTIGE Blog')

@section('meta')
<meta name="description" content="{{ $article->excerpt }}">
<meta property="og:title" content="{{ $article->title }}">
<meta property="og:description" content="{{ $article->excerpt }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
@if($article->image)
<meta property="og:image" content="{{ Storage::url($article->image) }}">
@endif
@endsection

@section('content')
<!-- Hero section sans navbar (la navbar est déjà dans le layout) -->
<header class="relative bg-gradient-to-r from-{{ $article->color_class }}-600 to-{{ $article->color_class }}-800">
    <div class="container mx-auto px-4 pt-24 pb-16">
        <div class="max-w-4xl mx-auto text-center text-white">
            <!-- Catégorie -->
            <div class="inline-flex items-center mb-6">
                <a href="{{ route('blog.category', $article->category) }}"
                    class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium hover:bg-white/30 transition">
                    <i class="fas fa-tag mr-2"></i>{{ $article->category_label }}
                </a>
            </div>

            <!-- Titre -->
            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                {{ $article->title }}
            </h1>

            <!-- Métadonnées -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-white/80 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span>{{ $article->created_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span>{{ $article->reading_time }} min de lecture</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <span>{{ $article->user->name ?? 'DJOK PRESTIGE' }}</span>
                </div>
            </div>

            <!-- Extrait -->
            <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                {{ $article->excerpt }}
            </p>
        </div>
    </div>

    <!-- Vague décorative -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-12">
            <path
                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                opacity=".25" class="fill-white"></path>
            <path
                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                opacity=".5" class="fill-white"></path>
            <path
                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                class="fill-white"></path>
        </svg>
    </div>
</header>

<!-- Contenu de l'article -->
<article class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Image principale -->
            @if($article->image)
            <div class="mb-12 rounded-2xl overflow-hidden shadow-2xl">
                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                    class="w-full h-auto max-h-[600px] object-cover">
            </div>
            @endif

            <!-- Contenu -->
            <div class="prose prose-lg max-w-none">
                @php
                // Convertir le markdown en HTML simple
                $content = $article->content;

                // Convertir **texte** en <strong>texte</strong>
                $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);

                // Convertir *texte* en <em>texte</em>
                $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);

                // Convertir __texte__ en <u>texte</u>
                $content = preg_replace('/__(.*?)__/', '<u>$1</u>', $content);

                // Convertir ### Titre en <h3>Titre</h3>
                $content = preg_replace('/### (.*?)(\n|$)/', '<h3 class="text-2xl font-bold mt-8 mb-4 text-gray-900">$1</h3>', $content);

                // Convertir #### Titre en <h4>Titre</h4>
                $content = preg_replace('/#### (.*?)(\n|$)/', '<h4 class="text-xl font-bold mt-6 mb-3 text-gray-800">$1</h4>', $content);

                // Convertir [texte](url) en <a href="url">texte</a>
                $content = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" class="text-' . $article->color_class . '-600 hover:text-' . $article->color_class . '-700 underline" target="_blank" rel="noopener">$1</a>', $content);

                // Convertir les listes à puces
                $content = preg_replace('/\n\* (.*?)(\n|$)/', "\n<li>$1</li>", $content);
                $content = str_replace("\n<li>", "\n<ul class='list-disc pl-6 mb-4 space-y-2'><li>", $content);
                $content = str_replace("</li>\n<li>", "</li>\n<li>", $content);
                $content = str_replace("</li>\n", "</li></ul>\n", $content);

                // Convertir les retours à la ligne en paragraphes
                $paragraphs = explode("\n\n", $content);
                $formattedContent = '';
                foreach ($paragraphs as $paragraph) {
                    $paragraph = trim($paragraph);
                    if (!empty($paragraph)) {
                        if (!preg_match('/^<(h[1-6]|ul|ol|li|strong|em|u|a) /', $paragraph)) {
                            $formattedContent .= '<p class="mb-6 text-gray-700 leading-relaxed">' . nl2br($paragraph) . '</p>';
                        } else {
                            $formattedContent .= $paragraph;
                        }
                    }
                }
                @endphp
                {!! $formattedContent !!}
            </div>

            <!-- Tags et partage -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- Tags -->
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Catégories :</span>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('blog.category', $article->category) }}"
                                class="px-3 py-1 bg-{{ $article->color_class }}-100 text-{{ $article->color_class }}-800 rounded-full text-sm font-medium hover:bg-{{ $article->color_class }}-200 transition">
                                {{ $article->category_label }}
                            </a>
                        </div>
                    </div>

                    <!-- Partage -->
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block">Partager :</span>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank"
                                class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}"
                                target="_blank"
                                class="w-10 h-10 flex items-center justify-center bg-blue-400 text-white rounded-full hover:bg-blue-500 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                target="_blank"
                                class="w-10 h-10 flex items-center justify-center bg-blue-700 text-white rounded-full hover:bg-blue-800 transition">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($article->title) }}&body={{ urlencode('Regarde cet article : ' . url()->current()) }}"
                                class="w-10 h-10 flex items-center justify-center bg-gray-600 text-white rounded-full hover:bg-gray-700 transition">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Articles similaires -->
@if($relatedArticles->count() > 0)
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Articles similaires</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez d'autres articles sur le même thème
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedArticles as $relatedArticle)
                <div class="bg-white rounded-xl shadow-md overflow-hidden group hover:shadow-lg transition duration-300">
                    @if($relatedArticle->image)
                    <div class="h-48 overflow-hidden">
                        <img src="{{ Storage::url($relatedArticle->image) }}" alt="{{ $relatedArticle->title }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300">
                    </div>
                    @else
                    <div class="h-48 flex items-center justify-center bg-gradient-to-r from-{{ $relatedArticle->color_class }}-400 to-{{ $relatedArticle->color_class }}-600">
                        <i class="{{ $relatedArticle->icon ?? 'fas fa-newspaper' }} text-white text-5xl"></i>
                    </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 bg-{{ $relatedArticle->color_class }}-100 text-{{ $relatedArticle->color_class }}-800 rounded-full text-xs font-medium">
                                {{ $relatedArticle->category_label }}
                            </span>
                            <span class="text-gray-500 text-sm">{{ $relatedArticle->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
                            {{ $relatedArticle->title }}
                        </h3>
                        <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                            {{ $relatedArticle->excerpt }}
                        </p>
                        <a href="{{ route('blog.show', $relatedArticle->slug) }}"
                            class="inline-flex items-center font-medium text-sm text-{{ $relatedArticle->color_class }}-600 hover:text-{{ $relatedArticle->color_class }}-700">
                            Lire l'article
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Bouton retour au blog -->
            <div class="text-center mt-12">
                <a href="{{ route('blog') }}"
                    class="inline-flex items-center px-6 py-3 bg-{{ $article->color_class }}-600 text-white rounded-lg hover:bg-{{ $article->color_class }}-700 transition font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au blog
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<style>
    .prose {
        color: #374151;
    }

    .prose h3 {
        color: #111827;
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .prose h4 {
        color: #1f2937;
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .prose p {
        margin-bottom: 1.5rem;
        line-height: 1.75;
    }

    .prose ul {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .prose li {
        margin-bottom: 0.5rem;
    }

    .prose a {
        text-decoration: underline;
    }

    .prose strong {
        font-weight: 700;
    }

    .prose em {
        font-style: italic;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
</style>
@endsection
