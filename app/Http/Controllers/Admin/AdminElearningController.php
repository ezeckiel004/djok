<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElearningForfait;
use App\Models\ElearningAcces;
use App\Models\ElearningCours;
use App\Models\ElearningQcm;
use App\Models\ElearningProgression;
use App\Models\ElearningSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminElearningController extends Controller
{
    /**
     * Dashboard e-learning
     */
    public function dashboard()
    {
        Log::info('Dashboard e-learning accédé');

        $stats = [
            'total_forfaits' => ElearningForfait::count(),
            'active_forfaits' => ElearningForfait::active()->count(),
            'total_acces' => ElearningAcces::count(),
            'active_acces' => ElearningAcces::active()->count(),
            'total_cours' => ElearningCours::count(),
            'active_cours' => ElearningCours::active()->count(),
            'revenue' => \App\Models\Paiement::where('service_type', 'elearning')->paid()->sum('amount'),
            'average_progression' => $this->calculateAverageProgression(),
            'average_score' => ElearningAcces::where('status', 'active')->avg('average_qcm_score') ?? 0,
        ];

        Log::info('Statistiques calculées', ['stats' => $stats]);

        $recentAcces = ElearningAcces::with(['forfait', 'paiement'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $expiringAcces = ElearningAcces::active()
            ->where('access_end', '<', now()->addDays(7))
            ->orderBy('access_end')
            ->limit(10)
            ->get();

        return view('admin.elearning.dashboard', compact('stats', 'recentAcces', 'expiringAcces'));
    }

    /**
     * Calcule la progression moyenne en pourcentage
     */
    private function calculateAverageProgression(): float
    {
        $activeAcces = ElearningAcces::where('status', 'active')
            ->where('total_cours', '>', 0)
            ->get();

        if ($activeAcces->isEmpty()) {
            return 0;
        }

        $totalProgression = 0;
        $count = 0;

        foreach ($activeAcces as $acces) {
            if ($acces->total_cours > 0) {
                $progression = ($acces->cours_completed / $acces->total_cours) * 100;
                $totalProgression += $progression;
                $count++;
            }
        }

        return $count > 0 ? round($totalProgression / $count, 1) : 0;
    }

    /**
     * Gestion des forfaits
     */
    public function forfaits()
    {
        Log::info('Liste des forfaits accédée');

        $forfaits = ElearningForfait::withCount('acces')->orderBy('access_order')->get();
        return view('admin.elearning.forfaits.index', compact('forfaits'));
    }

    public function createForfait()
    {
        Log::info('Création de forfait - formulaire affiché');

        // Récupérer tous les cours, QCM et examens disponibles pour la sélection
        $cours = ElearningCours::active()->ordered()->get();
        $qcmsNormaux = ElearningQcm::active()->where('is_examen_blanc', false)->get();
        $examensBlancs = ElearningQcm::active()->where('is_examen_blanc', true)->get();

        return view('admin.elearning.forfaits.create', compact('cours', 'qcmsNormaux', 'examensBlancs'));
    }

    public function storeForfait(Request $request)
    {
        Log::info('Tentative de création de forfait', ['data' => $request->except(['features', 'feature_titles', 'feature_descriptions'])]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:elearning_forfaits',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_concurrent_connections' => 'required|integer|min:1',
        ]);

        Log::info('Validation passée');

        // Traitement des fonctionnalités
        $features = [];

        if ($request->filled('features')) {
            $features = json_decode($request->features, true) ?? [];
            Log::info('Fonctionnalités depuis champ JSON', ['features' => $features]);
        }

        if (empty($features) && $request->has('feature_titles')) {
            foreach ($request->feature_titles as $index => $title) {
                $title = trim($title);
                if (!empty($title)) {
                    $description = isset($request->feature_descriptions[$index])
                        ? trim($request->feature_descriptions[$index])
                        : '';

                    $features[] = [
                        'title' => $title,
                        'description' => $description
                    ];
                }
            }
            Log::info('Fonctionnalités depuis champs dynamiques', ['features' => $features]);
        }

        // Gestion du mode de sélection
        $selectionMode = $request->input('selection_mode', 'all');
        $includeAllCours = $selectionMode === 'all' ? true : false;
        $includeAllQcms = $selectionMode === 'all' ? true : ($request->boolean('include_all_qcms') ?? false);
        $includeAllExamens = $selectionMode === 'all' ? true : ($request->boolean('include_all_examens') ?? false);

        // Récupérer les IDs sélectionnés
        $selectedCoursIds = $includeAllCours ? [] : ($request->input('selected_cours_ids', []));
        $selectedQcmsIds = $includeAllQcms ? [] : ($request->input('selected_qcms_ids', []));
        $selectedExamensIds = $includeAllExamens ? [] : ($request->input('selected_examens_ids', []));

        Log::info('Sélections préparées', [
            'selection_mode' => $selectionMode,
            'cours_count' => count($selectedCoursIds),
            'qcms_count' => count($selectedQcmsIds),
            'examens_count' => count($selectedExamensIds),
        ]);

        try {
            $forfait = ElearningForfait::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'duration_days' => $request->duration_days,
                'max_concurrent_connections' => $request->max_concurrent_connections,
                'includes_qcm' => $request->boolean('includes_qcm'),
                'includes_examens_blancs' => $request->boolean('includes_examens_blancs'),
                'includes_certification' => $request->boolean('includes_certification'),
                'access_order' => $request->access_order ?? 0,
                'is_active' => $request->boolean('is_active'),
                'features' => $features,
                // Nouveaux champs
                'selected_cours_ids' => $selectedCoursIds,
                'selected_qcms_ids' => $selectedQcmsIds,
                'selected_examens_ids' => $selectedExamensIds,
                'include_all_cours' => $includeAllCours,
                'include_all_qcms' => $includeAllQcms,
                'include_all_examens' => $includeAllExamens,
            ]);

            Log::info('Forfait créé avec succès', [
                'forfait_id' => $forfait->id,
                'selection_mode' => $selectionMode,
            ]);

            // Créer le produit Stripe si nécessaire
            if ($request->boolean('create_stripe_product')) {
                Log::info('Création produit Stripe demandée');
                $this->createStripeProduct($forfait);
            }

            return redirect()->route('admin.elearning.forfaits')
                ->with('success', 'Forfait créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du forfait', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du forfait: ' . $e->getMessage());
        }
    }

    public function editForfait($id)
    {
        Log::info('Édition de forfait', ['forfait_id' => $id]);

        $forfait = ElearningForfait::findOrFail($id);

        // Récupérer tous les éléments disponibles
        $cours = ElearningCours::active()->ordered()->get();
        $qcmsNormaux = ElearningQcm::active()->where('is_examen_blanc', false)->get();
        $examensBlancs = ElearningQcm::active()->where('is_examen_blanc', true)->get();

        return view('admin.elearning.forfaits.edit', compact('forfait', 'cours', 'qcmsNormaux', 'examensBlancs'));
    }

    public function updateForfait(Request $request, $id)
    {
        Log::info('Mise à jour de forfait', ['forfait_id' => $id]);

        $forfait = ElearningForfait::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:elearning_forfaits,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_concurrent_connections' => 'required|integer|min:1',
        ]);

        // Traitement des fonctionnalités
        $features = [];

        if ($request->filled('features')) {
            $features = json_decode($request->features, true) ?? [];
            Log::info('Fonctionnalités depuis champ JSON', ['features' => $features]);
        }

        if (empty($features) && $request->has('feature_titles')) {
            foreach ($request->feature_titles as $index => $title) {
                $title = trim($title);
                if (!empty($title)) {
                    $description = isset($request->feature_descriptions[$index])
                        ? trim($request->feature_descriptions[$index])
                        : '';

                    $features[] = [
                        'title' => $title,
                        'description' => $description
                    ];
                }
            }
            Log::info('Fonctionnalités depuis champs dynamiques', ['features' => $features]);
        }

        // Gestion du mode de sélection
        $selectionMode = $request->input('selection_mode', 'all');
        $includeAllCours = $selectionMode === 'all' ? true : false;
        $includeAllQcms = $selectionMode === 'all' ? true : ($request->boolean('include_all_qcms') ?? false);
        $includeAllExamens = $selectionMode === 'all' ? true : ($request->boolean('include_all_examens') ?? false);

        // Récupérer les IDs sélectionnés
        $selectedCoursIds = $includeAllCours ? [] : ($request->input('selected_cours_ids', []));
        $selectedQcmsIds = $includeAllQcms ? [] : ($request->input('selected_qcms_ids', []));
        $selectedExamensIds = $includeAllExamens ? [] : ($request->input('selected_examens_ids', []));

        Log::info('Mise à jour des sélections', [
            'selection_mode' => $selectionMode,
            'cours_count' => count($selectedCoursIds),
            'qcms_count' => count($selectedQcmsIds),
            'examens_count' => count($selectedExamensIds),
        ]);

        try {
            $forfait->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'duration_days' => $request->duration_days,
                'max_concurrent_connections' => $request->max_concurrent_connections,
                'includes_qcm' => $request->boolean('includes_qcm'),
                'includes_examens_blancs' => $request->boolean('includes_examens_blancs'),
                'includes_certification' => $request->boolean('includes_certification'),
                'access_order' => $request->access_order ?? $forfait->access_order,
                'is_active' => $request->boolean('is_active'),
                'features' => $features,
                // Nouveaux champs
                'selected_cours_ids' => $selectedCoursIds,
                'selected_qcms_ids' => $selectedQcmsIds,
                'selected_examens_ids' => $selectedExamensIds,
                'include_all_cours' => $includeAllCours,
                'include_all_qcms' => $includeAllQcms,
                'include_all_examens' => $includeAllExamens,
            ]);

            Log::info('Forfait mis à jour avec succès', ['forfait_id' => $forfait->id]);

            // Créer le produit Stripe si demandé et si pas déjà existant
            if ($request->boolean('create_stripe_product') && !$forfait->stripe_product_id) {
                Log::info('Création produit Stripe demandée pour mise à jour');
                $this->createStripeProduct($forfait);
            }

            return redirect()->route('admin.elearning.forfaits')
                ->with('success', 'Forfait mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du forfait', [
                'forfait_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du forfait: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un forfait (suppression définitive même avec accès)
     */
    public function destroyForfait($id)
    {
        Log::info('Suppression définitive de forfait demandée', ['forfait_id' => $id]);

        $forfait = ElearningForfait::findOrFail($id);

        // Compter le nombre d'accès associés
        $accesCount = $forfait->acces()->count();

        try {
            DB::beginTransaction();

            if ($accesCount > 0) {
                Log::info('Suppression des accès associés', ['count' => $accesCount]);

                // Récupérer tous les accès avec leurs relations
                $accesList = $forfait->acces()->with(['sessions', 'progressions'])->get();

                foreach ($accesList as $acces) {
                    // Supprimer les sessions associées
                    $acces->sessions()->delete();

                    // Supprimer les progressions associées
                    $acces->progressions()->delete();

                    // Supprimer l'accès lui-même
                    $acces->delete();
                }
            }

            // Supprimer le forfait
            $forfait->delete();

            DB::commit();

            $message = 'Forfait supprimé avec succès.';
            if ($accesCount > 0) {
                $message .= " {$accesCount} accès associés ont également été supprimés.";
            }

            Log::info('Forfait supprimé définitivement', [
                'forfait_id' => $id,
                'acces_deleted' => $accesCount
            ]);

            return redirect()->route('admin.elearning.forfaits')
                ->with('warning', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du forfait', [
                'forfait_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la suppression du forfait: ' . $e->getMessage());
        }
    }

    /**
     * Gestion des accès
     */
    public function acces(Request $request)
    {
        Log::info('Liste des accès e-learning', ['filters' => $request->all()]);

        $query = ElearningAcces::with(['forfait', 'paiement']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('forfait')) {
            $query->where('forfait_id', $request->forfait);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('access_code', 'like', "%{$search}%")
                    ->orWhere('virtual_room_code', 'like', "%{$search}%");
            });
        }

        $acces = $query->orderBy('created_at', 'desc')->paginate(20);

        Log::info('Nombre d\'accès trouvés', ['count' => $acces->count()]);

        return view('admin.elearning.acces.index', compact('acces'));
    }

    public function showAcces($id)
    {
        Log::info('Consultation d\'un accès e-learning', ['acces_id' => $id]);

        $acces = ElearningAcces::with(['forfait', 'paiement', 'sessions'])
            ->findOrFail($id);

        // Calculer la progression pour cet accès
        $acces->progression_percentage = $acces->total_cours > 0
            ? round(($acces->cours_completed / $acces->total_cours) * 100, 1)
            : 0;

        // Récupérer les progressions avec cours et QCM
        $progressions = ElearningProgression::where('acces_id', $acces->id)
            ->with(['cours', 'qcm'])
            ->orderBy('qcm_completed_at', 'desc')
            ->orderBy('cours_completed_at', 'desc')
            ->get();

        // Pour chaque progression avec QCM, récupérer les réponses détaillées
        $qcmsDetails = [];
        foreach ($progressions as $progression) {
            if ($progression->qcm_completed && $progression->qcm) {
                if (!empty($progression->qcm_details)) {
                    $qcmsDetails[$progression->qcm_id] = [
                        'progression' => $progression,
                        'stats' => [
                            'total_questions' => $progression->qcm->questions_count,
                            'score' => $progression->qcm_score,
                            'passing_score' => $progression->qcm->passing_score,
                            'passed' => $progression->qcm_score >= $progression->qcm->passing_score,
                            'attempt_number' => $progression->qcm_attempts,
                            'completed_at' => $progression->qcm_completed_at,
                        ],
                        'questions' => $this->getQcmQuestionsWithAnswers($progression),
                    ];
                }
            }
        }

        Log::info('Accès consulté', [
            'acces_id' => $acces->id,
            'progression' => $acces->progression_percentage,
            'qcms_completed' => count($qcmsDetails)
        ]);

        return view('admin.elearning.acces.show', compact('acces', 'progressions', 'qcmsDetails'));
    }

    /**
     * Récupère les questions d'un QCM avec les réponses de l'utilisateur
     */
    private function getQcmQuestionsWithAnswers(ElearningProgression $progression): array
    {
        $questions = [];

        if (!$progression->qcm || empty($progression->qcm->questions_data)) {
            return $questions;
        }

        $qcmQuestions = $progression->qcm->questions_data['questions'] ?? [];
        $userAnswers = $progression->qcm_answers ?? [];
        $details = $progression->qcm_details ?? [];

        foreach ($qcmQuestions as $index => $questionData) {
            $questionId = $questionData['id'] ?? $index;
            $userAnswer = $userAnswers[$questionId] ?? null;
            $detail = $details[$index] ?? [];

            $options = [];
            if (isset($questionData['options']) && is_array($questionData['options'])) {
                foreach ($questionData['options'] as $key => $value) {
                    $options[] = [
                        'key' => $key,
                        'value' => $value,
                        'is_correct' => $this->isCorrectOption($key, $questionData),
                        'is_selected' => $this->isOptionSelected($key, $userAnswer),
                    ];
                }
            } elseif (isset($questionData['answers']) && is_array($questionData['answers'])) {
                foreach ($questionData['answers'] as $key => $value) {
                    $options[] = [
                        'key' => $key,
                        'value' => $value,
                        'is_correct' => $this->isCorrectOption($key, $questionData),
                        'is_selected' => $this->isOptionSelected($key, $userAnswer),
                    ];
                }
            }

            $questions[] = [
                'question_number' => $index + 1,
                'question' => $questionData['text'] ?? 'Question ' . ($index + 1),
                'type' => $progression->qcm->allow_multiple_correct ? 'multiple' : 'single',
                'user_answer' => $userAnswer,
                'correct_answer' => $questionData['correct_answer'] ?? ($questionData['correct_answers'] ?? null),
                'is_correct' => $detail['correct'] ?? false,
                'points' => $detail['points'] ?? 0,
                'max_points' => $detail['max_points'] ?? 1,
                'explanation' => $questionData['explanation'] ?? null,
                'options' => $options,
            ];
        }

        return $questions;
    }

    /**
     * Vérifie si une option est correcte
     */
    private function isCorrectOption(string $optionKey, array $questionData): bool
    {
        if (isset($questionData['correct_answer'])) {
            return $optionKey === $questionData['correct_answer'];
        }

        if (isset($questionData['correct_answers']) && is_array($questionData['correct_answers'])) {
            return in_array($optionKey, $questionData['correct_answers']);
        }

        return false;
    }

    /**
     * Vérifie si une option a été sélectionnée par l'utilisateur
     */
    private function isOptionSelected(string $optionKey, $userAnswer): bool
    {
        if (is_array($userAnswer)) {
            return in_array($optionKey, $userAnswer);
        }

        return $optionKey === $userAnswer;
    }

    /**
     * Afficher les détails d'un QCM spécifique
     */
    public function showQcmDetails($accesId, $qcmId)
    {
        Log::info('Consultation des détails d\'un QCM', [
            'acces_id' => $accesId,
            'qcm_id' => $qcmId
        ]);

        $acces = ElearningAcces::findOrFail($accesId);
        $progression = ElearningProgression::where('acces_id', $accesId)
            ->where('qcm_id', $qcmId)
            ->with(['qcm'])
            ->firstOrFail();

        if (!$progression->qcm_completed) {
            return back()->with('error', 'Ce QCM n\'a pas encore été complété.');
        }

        $questions = $this->getQcmQuestionsWithAnswers($progression);
        $stats = [
            'total_questions' => $progression->qcm->questions_count,
            'score' => $progression->qcm_score,
            'passing_score' => $progression->qcm->passing_score,
            'passed' => $progression->qcm_score >= $progression->qcm->passing_score,
            'attempt_number' => $progression->qcm_attempts,
            'completed_at' => $progression->qcm_completed_at,
            'is_examen_blanc' => $progression->qcm->is_examen_blanc,
            'allow_multiple_correct' => $progression->qcm->allow_multiple_correct,
        ];

        return view('admin.elearning.acces.qcm-details', compact('acces', 'progression', 'questions', 'stats'));
    }

    public function suspendAcces(Request $request, $id)
    {
        Log::info('Suspension d\'accès demandée', ['acces_id' => $id, 'reason' => $request->reason]);

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $acces = ElearningAcces::findOrFail($id);

        if ($acces->current_session_token) {
            $acces->sessions()
                ->where('session_token', $acces->current_session_token)
                ->update(['logout_at' => now(), 'forced_logout' => true]);
            Log::info('Session terminée pour suspension', ['acces_id' => $id]);
        }

        $acces->update([
            'status' => 'suspended',
            'suspension_reason' => $request->reason,
            'current_session_token' => null,
            'current_session_start' => null,
            'current_session_ip' => null,
            'current_session_browser' => null,
        ]);

        Log::info('Accès suspendu avec succès', ['acces_id' => $id]);

        return redirect()->route('admin.elearning.acces.show', $acces->id)
            ->with('success', 'Accès suspendu avec succès.');
    }

    public function activateAcces($id)
    {
        Log::info('Activation d\'accès demandée', ['acces_id' => $id]);

        $acces = ElearningAcces::findOrFail($id);

        $acces->update([
            'status' => 'active',
            'suspension_reason' => null,
        ]);

        Log::info('Accès réactivé avec succès', ['acces_id' => $id]);

        return back()->with('success', 'Accès réactivé avec succès.');
    }

    public function extendAcces(Request $request, $id)
    {
        Log::info('Prolongation d\'accès demandée', ['acces_id' => $id, 'days' => $request->additional_days]);

        $request->validate([
            'additional_days' => 'required|integer|min:1|max:365',
        ]);

        $acces = ElearningAcces::findOrFail($id);

        $newEndDate = max($acces->access_end, now())->addDays($request->additional_days);

        $acces->update([
            'access_end' => $newEndDate,
            'status' => 'active',
        ]);

        Log::info('Accès prolongé avec succès', [
            'acces_id' => $id,
            'new_end_date' => $newEndDate
        ]);

        return back()->with('success', 'Accès prolongé de ' . $request->additional_days . ' jours.');
    }

    /**
     * Gestion des cours
     */
    public function cours()
    {
        Log::info('Liste des cours e-learning');

        $cours = ElearningCours::withCount('qcms')->orderBy('order')->get();
        return view('admin.elearning.cours.index', compact('cours'));
    }

    public function createCours()
    {
        Log::info('Création de cours - formulaire affiché');
        return view('admin.elearning.cours.create');
    }

    public function storeCours(Request $request)
    {
        Log::info('Tentative de création de cours', ['data' => $request->except(['content', 'video_file', 'pdf_file'])]);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:elearning_cours',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,mkv|max:102400',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
            'duration_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer',
        ]);

        try {
            $coursData = [
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'content' => $request->content,
                'video_path' => null,
                'pdf_path' => null,
                'video_name' => $request->video_name,
                'pdf_name' => $request->pdf_name,
                'duration_minutes' => $request->duration_minutes ?? 0,
                'order' => $request->order ?? 0,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('video_file')) {
                $videoFile = $request->file('video_file');
                $videoName = $request->video_name ?: $videoFile->getClientOriginalName();
                $videoPath = $videoFile->store('elearning/videos', 'public');

                $coursData['video_path'] = $videoPath;
                $coursData['video_name'] = $videoName;
                Log::info('Vidéo uploadée', ['path' => $videoPath, 'name' => $videoName]);
            }

            if ($request->hasFile('pdf_file')) {
                $pdfFile = $request->file('pdf_file');
                $pdfName = $request->pdf_name ?: $pdfFile->getClientOriginalName();
                $pdfPath = $pdfFile->store('elearning/pdfs', 'public');

                $coursData['pdf_path'] = $pdfPath;
                $coursData['pdf_name'] = $pdfName;
                Log::info('PDF uploadé', ['path' => $pdfPath, 'name' => $pdfName]);
            }

            $cours = ElearningCours::create($coursData);
            Log::info('Cours créé avec succès', ['cours_id' => $cours->id]);

            return redirect()->route('admin.elearning.cours')
                ->with('success', 'Cours créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du cours', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du cours: ' . $e->getMessage());
        }
    }

    public function editCours($id)
    {
        Log::info('Édition de cours', ['cours_id' => $id]);

        $cours = ElearningCours::findOrFail($id);
        return view('admin.elearning.cours.edit', compact('cours'));
    }

    public function updateCours(Request $request, $id)
    {
        Log::info('Mise à jour de cours', ['cours_id' => $id]);

        $cours = ElearningCours::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:elearning_cours,slug,' . $id,
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,mkv|max:102400',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        try {
            $updateData = [
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'content' => $request->content,
                'duration_minutes' => $request->duration_minutes ?? $cours->duration_minutes,
                'order' => $request->order ?? $cours->order,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('video_name')) {
                $updateData['video_name'] = $request->video_name;
            }

            if ($request->filled('pdf_name')) {
                $updateData['pdf_name'] = $request->pdf_name;
            }

            if ($request->has('remove_video') && $cours->video_path) {
                if (Storage::disk('public')->exists($cours->video_path)) {
                    Storage::disk('public')->delete($cours->video_path);
                }
                $updateData['video_path'] = null;
                $updateData['video_name'] = null;
                Log::info('Vidéo supprimée', ['cours_id' => $id]);
            }

            if ($request->has('remove_pdf') && $cours->pdf_path) {
                if (Storage::disk('public')->exists($cours->pdf_path)) {
                    Storage::disk('public')->delete($cours->pdf_path);
                }
                $updateData['pdf_path'] = null;
                $updateData['pdf_name'] = null;
                Log::info('PDF supprimé', ['cours_id' => $id]);
            }

            if ($request->hasFile('video_file')) {
                if ($cours->video_path && Storage::disk('public')->exists($cours->video_path)) {
                    Storage::disk('public')->delete($cours->video_path);
                }

                $videoFile = $request->file('video_file');
                $videoName = $request->video_name ?: $videoFile->getClientOriginalName();
                $videoPath = $videoFile->store('elearning/videos', 'public');

                $updateData['video_path'] = $videoPath;
                $updateData['video_name'] = $videoName;
                Log::info('Vidéo mise à jour', ['path' => $videoPath, 'name' => $videoName]);
            }

            if ($request->hasFile('pdf_file')) {
                if ($cours->pdf_path && Storage::disk('public')->exists($cours->pdf_path)) {
                    Storage::disk('public')->delete($cours->pdf_path);
                }

                $pdfFile = $request->file('pdf_file');
                $pdfName = $request->pdf_name ?: $pdfFile->getClientOriginalName();
                $pdfPath = $pdfFile->store('elearning/pdfs', 'public');

                $updateData['pdf_path'] = $pdfPath;
                $updateData['pdf_name'] = $pdfName;
                Log::info('PDF mis à jour', ['path' => $pdfPath, 'name' => $pdfName]);
            }

            $cours->update($updateData);
            Log::info('Cours mis à jour avec succès', ['cours_id' => $cours->id]);

            return redirect()->route('admin.elearning.cours')
                ->with('success', 'Cours mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du cours', [
                'cours_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du cours: ' . $e->getMessage());
        }
    }

    public function destroyCours($id)
    {
        Log::info('Suppression de cours demandée', ['cours_id' => $id]);

        $cours = ElearningCours::findOrFail($id);

        try {
            if ($cours->video_path && Storage::disk('public')->exists($cours->video_path)) {
                Storage::disk('public')->delete($cours->video_path);
            }
            if ($cours->pdf_path && Storage::disk('public')->exists($cours->pdf_path)) {
                Storage::disk('public')->delete($cours->pdf_path);
            }

            $cours->delete();
            Log::info('Cours supprimé avec succès', ['cours_id' => $id]);

            return redirect()->route('admin.elearning.cours')
                ->with('success', 'Cours supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du cours', [
                'cours_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la suppression du cours: ' . $e->getMessage());
        }
    }

    /**
     * Gestion des QCM
     */
    public function qcms(Request $request)
    {
        Log::info('Liste des QCM e-learning', ['filters' => $request->all()]);

        $query = ElearningQcm::with('cours');

        if ($request->has('type')) {
            if ($request->type === 'examen') {
                $query->where('is_examen_blanc', true);
            } elseif ($request->type === 'qcm') {
                $query->where('is_examen_blanc', false);
            }
        }

        if ($request->has('cours')) {
            $query->where('cours_id', $request->cours);
        }

        $qcms = $query->orderBy('created_at', 'desc')->get();
        $cours = ElearningCours::active()->get();

        return view('admin.elearning.qcms.index', compact('qcms', 'cours'));
    }

    public function createQcm()
    {
        Log::info('Création de QCM - formulaire affiché');

        $cours = ElearningCours::active()->get();
        return view('admin.elearning.qcms.create', compact('cours'));
    }

    public function show($id)
    {
        Log::info('Consultation d\'un QCM', ['qcm_id' => $id]);

        $qcm = ElearningQcm::with(['cours', 'progressions'])->findOrFail($id);

        $questions = $qcm->questions;

        $qcm->stats = [
            'progressions_count' => $qcm->progressions->count(),
            'average_score' => $qcm->progressions->where('qcm_completed', true)->avg('qcm_score') ?? 0,
            'completion_rate' => $qcm->progressions->count() > 0
                ? round(($qcm->progressions->where('qcm_completed', true)->count() / $qcm->progressions->count()) * 100, 1)
                : 0,
        ];

        return view('admin.elearning.qcms.show', compact('qcm', 'questions'));
    }

    public function editQcm($id)
    {
        Log::info('Édition de QCM', ['qcm_id' => $id]);

        $qcm = ElearningQcm::findOrFail($id);
        $cours = ElearningCours::active()->get();

        return view('admin.elearning.qcms.edit', compact('qcm', 'cours'));
    }

    public function updateQcm(Request $request, $id)
    {
        Log::info('Mise à jour de QCM', ['qcm_id' => $id]);

        $qcm = ElearningQcm::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'questions_data' => 'required|json',
            'questions_count' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $questionsData = json_decode($request->questions_data, true);
        if (!isset($questionsData['questions']) || !is_array($questionsData['questions'])) {
            Log::warning('Format JSON invalide pour QCM');
            return back()->withErrors(['questions_data' => 'Format JSON invalide.']);
        }

        try {
            $qcm->update([
                'cours_id' => $request->cours_id ?: null,
                'title' => $request->title,
                'description' => $request->description,
                'questions_count' => $request->questions_count,
                'passing_score' => $request->passing_score,
                'time_limit_minutes' => $request->time_limit_minutes,
                'attempts_allowed' => $request->attempts_allowed ?? 3,
                'is_examen_blanc' => $request->boolean('is_examen_blanc'),
                'allow_multiple_correct' => $request->boolean('allow_multiple_correct'),
                'is_active' => $request->boolean('is_active'),
                'questions_data' => $questionsData,
            ]);

            Log::info('QCM mis à jour avec succès', ['qcm_id' => $qcm->id]);

            return redirect()->route('admin.elearning.qcms')
                ->with('success', 'QCM mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du QCM', [
                'qcm_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du QCM: ' . $e->getMessage());
        }
    }

    public function storeQcm(Request $request)
    {
        Log::info('Tentative de création de QCM', ['data' => $request->except('questions_data')]);

        $request->validate([
            'title' => 'required|string|max:255',
            'questions_data' => 'required|json',
            'questions_count' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $questionsData = json_decode($request->questions_data, true);
        if (!isset($questionsData['questions']) || !is_array($questionsData['questions'])) {
            Log::warning('Format JSON invalide pour QCM');
            return back()->withErrors(['questions_data' => 'Format JSON invalide.']);
        }

        try {
            $qcm = ElearningQcm::create([
                'cours_id' => $request->cours_id ?: null,
                'title' => $request->title,
                'description' => $request->description,
                'questions_count' => $request->questions_count,
                'passing_score' => $request->passing_score,
                'time_limit_minutes' => $request->time_limit_minutes,
                'attempts_allowed' => $request->attempts_allowed ?? 3,
                'is_examen_blanc' => $request->boolean('is_examen_blanc'),
                'allow_multiple_correct' => $request->boolean('allow_multiple_correct'),
                'is_active' => $request->boolean('is_active'),
                'questions_data' => $questionsData,
            ]);

            Log::info('QCM créé avec succès', ['qcm_id' => $qcm->id]);

            return redirect()->route('admin.elearning.qcms')
                ->with('success', 'QCM créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du QCM', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du QCM: ' . $e->getMessage());
        }
    }

    public function destroyQcm($id)
    {
        Log::info('Suppression de QCM demandée', ['qcm_id' => $id]);

        $qcm = ElearningQcm::findOrFail($id);

        if ($qcm->progressions()->exists()) {
            Log::warning('Impossible de supprimer le QCM - progressions associées existent', ['qcm_id' => $id]);
            return back()->with('error', 'Impossible de supprimer ce QCM car il y a des progressions associées.');
        }

        try {
            $qcm->delete();
            Log::info('QCM supprimé avec succès', ['qcm_id' => $id]);

            return redirect()->route('admin.elearning.qcms')
                ->with('success', 'QCM supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du QCM', [
                'qcm_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la suppression du QCM: ' . $e->getMessage());
        }
    }

    /**
     * Upload de certification
     */
    public function uploadCertification(Request $request, $accesId)
    {
        Log::info('Upload de certification demandé', ['acces_id' => $accesId]);

        $request->validate([
            'certification_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $acces = ElearningAcces::findOrFail($accesId);

        try {
            $path = $request->file('certification_file')->store('certifications', 'public');

            $acces->update([
                'certification_file_path' => $path,
                'certification_sent_at' => now(),
            ]);

            Log::info('Certification téléchargée avec succès', [
                'acces_id' => $accesId,
                'file_path' => $path
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($acces->email)->send(new \App\Mail\ElearningCertificationMail($acces));
                Log::info('Email de certification envoyé avec succès à: ' . $acces->email);

                $message = 'Certification téléchargée et envoyée avec succès à ' . $acces->email;
            } catch (\Exception $emailError) {
                Log::error('Erreur lors de l\'envoi de l\'email de certification', [
                    'acces_id' => $accesId,
                    'email' => $acces->email,
                    'error' => $emailError->getMessage()
                ]);

                $message = 'Certification téléchargée mais erreur lors de l\'envoi de l\'email: ' . $emailError->getMessage();
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload de la certification', [
                'acces_id' => $accesId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors du téléchargement de la certification: ' . $e->getMessage());
        }
    }

    /**
     * Statistiques
     */
    public function statistics()
    {
        Log::info('Statistiques e-learning accédées');

        $stats = [
            'total_acces' => ElearningAcces::count(),
            'active_acces' => ElearningAcces::active()->count(),
            'expired_acces' => ElearningAcces::expired()->count(),
            'average_progression' => $this->calculateAverageProgression(),
            'average_score' => ElearningAcces::avg('average_qcm_score') ?? 0,
        ];

        $forfaitDistribution = ElearningAcces::selectRaw('forfait_id, count(*) as count')
            ->groupBy('forfait_id')
            ->with('forfait')
            ->get();

        $recentAcces = ElearningAcces::with(['forfait', 'paiement'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.elearning.statistics', compact('stats', 'forfaitDistribution', 'recentAcces'));
    }

    /**
     * Helper: Créer un produit Stripe
     */
    private function createStripeProduct(ElearningForfait $forfait)
    {
        try {
            Log::info('Création produit Stripe en cours', ['forfait_id' => $forfait->id]);

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $product = \Stripe\Product::create([
                'name' => $forfait->name,
                'description' => $forfait->description,
                'metadata' => [
                    'forfait_id' => $forfait->id,
                    'duration_days' => $forfait->duration_days,
                ],
            ]);

            $price = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => $forfait->price * 100,
                'currency' => 'eur',
            ]);

            $forfait->update([
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id,
            ]);

            Log::info('Produit Stripe créé avec succès', [
                'forfait_id' => $forfait->id,
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création produit Stripe', [
                'forfait_id' => $forfait->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Afficher les sessions actives e-learning
     */
    public function activeSessions()
    {
        Log::info('=== DÉBUT activeSessions ===');

        $allActiveSessions = ElearningSession::whereNull('logout_at')->count();
        Log::info('Toutes les sessions sans déconnexion: ' . $allActiveSessions);

        $recentSessions = ElearningSession::with(['acces.forfait'])
            ->where('last_activity_at', '>=', now()->subHours(2))
            ->orderBy('last_activity_at', 'desc')
            ->get();

        Log::info('Sessions avec last_activity_at >= ' . now()->subHours(2)->format('Y-m-d H:i:s') . ': ' . $recentSessions->count());

        $activeSessions = ElearningSession::with(['acces.forfait'])
            ->whereNull('logout_at')
            ->where('last_activity_at', '>=', now()->subHours(2))
            ->orderBy('last_activity_at', 'desc')
            ->get();

        Log::info('Sessions actives trouvées: ' . $activeSessions->count());

        $totalActive = $activeSessions->count();
        $inactiveSessions = ElearningSession::with(['acces'])
            ->whereNull('logout_at')
            ->where('last_activity_at', '<', now()->subMinutes(30))
            ->count();

        $avgSessionDuration = 0;
        if ($totalActive > 0) {
            $totalMinutes = 0;
            foreach ($activeSessions as $session) {
                $totalMinutes += now()->diffInMinutes($session->login_at);
            }
            $avgSessionDuration = round($totalMinutes / $totalActive);
        }

        $recentSessionsList = ElearningSession::with(['acces'])
            ->whereNotNull('logout_at')
            ->where('logout_at', '>=', now()->subDay())
            ->orderBy('logout_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_active' => $totalActive,
            'inactive_sessions' => $inactiveSessions,
            'avg_session_duration' => $avgSessionDuration,
        ];

        Log::info('Statistiques calculées', $stats);
        Log::info('=== FIN activeSessions ===');

        return view('admin.elearning.sessions.active', compact(
            'activeSessions',
            'stats',
            'recentSessionsList'
        ));
    }

    /**
     * Forcer la déconnexion d'une session
     */
    public function forceLogout($sessionId)
    {
        $session = ElearningSession::findOrFail($sessionId);

        $session->forceLogout();

        if ($session->acces) {
            $session->acces->update([
                'current_session_token' => null,
                'current_session_start' => null,
                'current_session_ip' => null,
            ]);
        }

        return back()->with('success', 'L\'utilisateur a été déconnecté avec succès.');
    }

    public function confirmDestroyAcces($id)
    {
        Log::info('Confirmation de suppression d\'accès', ['acces_id' => $id]);

        $acces = ElearningAcces::findOrFail($id);

        return view('admin.elearning.acces.confirm-destroy', compact('acces'));
    }

    /**
     * Supprimer définitivement un accès
     */
    public function destroyAcces($id)
    {
        Log::info('Suppression d\'accès demandée', ['acces_id' => $id]);

        $acces = ElearningAcces::findOrFail($id);

        try {
            if ($acces->hasActiveSession()) {
                Log::warning('Impossible de supprimer - session active', ['acces_id' => $id]);
                return back()->with('error', 'Impossible de supprimer cet accès car il y a une session active. Veuillez d\'abord forcer la déconnexion.');
            }

            if ($acces->progressions()->exists()) {
                Log::warning('Impossible de supprimer - progressions associées', ['acces_id' => $id]);
                return back()->with('error', 'Impossible de supprimer cet accès car il y a des progressions associées.');
            }

            $acces->sessions()->delete();
            $acces->delete();

            Log::info('Accès supprimé avec succès', ['acces_id' => $id]);

            return redirect()->route('admin.elearning.acces')
                ->with('success', 'Accès supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'accès', [
                'acces_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la suppression de l\'accès: ' . $e->getMessage());
        }
    }

    /**
     * Formulaire de suspension d'accès
     */
    public function suspendAccesForm($id)
    {
        Log::info('Formulaire de suspension d\'accès', ['acces_id' => $id]);

        $acces = ElearningAcces::findOrFail($id);

        return view('admin.elearning.acces.suspend', compact('acces'));
    }
}
