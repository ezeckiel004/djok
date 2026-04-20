@extends('layouts.client')

@section('title', $cours->title . ' - DJOK PRESTIGE')
@section('page-title', $cours->title)
@section('page-description', 'Cours e-learning')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('client.elearning.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour à ma salle
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-2xl font-bold text-gray-900 break-words">{{ $cours->title }}</h1>

                @if(!$progression->cours_completed)
                <button type="button" id="markCompleteBtn"
                    data-action="{{ route('client.elearning.cours.complete', $cours->id) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-check mr-2"></i> Marquer comme terminé
                </button>
                @else
                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg whitespace-nowrap">
                    <i class="fas fa-check-circle mr-2"></i> Cours terminé
                </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            @if($cours->description)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-700 break-words whitespace-normal">{{ $cours->description }}</p>
            </div>
            @endif

            @if($cours->content)
            <div class="prose max-w-none break-words">
                {!! $cours->content !!}
            </div>
            @endif

            @if($cours->hasVideo())
            <div class="mt-6">
                <h3 class="font-bold text-gray-900 mb-3">Vidéo du cours</h3>
                <div class="aspect-video bg-black rounded-lg overflow-hidden">
                    <video controls class="w-full h-full">
                        <source src="{{ $cours->video_url }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </div>
            </div>
            @endif

            @if($cours->hasPdf())
            <div class="mt-6">
                <h3 class="font-bold text-gray-900 mb-3">Document du cours</h3>
                <a href="{{ $cours->pdf_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-file-pdf mr-2"></i> Télécharger le document
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('markCompleteBtn')?.addEventListener('click', function () {
        const btn = this;
        const originalHTML = btn.innerHTML;
        const action = btn.getAttribute('data-action');

        // Récupérer le token CSRF depuis la meta tag (méthode recommandée)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!csrfToken) {
            alert('Erreur de sécurité : token CSRF introuvable. Rechargez la page.');
            return;
        }

        // Désactiver le bouton
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';

        const formData = new FormData();
        formData.append('_token', csrfToken);

        fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().catch(() => {
                    throw new Error('Erreur serveur (' + response.status + ')');
                }).then(data => {
                    throw new Error(data.error || 'Erreur serveur (' + response.status + ')');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface sans recharger
                btn.outerHTML = `
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg whitespace-nowrap">
                        <i class="fas fa-check-circle mr-2"></i> Cours terminé
                    </span>
                `;
            } else {
                alert('Erreur : ' + (data.error || 'Impossible de marquer le cours comme terminé'));
                resetButton();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue : ' + error.message + '\nVeuillez réessayer.');
            resetButton();
        });

        function resetButton() {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    });
</script>
@endsection

<style>
    .break-words {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .whitespace-normal {
        white-space: normal;
    }

    .prose {
        max-width: 100%;
    }

    .prose p,
    .prose h1,
    .prose h2,
    .prose h3,
    .prose h4,
    .prose ul,
    .prose ol,
    .prose li {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .prose img {
        max-width: 100%;
        height: auto;
    }

    .prose iframe {
        max-width: 100%;
    }

    .prose pre,
    .prose code {
        max-width: 100%;
        overflow-x: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
