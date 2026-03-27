<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationMedia;
use App\Models\Paiement;
use App\Models\UserFormation;
use App\Models\Participant;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:access-admin-dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('FormationController@index - Liste des formations');

        $formations = Formation::withCount('media')
            ->withCount(['paiements' => function ($query) {
                $query->where('service_type', 'formation')
                    ->where('status', 'paid');
            }])
            ->withCount(['userFormations' => function ($query) {
                $query->where('status', 'active');
            }])
            ->latest()
            ->paginate(15);

        return view('admin.formations.index', compact('formations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info('FormationController@create - Formulaire de création');
        return view('admin.formations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== DÉBUT FormationController@store ===');

        // Augmenter les limites PHP pour les gros fichiers (2GB)
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 3600);
        ini_set('max_input_time', 3600);
        set_time_limit(3600);

        // Désactiver le buffering pour économiser la mémoire
        while (ob_get_level()) {
            ob_end_clean();
        }

        Log::info('Limites PHP configurées - Memory: 2GB, Timeout: 1h');

        try {
            // Log des informations de la requête
            Log::info('Content-Length header: ' . $request->header('Content-Length'));
            Log::info('Content-Type: ' . $request->header('Content-Type'));
            Log::info('Has PDF files: ' . ($request->hasFile('pdf_files') ? 'OUI' : 'NON'));
            Log::info('Has Video files: ' . ($request->hasFile('video_files') ? 'OUI' : 'NON'));
            Log::info('Has Programme PDF: ' . ($request->hasFile('programme_pdf') ? 'OUI' : 'NON'));

            // Validation des données de base
            Log::info('Validation des données de base...');
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration_hours' => 'required|integer|min:1',
                'format_type' => 'required|in:presentiel,en_ligne,mixte',
                'type_formation' => 'required|in:presentiel,e_learning',
                'categorie' => 'required|in:vtc_theorique,vtc_pratique,e_learning,renouvellement',
                'duree' => 'nullable|string|max:100',
                'format_affichage' => 'nullable|string|max:100',
                'frais_examen' => 'nullable|string|max:100',
                'location_vehicule' => 'nullable|string|max:100',
                'is_certified' => 'boolean',
                'is_financeable_cpf' => 'boolean',
                'is_active' => 'boolean',
                'program' => 'nullable|string',
                'requirements' => 'nullable|string',
                'included_services' => 'nullable|string',
                'programme_pdf' => 'nullable|file|mimes:pdf|max:51200', // 50MB max pour le PDF programme
            ]);
            Log::info('Validation des données de base réussie');

            // Générer le slug automatiquement avec le type de formation
            Log::info('Génération du slug...');
            $slug = Str::slug($validated['title']);
            if ($validated['type_formation'] === 'presentiel') {
                $slug .= '-presentiel';
            } elseif ($validated['type_formation'] === 'e_learning') {
                $slug .= '-en-ligne';
            }

            // Vérifier l'unicité du slug
            $originalSlug = $slug;
            $counter = 1;

            while (Formation::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
            Log::info('Slug généré: ' . $slug);

            // Validation des fichiers PDF (500MB max)
            if ($request->hasFile('pdf_files')) {
                Log::info('Validation des fichiers PDF...');
                $pdfFiles = $request->file('pdf_files');
                Log::info('Nombre de fichiers PDF: ' . (is_array($pdfFiles) ? count($pdfFiles) : 1));

                $request->validate([
                    'pdf_files.*' => 'file|mimes:pdf|max:512000', // 500MB
                    'pdf_titles.*' => 'nullable|string|max:255',
                    'pdf_descriptions.*' => 'nullable|string',
                ]);
                Log::info('Validation des fichiers PDF réussie');
            }

            // Validation des fichiers vidéo (2GB max)
            if ($request->hasFile('video_files')) {
                Log::info('Validation des fichiers vidéo...');
                $videoFiles = $request->file('video_files');
                Log::info('Nombre de fichiers vidéo: ' . (is_array($videoFiles) ? count($videoFiles) : 1));

                $request->validate([
                    'video_files.*' => 'file|mimes:mp4,mov,avi,mkv,wmv,flv,webm|max:2097152', // 2GB (2097152 KB)
                    'video_titles.*' => 'nullable|string|max:255',
                    'video_descriptions.*' => 'nullable|string',
                    'video_durations.*' => 'nullable|string|max:20',
                    'video_thumbnails.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max pour les miniatures
                ]);
                Log::info('Validation des fichiers vidéo réussie');
            }

            // Convertir les textareas en tableaux JSON
            Log::info('Conversion des textareas en JSON...');
            if ($request->filled('program')) {
                $programLines = array_filter(array_map('trim', explode("\n", $request->program)));
                $validated['program'] = !empty($programLines) ? $programLines : null;
                Log::info('Programme - lignes: ' . count($programLines ?? []));
            }

            if ($request->filled('requirements')) {
                $requirementsLines = array_filter(array_map('trim', explode("\n", $request->requirements)));
                $validated['requirements'] = !empty($requirementsLines) ? $requirementsLines : null;
                Log::info('Prérequis - lignes: ' . count($requirementsLines ?? []));
            }

            if ($request->filled('included_services')) {
                $servicesLines = array_filter(array_map('trim', explode("\n", $request->included_services)));
                $validated['included_services'] = !empty($servicesLines) ? $servicesLines : null;
                Log::info('Services inclus - lignes: ' . count($servicesLines ?? []));
            }

            // Convertir les checkboxes en booléens
            Log::info('Conversion des checkboxes...');
            $validated['is_certified'] = $request->has('is_certified');
            $validated['is_financeable_cpf'] = $request->has('is_financeable_cpf');
            $validated['is_active'] = $request->has('is_active');

            Log::info('is_certified: ' . ($validated['is_certified'] ? 'true' : 'false'));
            Log::info('is_financeable_cpf: ' . ($validated['is_financeable_cpf'] ? 'true' : 'false'));
            Log::info('is_active: ' . ($validated['is_active'] ? 'true' : 'false'));

            // Créer la formation
            Log::info('Création de la formation en base de données...');
            $formation = Formation::create($validated);
            Log::info('Formation créée avec ID: ' . $formation->id . ' - Titre: ' . $formation->title);

            // Upload du PDF programme (brochure)
            if ($request->hasFile('programme_pdf')) {
                Log::info('Upload du PDF programme...');
                $this->uploadProgrammePdf($formation, $request->file('programme_pdf'));
                Log::info('Upload du PDF programme terminé');
            }

            // Upload des fichiers PDF
            if ($request->hasFile('pdf_files')) {
                Log::info('Début upload des fichiers PDF...');
                $this->uploadMediaFiles(
                    $formation,
                    $request->file('pdf_files'),
                    'pdf',
                    $request->input('pdf_titles', []),
                    $request->input('pdf_descriptions', [])
                );
                Log::info('Upload des fichiers PDF terminé');
            }

            // Upload des fichiers vidéo
            if ($request->hasFile('video_files')) {
                Log::info('Début upload des fichiers vidéo...');
                $this->uploadMediaFiles(
                    $formation,
                    $request->file('video_files'),
                    'video',
                    $request->input('video_titles', []),
                    $request->input('video_descriptions', []),
                    $request->input('video_durations', []),
                    $request->file('video_thumbnails', [])
                );
                Log::info('Upload des fichiers vidéo terminé');
            }

            // Créer automatiquement un produit Stripe pour les formations e-learning
            if ($formation->isElearning() && !$formation->stripe_price_id) {
                Log::info('Tentative de création du produit Stripe pour la formation e-learning...');
                try {
                    $stripeService = app(\App\Services\StripeService::class);
                    $stripeService->createProductForFormation($formation);
                    Log::info('Produit Stripe créé avec succès');
                } catch (\Exception $e) {
                    Log::error('Erreur création produit Stripe: ' . $e->getMessage());
                }
            }

            Log::info('=== FIN FormationController@store - SUCCÈS ===');

            return redirect()->route('admin.formations.index')
                ->with('success', 'Formation créée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== ERREUR DE VALIDATION ===');
            Log::error('Erreurs: ', $e->errors());
            Log::error('Données reçues: ', $request->except(['pdf_files', 'video_files', 'new_pdf_files', 'new_video_files', 'programme_pdf']));

            // Re-throw l'exception pour qu'elle soit gérée par Laravel
            throw $e;
        } catch (\Exception $e) {
            Log::error('=== ERREUR GÉNÉRALE ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            // Rollback: supprimer la formation si elle a été créée
            if (isset($formation) && $formation->exists) {
                Log::info('Rollback: suppression de la formation ID: ' . $formation->id);
                try {
                    $formation->delete();
                    Log::info('Formation supprimée avec succès');
                } catch (\Exception $deleteError) {
                    Log::error('Erreur lors du rollback: ' . $deleteError->getMessage());
                }
            }

            return back()
                ->withInput($request->except(['pdf_files', 'video_files', 'programme_pdf']))
                ->withErrors([
                    'error' => 'Une erreur est survenue lors de la création de la formation: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        Log::info('FormationController@show - Affichage formation ID: ' . $formation->id);

        $formation->load(['media' => function ($query) {
            $query->orderBy('order');
        }]);

        // Compter les paiements avec la nouvelle structure
        $formation->paiements_count = Paiement::where('service_type', 'formation')
            ->where('service_id', $formation->id)
            ->where('status', 'paid')
            ->count();

        Log::info('FormationController@show - Nombre de médias: ' . $formation->media->count());

        return view('admin.formations.show', compact('formation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        Log::info('FormationController@edit - Édition formation ID: ' . $formation->id);

        $formation->load(['media' => function ($query) {
            $query->orderBy('order');
        }]);

        Log::info('FormationController@edit - Nombre de médias: ' . $formation->media->count());

        return view('admin.formations.edit', compact('formation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        Log::info('=== DÉBUT FormationController@update ===');
        Log::info('Formation ID: ' . $formation->id);

        // Augmenter les limites PHP pour les gros fichiers
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 3600);
        ini_set('max_input_time', 3600);
        set_time_limit(3600);

        // Désactiver le buffering
        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            // Validation des données de base
            Log::info('Validation des données de base...');
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'duration_hours' => 'required|integer|min:1',
                'format_type' => 'required|in:presentiel,en_ligne,mixte',
                'type_formation' => 'required|in:presentiel,e_learning',
                'categorie' => 'required|in:vtc_theorique,vtc_pratique,e_learning,renouvellement',
                'duree' => 'nullable|string|max:100',
                'format_affichage' => 'nullable|string|max:100',
                'frais_examen' => 'nullable|string|max:100',
                'location_vehicule' => 'nullable|string|max:100',
                'is_certified' => 'boolean',
                'is_financeable_cpf' => 'boolean',
                'is_active' => 'boolean',
                'program' => 'nullable|string',
                'requirements' => 'nullable|string',
                'included_services' => 'nullable|string',
                'programme_pdf' => 'nullable|file|mimes:pdf|max:51200', // 50MB max
            ]);
            Log::info('Validation des données de base réussie');

            // Générer un nouveau slug si le titre ou le type a changé
            if ($formation->isDirty('title') || $formation->isDirty('type_formation')) {
                Log::info('Titre ou type de formation modifié - Régénération du slug...');
                $slug = Str::slug($validated['title']);
                if ($validated['type_formation'] === 'presentiel') {
                    $slug .= '-presentiel';
                } elseif ($validated['type_formation'] === 'e_learning') {
                    $slug .= '-en-ligne';
                }

                // Vérifier l'unicité du slug
                $originalSlug = $slug;
                $counter = 1;

                while (Formation::where('slug', $slug)->where('id', '!=', $formation->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $validated['slug'] = $slug;
                Log::info('Nouveau slug généré: ' . $slug);
            } else {
                Log::info('Slug inchangé: ' . $formation->slug);
                $validated['slug'] = $formation->slug;
            }

            // Validation des nouveaux fichiers PDF (500MB)
            if ($request->hasFile('new_pdf_files')) {
                Log::info('Validation des nouveaux fichiers PDF...');
                $request->validate([
                    'new_pdf_files.*' => 'file|mimes:pdf|max:512000', // 500MB
                    'new_pdf_titles.*' => 'nullable|string|max:255',
                    'new_pdf_descriptions.*' => 'nullable|string',
                ]);
                Log::info('Validation des nouveaux fichiers PDF réussie');
            }

            // Validation des nouveaux fichiers vidéo (2GB)
            if ($request->hasFile('new_video_files')) {
                Log::info('Validation des nouveaux fichiers vidéo...');
                $request->validate([
                    'new_video_files.*' => 'file|mimes:mp4,mov,avi,mkv,wmv,flv,webm|max:2097152', // 2GB
                    'new_video_titles.*' => 'nullable|string|max:255',
                    'new_video_descriptions.*' => 'nullable|string',
                    'new_video_durations.*' => 'nullable|string|max:20',
                    'new_video_thumbnails.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max pour les miniatures
                ]);
                Log::info('Validation des nouveaux fichiers vidéo réussie');
            }

            // Convertir les textareas en tableaux JSON
            Log::info('Conversion des textareas en JSON...');
            if ($request->filled('program')) {
                $programLines = array_filter(array_map('trim', explode("\n", $request->program)));
                $validated['program'] = !empty($programLines) ? $programLines : null;
                Log::info('Programme converti');
            } else {
                $validated['program'] = null;
                Log::info('Programme défini à null');
            }

            if ($request->filled('requirements')) {
                $requirementsLines = array_filter(array_map('trim', explode("\n", $request->requirements)));
                $validated['requirements'] = !empty($requirementsLines) ? $requirementsLines : null;
                Log::info('Prérequis convertis');
            } else {
                $validated['requirements'] = null;
                Log::info('Prérequis défini à null');
            }

            if ($request->filled('included_services')) {
                $servicesLines = array_filter(array_map('trim', explode("\n", $request->included_services)));
                $validated['included_services'] = !empty($servicesLines) ? $servicesLines : null;
                Log::info('Services inclus convertis');
            } else {
                $validated['included_services'] = null;
                Log::info('Services inclus défini à null');
            }

            // Convertir les checkboxes en booléens
            Log::info('Conversion des checkboxes...');
            $validated['is_certified'] = $request->has('is_certified');
            $validated['is_financeable_cpf'] = $request->has('is_financeable_cpf');
            $validated['is_active'] = $request->has('is_active');

            Log::info('Checkboxes - is_certified: ' . ($validated['is_certified'] ? 'true' : 'false') .
                ', is_financeable_cpf: ' . ($validated['is_financeable_cpf'] ? 'true' : 'false') .
                ', is_active: ' . ($validated['is_active'] ? 'true' : 'false'));

            // Mettre à jour la formation
            Log::info('Mise à jour de la formation...');
            $formation->update($validated);
            Log::info('Formation mise à jour avec succès');

            // Gestion du PDF programme (brochure)
            if ($request->hasFile('programme_pdf')) {
                Log::info('Upload du nouveau PDF programme...');
                // Supprimer l'ancien PDF s'il existe
                $formation->deleteProgrammePdf();
                // Upload du nouveau
                $this->uploadProgrammePdf($formation, $request->file('programme_pdf'));
                Log::info('Upload du nouveau PDF programme terminé');
            }

            // Si l'utilisateur a coché la case de suppression
            if ($request->has('delete_programme_pdf') && $request->delete_programme_pdf == '1') {
                Log::info('Suppression du PDF programme demandée...');
                $formation->deleteProgrammePdf();
                Log::info('PDF programme supprimé');
            }

            // Gérer les médias existants (mise à jour des titres/descriptions)
            if ($request->has('media_titles')) {
                $mediaCount = count($request->input('media_titles', []));
                Log::info('Mise à jour des ' . $mediaCount . ' média(s) existant(s)...');

                foreach ($request->input('media_titles', []) as $mediaId => $title) {
                    $media = $formation->media()->find($mediaId);
                    if ($media) {
                        $media->update([
                            'title' => $title,
                            'description' => $request->input("media_descriptions.{$mediaId}", null),
                        ]);
                        Log::info('Média ID ' . $mediaId . ' mis à jour');
                    } else {
                        Log::warning('Média ID ' . $mediaId . ' non trouvé');
                    }
                }
            }

            // Upload des nouveaux fichiers PDF
            if ($request->hasFile('new_pdf_files')) {
                Log::info('Upload des nouveaux fichiers PDF...');
                $this->uploadMediaFiles(
                    $formation,
                    $request->file('new_pdf_files'),
                    'pdf',
                    $request->input('new_pdf_titles', []),
                    $request->input('new_pdf_descriptions', [])
                );
                Log::info('Upload des nouveaux fichiers PDF terminé');
            }

            // Upload des nouveaux fichiers vidéo
            if ($request->hasFile('new_video_files')) {
                Log::info('Upload des nouveaux fichiers vidéo...');
                $this->uploadMediaFiles(
                    $formation,
                    $request->file('new_video_files'),
                    'video',
                    $request->input('new_video_titles', []),
                    $request->input('new_video_descriptions', []),
                    $request->input('new_video_durations', []),
                    $request->file('new_video_thumbnails', [])
                );
                Log::info('Upload des nouveaux fichiers vidéo terminé');
            }

            // Supprimer les médias cochés
            if ($request->has('delete_media')) {
                $deleteCount = count($request->input('delete_media', []));
                Log::info('Suppression de ' . $deleteCount . ' média(s)...');

                foreach ($request->input('delete_media', []) as $mediaId) {
                    $media = $formation->media()->find($mediaId);
                    if ($media) {
                        // Supprimer le fichier physique
                        Storage::disk('public')->delete($media->file_path);
                        // Supprimer la miniature si elle existe
                        if ($media->thumbnail_path) {
                            Storage::disk('public')->delete($media->thumbnail_path);
                        }
                        // Supprimer l'entrée en base
                        $media->delete();
                        Log::info('Média ID ' . $mediaId . ' supprimé');
                    } else {
                        Log::warning('Média ID ' . $mediaId . ' non trouvé pour suppression');
                    }
                }
            }

            // Réorganiser les médias
            if ($request->has('media_order')) {
                $orderArray = explode(',', $request->input('media_order'));
                Log::info('Réorganisation des médias - ordre: ' . $request->input('media_order'));

                foreach ($orderArray as $index => $mediaId) {
                    $media = $formation->media()->find($mediaId);
                    if ($media) {
                        $media->update(['order' => $index + 1]);
                        Log::info('Média ID ' . $mediaId . ' -> ordre: ' . ($index + 1));
                    } else {
                        Log::warning('Média ID ' . $mediaId . ' non trouvé pour réorganisation');
                    }
                }
            }

            // Créer automatiquement un produit Stripe si c'est une formation e-learning sans produit
            if ($formation->isElearning() && !$formation->stripe_price_id) {
                Log::info('Tentative de création du produit Stripe...');
                try {
                    $stripeService = app(\App\Services\StripeService::class);
                    $stripeService->createProductForFormation($formation);
                    Log::info('Produit Stripe créé avec succès');
                } catch (\Exception $e) {
                    Log::error('Erreur création produit Stripe: ' . $e->getMessage());
                }
            }

            Log::info('=== FIN FormationController@update - SUCCÈS ===');

            return redirect()->route('admin.formations.index')
                ->with('success', 'Formation mise à jour avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== ERREUR DE VALIDATION ===');
            Log::error('Erreurs: ', $e->errors());
            throw $e;
        } catch (\Exception $e) {
            Log::error('=== ERREUR GÉNÉRALE ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return back()
                ->withInput($request->except(['new_pdf_files', 'new_video_files', 'programme_pdf']))
                ->withErrors([
                    'error' => 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        Log::info('=== DÉBUT FormationController@destroy ===');
        Log::info('Tentative de suppression formation ID: ' . $formation->id);
        Log::info('Titre: ' . $formation->title);

        try {
            // Compter les associations avant suppression pour le log
            $paidPaiementsCount = Paiement::where('service_type', 'formation')
                ->where('service_id', $formation->id)
                ->where('status', 'paid')
                ->count();

            $totalPaiementsCount = Paiement::where('service_type', 'formation')
                ->where('service_id', $formation->id)
                ->count();

            $activeUserFormationsCount = $formation->userFormations()->where('status', 'active')->count();
            $totalUserFormationsCount = $formation->userFormations()->count();
            $participantsCount = $formation->inscriptions()->count();
            $mediaCount = $formation->media()->count();

            Log::info('Statistiques avant suppression:');
            Log::info('- Paiements payés: ' . $paidPaiementsCount);
            Log::info('- Paiements totaux: ' . $totalPaiementsCount);
            Log::info('- Inscriptions actives: ' . $activeUserFormationsCount);
            Log::info('- Inscriptions totales: ' . $totalUserFormationsCount);
            Log::info('- Participants: ' . $participantsCount);
            Log::info('- Médias: ' . $mediaCount);

            // Utiliser une transaction pour s'assurer que tout est supprimé ou rien
            DB::beginTransaction();

            try {
                // 1. Supprimer d'abord les paiements associés
                Log::info('Suppression des paiements associés...');
                $paiementsDeleted = Paiement::where('service_type', 'formation')
                    ->where('service_id', $formation->id)
                    ->delete();
                Log::info('Paiements supprimés: ' . $paiementsDeleted);

                // 2. Supprimer les inscriptions utilisateur (UserFormation)
                Log::info('Suppression des inscriptions utilisateur...');
                $userFormationsDeleted = $formation->userFormations()->delete();
                Log::info('Inscriptions utilisateur supprimées: ' . $userFormationsDeleted);

                // 3. Supprimer les participants
                Log::info('Suppression des participants...');
                $participantsDeleted = $formation->inscriptions()->delete();
                Log::info('Participants supprimés: ' . $participantsDeleted);

                // 4. Supprimer tous les fichiers multimédias
                Log::info('Suppression des ' . $mediaCount . ' fichier(s) multimédia(s)...');

                foreach ($formation->media as $media) {
                    Log::info('Suppression fichier: ' . $media->file_path);
                    Storage::disk('public')->delete($media->file_path);

                    // Supprimer la miniature si elle existe
                    if ($media->thumbnail_path) {
                        Log::info('Suppression miniature: ' . $media->thumbnail_path);
                        Storage::disk('public')->delete($media->thumbnail_path);
                    }
                }

                // 5. Supprimer les entrées médias de la base de données
                $mediaDeleted = $formation->media()->delete();
                Log::info('Entrées médias supprimées: ' . $mediaDeleted);

                // 6. Supprimer le PDF programme s'il existe
                if ($formation->programme_pdf && Storage::disk('public')->exists($formation->programme_pdf)) {
                    Log::info('Suppression du PDF programme: ' . $formation->programme_pdf);
                    Storage::disk('public')->delete($formation->programme_pdf);
                }

                // 7. Supprimer le dossier de la formation
                $formationFolder = "formations/{$formation->id}";
                if (Storage::disk('public')->exists($formationFolder)) {
                    Log::info('Suppression du dossier: ' . $formationFolder);
                    Storage::disk('public')->deleteDirectory($formationFolder);
                } else {
                    Log::info('Dossier non trouvé: ' . $formationFolder);
                }

                // 8. Enfin, supprimer la formation elle-même
                $formationTitle = $formation->title;
                $formation->delete();

                // Valider la transaction
                DB::commit();

                Log::info('=== FIN FormationController@destroy - SUCCÈS ===');
                Log::info('Formation "' . $formationTitle . '" supprimée avec succès');
                Log::info('Suppressions effectuées:');
                Log::info('- Paiements: ' . $totalPaiementsCount);
                Log::info('- Inscriptions utilisateur: ' . $totalUserFormationsCount);
                Log::info('- Participants: ' . $participantsCount);
                Log::info('- Médias: ' . $mediaCount);

                return redirect()->route('admin.formations.index')
                    ->with('success', 'Formation et toutes ses données associées ont été supprimées avec succès. (' .
                        $totalPaiementsCount . ' paiement(s), ' .
                        $totalUserFormationsCount . ' inscription(s), ' .
                        $participantsCount . ' participant(s) et ' .
                        $mediaCount . ' média(s) supprimé(s)).');
            } catch (\Exception $e) {
                // En cas d'erreur, annuler la transaction
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('=== ERREUR DANS DESTROY ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->route('admin.formations.index')
                ->with('error', 'Erreur lors de la suppression de la formation: ' . $e->getMessage());
        }
    }

    /**
     * Upload du PDF programme (brochure)
     */
    private function uploadProgrammePdf($formation, $file)
    {
        try {
            $fileName = 'programme_' . Str::slug($formation->title) . '_' . time() . '.pdf';
            $folder = "formations/{$formation->id}/programme";

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder, 0755, true);
            }

            $path = $file->storeAs($folder, $fileName, 'public');

            $formation->update([
                'programme_pdf' => $path,
                'programme_pdf_generated_at' => now(),
            ]);

            Log::info('PDF programme uploadé', [
                'formation_id' => $formation->id,
                'path' => $path
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur upload PDF programme', [
                'formation_id' => $formation->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Upload des fichiers multimédias (optimisé pour gros fichiers jusqu'à 2GB)
     */
    private function uploadMediaFiles($formation, $files, $type, $titles = [], $descriptions = [], $durations = [], $thumbnails = [])
    {
        Log::info('=== DÉBUT uploadMediaFiles ===');
        Log::info('Type: ' . $type);
        Log::info('Formation ID: ' . $formation->id);

        // Normaliser les tableaux
        if (!is_array($files)) {
            $files = [$files];
        }

        if (!is_array($titles)) {
            $titles = [];
        }

        if (!is_array($descriptions)) {
            $descriptions = [];
        }

        if (!is_array($durations)) {
            $durations = [];
        }

        if (!is_array($thumbnails)) {
            $thumbnails = [];
        }

        $totalFiles = count($files);
        Log::info('Nombre total de fichiers à traiter: ' . $totalFiles);

        $order = $formation->media()->max('order') ?? 0;
        Log::info('Ordre de départ: ' . $order);

        $successCount = 0;
        $errorCount = 0;

        foreach ($files as $index => $file) {
            $fileNumber = $index + 1;
            Log::info('--- Traitement fichier ' . $fileNumber . '/' . $totalFiles . ' ---');

            if (!$file || !$file->isValid()) {
                Log::warning('Fichier ' . $fileNumber . ' invalide ou manquant');
                $errorCount++;
                continue;
            }

            $order++;
            Log::info('Nom original: ' . $file->getClientOriginalName());
            Log::info('Taille: ' . $this->formatFileSize($file->getSize()));
            Log::info('Type MIME: ' . $file->getMimeType());

            try {
                // Libérer la mémoire régulièrement
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

                // Générer un nom de fichier unique et sécurisé
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension() ?: $this->getExtensionFromMime($file->getMimeType());
                $fileName = Str::slug($originalName) . '_' . time() . '_' . Str::random(5) . '.' . $extension;

                Log::info('Nom généré: ' . $fileName);

                // Définir le chemin de stockage
                $folder = "formations/{$formation->id}/{$type}s";
                Log::info('Dossier de destination: ' . $folder);

                // Créer le dossier s'il n'existe pas
                if (!Storage::disk('public')->exists($folder)) {
                    Log::info('Création du dossier...');
                    Storage::disk('public')->makeDirectory($folder, 0755, true);
                }

                // Upload du fichier principal
                Log::info('Début de l\'upload du fichier principal...');
                $startTime = microtime(true);

                $path = $file->storeAs($folder, $fileName, 'public');

                $endTime = microtime(true);
                $uploadTime = round($endTime - $startTime, 2);
                Log::info('Upload terminé en ' . $uploadTime . ' secondes');
                Log::info('Chemin stocké: ' . $path);

                // Vérifier que le fichier a bien été uploadé
                if (!Storage::disk('public')->exists($path)) {
                    throw new \Exception('Le fichier n\'a pas été correctement stocké sur le disque');
                }

                $storedSize = Storage::disk('public')->size($path);
                Log::info('Taille vérifiée: ' . $this->formatFileSize($storedSize));

                // Gérer la miniature pour les vidéos
                $thumbnailPath = null;
                if ($type === 'video' && isset($thumbnails[$index]) && $thumbnails[$index] && $thumbnails[$index]->isValid()) {
                    Log::info('Traitement de la miniature...');
                    $thumbnailFile = $thumbnails[$index];
                    $thumbnailOriginalName = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $thumbnailExtension = $thumbnailFile->getClientOriginalExtension() ?: 'jpg';
                    $thumbnailFileName = Str::slug($thumbnailOriginalName) . '_thumb_' . time() . '_' . Str::random(5) . '.' . $thumbnailExtension;

                    $thumbnailFolder = "formations/{$formation->id}/thumbnails";

                    if (!Storage::disk('public')->exists($thumbnailFolder)) {
                        Storage::disk('public')->makeDirectory($thumbnailFolder, 0755, true);
                    }

                    $thumbnailPath = $thumbnailFile->storeAs($thumbnailFolder, $thumbnailFileName, 'public');
                    Log::info('Miniature uploadée: ' . $thumbnailPath);
                }

                // Formater la taille pour l'affichage
                $size = $this->formatFileSize($file->getSize());

                // Préparer les données du média
                $mediaData = [
                    'type' => $type,
                    'title' => isset($titles[$index]) && !empty(trim($titles[$index])) ? trim($titles[$index]) : $originalName,
                    'description' => isset($descriptions[$index]) && !empty(trim($descriptions[$index])) ? trim($descriptions[$index]) : null,
                    'file_path' => $path,
                    'thumbnail_path' => $thumbnailPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $size,
                    'order' => $order,
                ];

                // Ajouter la durée pour les vidéos
                if ($type === 'video') {
                    $mediaData['duration'] = isset($durations[$index]) && !empty(trim($durations[$index])) ? trim($durations[$index]) : '00:00';
                    Log::info('Durée vidéo: ' . $mediaData['duration']);
                }

                // Créer l'entrée en base de données
                Log::info('Création de l\'entrée en base de données...');
                $media = $formation->media()->create($mediaData);
                Log::info('Média créé avec ID: ' . $media->id . ', Ordre: ' . $order);

                $successCount++;
            } catch (\Exception $e) {
                Log::error('ERREUR lors du traitement du fichier ' . $file->getClientOriginalName());
                Log::error('Message d\'erreur: ' . $e->getMessage());
                Log::error('Fichier: ' . $e->getFile());
                Log::error('Ligne: ' . $e->getLine());

                $errorCount++;

                // Nettoyer les fichiers partiellement uploadés
                if (isset($path) && Storage::disk('public')->exists($path)) {
                    Log::info('Nettoyage: suppression du fichier principal ' . $path);
                    Storage::disk('public')->delete($path);
                }

                if (isset($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
                    Log::info('Nettoyage: suppression de la miniature ' . $thumbnailPath);
                    Storage::disk('public')->delete($thumbnailPath);
                }

                // Continuer avec les autres fichiers au lieu de tout arrêter
                continue;
            }
        }

        Log::info('=== FIN uploadMediaFiles ===');
        Log::info('Résumé: ' . $successCount . ' fichier(s) uploadé(s) avec succès, ' . $errorCount . ' erreur(s)');

        if ($errorCount > 0) {
            throw new \Exception($errorCount . ' fichier(s) n\'ont pas pu être uploadé(s)');
        }
    }

    /**
     * Obtenir l'extension à partir du type MIME
     */
    private function getExtensionFromMime($mimeType)
    {
        $mimeMap = [
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-matroska' => 'mkv',
            'video/x-ms-wmv' => 'wmv',
            'video/x-flv' => 'flv',
            'video/webm' => 'webm',
            'application/pdf' => 'pdf',
        ];

        return $mimeMap[$mimeType] ?? 'bin';
    }

    /**
     * Formater la taille du fichier en unités lisible
     */
    private function formatFileSize($bytes)
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Toggle le statut actif/inactif
     */
    public function toggleStatus(Request $request, Formation $formation)
    {
        Log::info('FormationController@toggleStatus - Formation ID: ' . $formation->id);
        Log::info('Ancien statut: ' . ($formation->is_active ? 'actif' : 'inactif'));

        try {
            $formation->update([
                'is_active' => !$formation->is_active
            ]);

            $newStatus = $formation->is_active ? 'actif' : 'inactif';
            Log::info('Nouveau statut: ' . $newStatus);

            return response()->json([
                'success' => true,
                'is_active' => $formation->is_active,
                'message' => 'Formation ' . $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans toggleStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    /**
     * Synchroniser avec Stripe (créer ou mettre à jour le produit)
     */
    public function syncStripe(Formation $formation)
    {
        Log::info('=== DÉBUT FormationController@syncStripe ===');
        Log::info('Formation ID: ' . $formation->id);
        Log::info('Type formation: ' . $formation->type_formation);
        Log::info('Stripe price ID existant: ' . ($formation->stripe_price_id ?? 'null'));

        if (!$formation->isElearning()) {
            Log::warning('Tentative de sync Stripe sur une formation non e-learning');
            return redirect()->route('admin.formations.index')
                ->with('error', 'Seules les formations e-learning peuvent être synchronisées avec Stripe.');
        }

        try {
            Log::info('Création du produit Stripe...');
            $stripeService = app(\App\Services\StripeService::class);
            $success = $stripeService->createProductForFormation($formation);

            if ($success) {
                $formation->refresh();
                Log::info('Produit Stripe créé avec succès');
                Log::info('Nouveau Stripe product ID: ' . $formation->stripe_product_id);
                Log::info('Nouveau Stripe price ID: ' . $formation->stripe_price_id);

                Log::info('=== FIN FormationController@syncStripe - Succès ===');

                return redirect()->route('admin.formations.show', $formation)
                    ->with('success', 'Produit Stripe créé avec succès !');
            } else {
                Log::error('La création du produit Stripe a échoué (retourné false)');
                return redirect()->route('admin.formations.show', $formation)
                    ->with('error', 'Erreur lors de la création du produit Stripe.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans syncStripe: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('admin.formations.show', $formation)
                ->with('error', 'Erreur Stripe: ' . $e->getMessage());
        }
    }

    /**
     * Corriger le slug d'une formation spécifique
     */
    public function fixSlug(Formation $formation)
    {
        Log::info('=== DÉBUT FormationController@fixSlug ===');
        Log::info('Formation ID: ' . $formation->id);
        Log::info('Titre: ' . $formation->title);
        Log::info('Ancien slug: ' . $formation->slug);
        Log::info('Type formation: ' . $formation->type_formation);

        try {
            $oldSlug = $formation->slug;

            // Générer un nouveau slug unique
            $newSlug = Str::slug($formation->title);
            if ($formation->type_formation === 'presentiel') {
                $newSlug .= '-presentiel';
            } elseif ($formation->type_formation === 'e_learning') {
                $newSlug .= '-en-ligne';
            }

            // Vérifier l'unicité du slug
            $originalSlug = $newSlug;
            $counter = 1;

            while (Formation::where('slug', $newSlug)->where('id', '!=', $formation->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $formation->slug = $newSlug;
            $formation->save();

            Log::info('Slug corrigé: ' . $oldSlug . ' → ' . $newSlug);
            Log::info('=== FIN FormationController@fixSlug - Succès ===');

            return redirect()->route('admin.formations.show', $formation)
                ->with('success', "Slug corrigé : {$oldSlug} → {$newSlug}");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la correction du slug', [
                'formation_id' => $formation->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.formations.show', $formation)
                ->with('error', "Erreur lors de la correction du slug : {$e->getMessage()}");
        }
    }

    /**
     * Corriger tous les slugs des formations
     */
    public function fixAllSlugs()
    {
        Log::info('=== DÉBUT FormationController@fixAllSlugs ===');

        try {
            $formations = Formation::all();
            $updatedCount = 0;
            $errors = [];

            Log::info('Nombre total de formations: ' . $formations->count());

            foreach ($formations as $formation) {
                try {
                    $oldSlug = $formation->slug;

                    // Générer un nouveau slug unique
                    $newSlug = Str::slug($formation->title);
                    if ($formation->type_formation === 'presentiel') {
                        $newSlug .= '-presentiel';
                    } elseif ($formation->type_formation === 'e_learning') {
                        $newSlug .= '-en-ligne';
                    }

                    // Vérifier l'unicité du slug
                    $originalSlug = $newSlug;
                    $counter = 1;

                    while (Formation::where('slug', $newSlug)->where('id', '!=', $formation->id)->exists()) {
                        $newSlug = $originalSlug . '-' . $counter;
                        $counter++;
                    }

                    if ($oldSlug !== $newSlug) {
                        $formation->slug = $newSlug;
                        $formation->saveQuietly(); // saveQuietly pour éviter les événements

                        Log::info('Formation ' . $formation->id . ' : ' . $oldSlug . ' → ' . $newSlug);
                        $updatedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Formation {$formation->id} ({$formation->title}) : {$e->getMessage()}";
                    Log::error('Erreur formation ' . $formation->id . ' : ' . $e->getMessage());
                }
            }

            $message = "{$updatedCount} slugs ont été corrigés avec succès.";

            if (!empty($errors)) {
                $message .= " Erreurs : " . implode(' | ', $errors);
                Log::info('=== FIN FormationController@fixAllSlugs - Avec erreurs ===');
                return redirect()->route('admin.formations.index')
                    ->with('warning', $message);
            }

            Log::info('=== FIN FormationController@fixAllSlugs - Succès ===');
            return redirect()->route('admin.formations.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la correction de tous les slugs', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.formations.index')
                ->with('error', "Erreur lors de la correction des slugs : {$e->getMessage()}");
        }
    }

    /**
     * Vérifier les doublons de slugs
     */
    public function checkSlugs()
    {
        Log::info('=== DÉBUT FormationController@checkSlugs ===');

        try {
            // Rechercher les doublons de slugs
            $duplicates = Formation::select('slug')
                ->groupBy('slug')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            Log::info('Nombre de doublons trouvés: ' . $duplicates->count());

            if ($duplicates->isEmpty()) {
                Log::info('Aucun doublon détecté');
                return redirect()->route('admin.formations.index')
                    ->with('info', '✓ Aucun doublon de slug détecté.');
            }

            $duplicateList = [];
            foreach ($duplicates as $dup) {
                $formations = Formation::where('slug', $dup->slug)->get();
                $duplicateList[] = [
                    'slug' => $dup->slug,
                    'count' => $formations->count(),
                    'formations' => $formations->map(function ($f) {
                        return "ID: {$f->id}, Titre: {$f->title}, Type: {$f->type_formation}";
                    })->implode(' | ')
                ];

                Log::info('Doublon trouvé: ' . $dup->slug . ' (' . $formations->count() . ' formations)');
            }

            Log::info('=== FIN FormationController@checkSlugs ===');
            return redirect()->route('admin.formations.index')
                ->with('warning', 'Doublons détectés : ' .
                    collect($duplicateList)->map(function ($item) {
                        return "Slug '{$item['slug']}' utilisé par {$item['count']} formation(s) : {$item['formations']}";
                    })->implode('; '));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification des doublons', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.formations.index')
                ->with('error', "Erreur lors de la vérification des doublons : {$e->getMessage()}");
        }
    }

    /**
     * Rapport détaillé des slugs
     */
    public function slugsReport()
    {
        Log::info('FormationController@slugsReport - Rapport des slugs');

        $formations = Formation::select(['id', 'title', 'slug', 'type_formation', 'categorie', 'is_active'])
            ->orderBy('slug')
            ->get();

        // Analyser les slugs
        $analysis = [
            'total' => $formations->count(),
            'with_presentiel_suffix' => $formations->where('slug', 'like', '%-presentiel')->count(),
            'with_en_ligne_suffix' => $formations->where('slug', 'like', '%-en-ligne')->count(),
            'without_suffix' => $formations->where(function ($query) {
                $query->where('slug', 'not like', '%-presentiel')
                    ->where('slug', 'not like', '%-en-ligne');
            })->count(),
            'active' => $formations->where('is_active', true)->count(),
            'inactive' => $formations->where('is_active', false)->count(),
        ];

        return view('admin.formations.slugs-report', compact('formations', 'analysis'));
    }

    public function paiementsIndex()
    {
        Log::info('FormationController@paiementsIndex - Liste des paiements');

        $paiements = Paiement::with(['user'])
            ->where('service_type', 'formation')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistiques = [
            'total' => Paiement::where('service_type', 'formation')->count(),
            'payes' => Paiement::where('service_type', 'formation')->where('status', 'paid')->count(),
            'en_attente' => Paiement::where('service_type', 'formation')->where('status', 'pending')->count(),
            'annules' => Paiement::where('service_type', 'formation')->where('status', 'canceled')->count(),
            'refunded' => Paiement::where('service_type', 'formation')->where('status', 'refunded')->count(),
            'total_amount' => Paiement::where('service_type', 'formation')->where('status', 'paid')->sum('amount'),
        ];

        return view('admin.paiements.index', compact('paiements', 'statistiques'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function paiementsShow(Paiement $paiement)
    {
        Log::info('FormationController@paiementsShow - Détails paiement ID: ' . $paiement->id);
        Log::info('Utilisateur associé: ' . ($paiement->user_id ? 'OUI (ID: ' . $paiement->user_id . ')' : 'NON'));
        Log::info('Service Type: ' . $paiement->service_type);
        Log::info('Service ID: ' . $paiement->service_id);

        try {
            // 1. Charger les relations selon le type de service
            $formation = null;
            if ($paiement->service_type === 'formation') {
                $formation = Formation::with('media')->find($paiement->service_id);
                Log::info('Formation trouvée: ' . ($formation ? 'OUI' : 'NON'));
            }

            // 2. Charger l'utilisateur seulement s'il existe
            $user = null;
            if ($paiement->user_id) {
                $user = \App\Models\User::find($paiement->user_id);
                Log::info('Utilisateur trouvé: ' . $user->email);
            } else {
                Log::info('Aucun utilisateur associé à ce paiement');
            }

            // 3. Charger les inscriptions utilisateur (uniquement si c'est une formation)
            $userFormations = collect();
            $participant = null;

            if ($paiement->service_type === 'formation' && $paiement->service_id && $paiement->user_id) {
                // Cas 1: Utilisateur avec compte
                Log::info('Recherche des inscriptions utilisateur...');

                // Inscriptions dans UserFormation
                $userFormations = \App\Models\UserFormation::where('user_id', $paiement->user_id)
                    ->where('formation_id', $paiement->service_id)
                    ->get();
                Log::info('Inscriptions utilisateur trouvées: ' . $userFormations->count());

                // Participant associé
                $participant = \App\Models\Participant::where('paiement_id', $paiement->id)
                    ->orWhere('email', $paiement->customer_info['email'] ?? null)
                    ->first();
            } else if ($paiement->service_type === 'formation' && $paiement->customer_info['email'] ?? false) {
                // Cas 2: Visiteur sans compte
                Log::info('Recherche du participant par email: ' . $paiement->customer_info['email']);

                $participant = \App\Models\Participant::where('email', $paiement->customer_info['email'])
                    ->where('formation_id', $paiement->service_id)
                    ->first();

                if ($participant) {
                    Log::info('Participant trouvé: ID ' . $participant->id);
                }
            }

            Log::info('Paiement chargé avec succès');
            Log::info('Statut: ' . $paiement->status);
            Log::info('Montant: ' . $paiement->amount . ' ' . $paiement->currency);
            Log::info('Date: ' . $paiement->created_at);

            return view('admin.paiements.show', compact('paiement', 'formation', 'user', 'userFormations', 'participant'));
        } catch (\Exception $e) {
            Log::error('Erreur dans paiementsShow: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->route('admin.paiements.index')
                ->with('error', 'Erreur lors du chargement des détails du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Rembourser un paiement
     */
    public function paiementsRefund(Request $request, Paiement $paiement)
    {
        Log::info('=== DÉBUT FormationController@paiementsRefund ===');
        Log::info('Paiement ID: ' . $paiement->id);
        Log::info('Référence: ' . $paiement->reference);
        Log::info('Raison: ' . $request->input('reason', 'N/A'));

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            // Vérifier que le paiement peut être remboursé
            if ($paiement->status !== 'paid') {
                Log::warning('Paiement non payé - Statut: ' . $paiement->status);
                return redirect()->back()
                    ->with('error', 'Seuls les paiements validés peuvent être remboursés.');
            }

            if (!$paiement->stripe_payment_intent_id) {
                Log::error('ID de paiement Stripe manquant');
                return redirect()->back()
                    ->with('error', 'ID de paiement Stripe manquant. Impossible de procéder au remboursement.');
            }

            // Logique de remboursement Stripe
            $stripeService = app(\App\Services\StripeService::class);

            // Créer le remboursement
            Log::info('Création du remboursement Stripe...');
            $refund = $stripeService->createRefund($paiement->stripe_payment_intent_id, [
                'reason' => $request->reason ? 'requested_by_customer' : 'duplicate',
                'metadata' => [
                    'paiement_reference' => $paiement->reference,
                    'admin_id' => auth()->id(),
                    'reason' => $request->reason,
                ]
            ]);

            // Mettre à jour le paiement
            Log::info('Mise à jour du paiement en base de données...');
            $paiement->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_reason' => $request->reason,
                'refund_data' => json_decode(json_encode($refund), true),
            ]);

            // Mettre à jour l'accès utilisateur si c'est une formation
            if ($paiement->service_type === 'formation' && $paiement->service_id) {
                $userFormations = \App\Models\UserFormation::where('user_id', $paiement->user_id)
                    ->where('formation_id', $paiement->service_id)
                    ->get();

                foreach ($userFormations as $userFormation) {
                    $userFormation->update([
                        'status' => 'refunded',
                        'access_end' => now(),
                    ]);
                }
                Log::info('Accès utilisateur mis à jour');
            }

            // Mettre à jour le participant si c'est une formation
            if ($paiement->service_type === 'formation') {
                $participant = \App\Models\Participant::where('paiement_id', $paiement->id)->first();
                if ($participant) {
                    $participant->update([
                        'statut' => 'annule',
                        'date_fin' => now(),
                    ]);
                    Log::info('Participant mis à jour');
                }
            }

            Log::info('=== FIN FormationController@paiementsRefund - Succès ===');

            return redirect()->back()
                ->with('success', 'Le remboursement a été effectué avec succès.');
        } catch (\Exception $e) {
            Log::error('=== ERREUR FormationController@paiementsRefund ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    /**
     * Statistiques générales
     */
    public function statistiques()
    {
        Log::info('FormationController@statistiques - Affichage des statistiques');

        // Statistiques formations
        $totalFormations = Formation::count();
        $activeFormations = Formation::where('is_active', true)->count();
        $elearningFormations = Formation::where('type_formation', 'e_learning')->count();
        $presentielFormations = Formation::where('type_formation', 'presentiel')->count();

        // Statistiques paiements formations
        $totalPaiements = Paiement::where('service_type', 'formation')->count();
        $paidPaiements = Paiement::where('service_type', 'formation')->where('status', 'paid')->count();
        $pendingPaiements = Paiement::where('service_type', 'formation')->where('status', 'pending')->count();
        $refundedPaiements = Paiement::where('service_type', 'formation')->where('status', 'refunded')->count();
        $totalRevenue = Paiement::where('service_type', 'formation')->where('status', 'paid')->sum('amount');

        // Statistiques participants
        $totalParticipants = \App\Models\Participant::count();
        $confirmedParticipants = \App\Models\Participant::where('statut', 'confirme')->count();
        $pendingParticipants = \App\Models\Participant::where('statut', 'en_attente')->count();

        // Top formations
        $topFormations = Formation::withCount(['paiements' => function ($query) {
            $query->where('service_type', 'formation')
                ->where('status', 'paid');
        }])
            ->orderBy('paiements_count', 'desc')
            ->limit(5)
            ->get();

        // Paiements récents (formations uniquement)
        $recentPaiements = Paiement::with(['user'])
            ->where('service_type', 'formation')
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistiques', compact(
            'totalFormations',
            'activeFormations',
            'elearningFormations',
            'presentielFormations',
            'totalPaiements',
            'paidPaiements',
            'pendingPaiements',
            'refundedPaiements',
            'totalRevenue',
            'totalParticipants',
            'confirmedParticipants',
            'pendingParticipants',
            'topFormations',
            'recentPaiements'
        ));
    }

    /**
     * Utilisateurs inscrits aux formations
     */
    public function utilisateurs()
    {
        Log::info('FormationController@utilisateurs - Liste des utilisateurs');

        $users = \App\Models\User::withCount(['userFormations' => function ($query) {
            $query->where('status', 'active');
        }])
            ->whereHas('userFormations')
            ->orderBy('user_formations_count', 'desc')
            ->paginate(20);

        return view('admin.utilisateurs', compact('users'));
    }

    /**
     * Inscriptions aux formations
     */
    public function inscriptions()
    {
        Log::info('FormationController@inscriptions - Liste des inscriptions');

        $inscriptions = \App\Models\Participant::with(['formation', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistiques = [
            'total' => \App\Models\Participant::count(),
            'confirme' => \App\Models\Participant::where('statut', 'confirme')->count(),
            'en_attente' => \App\Models\Participant::where('statut', 'en_attente')->count(),
            'annule' => \App\Models\Participant::where('statut', 'annule')->count(),
        ];

        return view('admin.inscriptions', compact('inscriptions', 'statistiques'));
    }
}
