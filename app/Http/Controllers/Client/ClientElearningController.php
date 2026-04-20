<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ElearningForfait;
use App\Models\ElearningAcces;
use App\Models\ElearningCours;
use App\Models\ElearningQcm;
use App\Models\ElearningProgression;
use App\Models\Paiement;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ClientElearningController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

  /**
 * Page des forfaits e-learning pour les clients connectés
 */
public function index()
{
    $user = Auth::user();

    // Récupérer tous les accès e-learning de l'utilisateur (historique)
    $mesAcces = ElearningAcces::where('email', $user->email)
        ->with(['forfait', 'paiement'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Récupérer les IDs des forfaits déjà achetés (pour ne pas les afficher dans la liste des disponibles)
    $forfaitsAchetesIds = $mesAcces->pluck('forfait_id')->unique()->toArray();

    // Récupérer les forfaits actifs que l'utilisateur n'a PAS encore achetés
    $forfaits = ElearningForfait::active()
        ->ordered()
        ->whereNotIn('id', $forfaitsAchetesIds)
        ->get();

    // Vérifier si l'utilisateur a un accès actif (pour afficher le bandeau)
    $accesActif = $mesAcces->first(function ($acces) {
        return $acces->isActive();
    });

    return view('client.elearning.index', compact('forfaits', 'mesAcces', 'accesActif'));
}
    /**
     * Page d'achat d'un forfait
     */
    public function acheter($forfaitSlug)
    {
        Log::info('Client connecté - Accès page achat e-learning', ['slug' => $forfaitSlug]);

        $forfait = ElearningForfait::where('slug', $forfaitSlug)->active()->firstOrFail();
        $user = Auth::user();

        return view('client.elearning.acheter', compact('forfait', 'user'));
    }

    /**
     * Traitement de l'achat pour client connecté
     */
    public function processPayment(Request $request, $forfaitSlug)
    {
        Log::info('=== DÉBUT processPayment Client E-learning ===', [
            'forfait_slug' => $forfaitSlug,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
        ]);

        $user = Auth::user();
        $forfait = ElearningForfait::where('slug', $forfaitSlug)->active()->firstOrFail();

        $serviceData = [
            'amount' => $forfait->price,
            'service_name' => 'Forfait E-learning: ' . $forfait->name,
            'description' => $forfait->description ?? 'Accès à la plateforme e-learning DJOK PRESTIGE',
        ];

        $customerData = [
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
        ];

        $metadata = [
            'forfait_id' => $forfait->id,
            'forfait_name' => $forfait->name,
            'forfait_slug' => $forfait->slug,
            'duration_days' => $forfait->duration_days,
            'customer_email' => $user->email,
            'customer_nom' => $user->name,
            'customer_telephone' => $user->phone,
            'service_type' => 'elearning',
            'user_id' => $user->id,
            'is_authenticated' => true,
            'include_all_cours' => $forfait->include_all_cours,
            'include_all_qcms' => $forfait->include_all_qcms,
            'include_all_examens' => $forfait->include_all_examens,
            'selected_cours_ids' => json_encode($forfait->selected_cours_ids ?? []),
            'selected_qcms_ids' => json_encode($forfait->selected_qcms_ids ?? []),
            'selected_examens_ids' => json_encode($forfait->selected_examens_ids ?? []),
        ];

        $redirectUrl = route('client.elearning.dashboard');

        try {
            $paymentSession = $this->paymentService->createPaymentSession(
                'elearning',
                $serviceData,
                $customerData,
                $metadata,
                $redirectUrl
            );

            Log::info('Session de paiement créée avec succès', [
                'forfait_id' => $forfait->id,
                'session_id' => $paymentSession['session_id'],
                'reference' => $paymentSession['reference'],
                'redirect_url' => $redirectUrl,
            ]);

            return redirect()->away($paymentSession['url']);
        } catch (\Exception $e) {
            Log::error('Erreur création session paiement e-learning: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard e-learning du client (sa salle virtuelle)
     */
    public function dashboard()
    {
        Log::info('=== DÉBUT ClientElearningController::dashboard ===');

        $user = Auth::user();

        // Récupérer l'accès e-learning actif de l'utilisateur
        $acces = ElearningAcces::where('email', $user->email)
            ->where('status', 'active')
            ->where('access_end', '>', now())
            ->with(['forfait'])
            ->first();

        // Si pas d'accès actif, vérifier s'il y a un accès existant (peut-être expiré mais pas marqué)
        if (!$acces) {
            $acces = ElearningAcces::where('email', $user->email)
                ->where('status', 'active')
                ->with(['forfait'])
                ->first();

            if ($acces && $acces->access_end <= now()) {
                Log::info('Accès expiré détecté', ['acces_id' => $acces->id]);
                return redirect()->route('client.elearning.index')
                    ->with('error', 'Votre accès e-learning a expiré le ' . $acces->access_end->format('d/m/Y') . '. Veuillez renouveler votre abonnement.');
            }
        }

        if (!$acces) {
            Log::info('Aucun accès e-learning pour l\'utilisateur', ['email' => $user->email]);
            return redirect()->route('client.elearning.index')
                ->with('info', 'Vous n\'avez pas d\'accès actif à l\'e-learning. Choisissez un forfait ci-dessous.');
        }

        // Vérifier si une session existe déjà
        if (!$acces->hasActiveSession()) {
            $sessionToken = Str::random(60);
            $acces->update([
                'current_session_token' => $sessionToken,
                'current_session_start' => now(),
                'current_session_ip' => request()->ip(),
                'current_session_browser' => request()->userAgent(),
                'last_access_at' => now(),
            ]);

            \App\Models\ElearningSession::create([
                'acces_id' => $acces->id,
                'session_token' => $sessionToken,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'login_at' => now(),
                'last_activity_at' => now(),
            ]);

            session([
                'elearning_access_id' => $acces->id,
                'elearning_session_token' => $sessionToken,
            ]);
        } else {
            $acces->update(['last_access_at' => now()]);
            $this->updateSessionActivity($acces);
        }

        // Récupérer le forfait et le contenu inclus
        $forfait = $acces->forfait;

        // Synchroniser le nombre total de cours avec le forfait actuel
        $totalCours = $forfait->include_all_cours
            ? ElearningCours::active()->count()
            : count($forfait->selected_cours_ids ?? []);

        if ($acces->total_cours != $totalCours) {
            $acces->update(['total_cours' => $totalCours]);
            Log::info('Total cours synchronisé', ['acces_id' => $acces->id, 'old' => $acces->total_cours, 'new' => $totalCours]);
        }

        // Récupérer les cours inclus
        $cours = $this->getIncludedCoursFromForfait($forfait);
        $qcms = $this->getIncludedQcmsFromForfait($forfait);

        // Récupérer toutes les progressions
        $allProgressions = ElearningProgression::where('acces_id', $acces->id)->get();

        $coursIdsIncluded = $cours->pluck('id')->toArray();
        $progressions = $allProgressions->filter(function($progression) use ($coursIdsIncluded) {
            return $progression->cours_id && in_array($progression->cours_id, $coursIdsIncluded);
        })->keyBy('cours_id');

        $qcmIdsIncluded = $qcms->pluck('id')->toArray();
        $qcmsProgressions = [];
        foreach ($allProgressions as $progression) {
            if ($progression->qcm_id && in_array($progression->qcm_id, $qcmIdsIncluded)) {
                $qcmsProgressions[$progression->qcm_id] = $progression;
            }
        }

        // Séparer les QCM normaux et examens blancs
        $qcmsNormaux = collect();
        $examensBlancs = collect();

        foreach ($qcms as $qcm) {
            if ($qcm->is_examen_blanc) {
                $examensBlancs->push($qcm);
            } else {
                $qcmsNormaux->push($qcm);
            }
        }

        // Calculer les QCM complétés
        $qcmsNormauxDisponibles = collect();
        $qcmsNormauxCompletes = collect();
        $examensBlancsDisponibles = collect();
        $examensBlancsCompletes = collect();
        $allQcmsCompletes = collect();

        foreach ($qcmsNormaux as $qcm) {
            $progression = $qcmsProgressions[$qcm->id] ?? null;
            $isCompleted = $progression && $progression->qcm_completed == 1;
            if ($isCompleted) {
                $qcmsNormauxCompletes->push($qcm);
                $allQcmsCompletes->push($qcm);
            } else {
                $qcmsNormauxDisponibles->push($qcm);
            }
        }

        foreach ($examensBlancs as $examen) {
            $progression = $qcmsProgressions[$examen->id] ?? null;
            $isCompleted = $progression && $progression->qcm_completed == 1;
            if ($isCompleted) {
                $examensBlancsCompletes->push($examen);
                $allQcmsCompletes->push($examen);
            } else {
                $examensBlancsDisponibles->push($examen);
            }
        }

        $acces->progression_percentage = $acces->total_cours > 0
            ? round(($acces->cours_completed / $acces->total_cours) * 100, 1)
            : 0;

        return view('client.elearning.dashboard', compact(
            'acces', 'cours', 'progressions', 'qcmsNormaux', 'examensBlancs',
            'qcmsNormauxDisponibles', 'qcmsNormauxCompletes', 'examensBlancsDisponibles',
            'examensBlancsCompletes', 'allQcmsCompletes', 'qcmsProgressions', 'forfait'
        ));
    }

    /**
     * Voir un cours spécifique
     */
    public function showCours($coursId)
    {
        Log::info('=== DÉBUT showCours Client ===', ['cours_id' => $coursId]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            return redirect()->route('client.elearning.index')
                ->with('error', 'Accès non valide ou expiré.');
        }

        $forfait = $acces->forfait;
        if (!$this->isCoursIncludedInForfait($forfait, $coursId)) {
            return redirect()->route('client.elearning.dashboard')
                ->with('error', 'Ce cours n\'est pas inclus dans votre forfait.');
        }

        $this->updateSessionActivity($acces);
        $cours = ElearningCours::findOrFail($coursId);

        $progression = ElearningProgression::where('acces_id', $acces->id)
            ->where('cours_id', $coursId)
            ->first();

        if (!$progression) {
            $progression = ElearningProgression::create([
                'acces_id' => $acces->id,
                'cours_id' => $coursId,
                'cours_completed' => false,
            ]);
        }

        return view('client.elearning.cours', compact('acces', 'cours', 'progression'));
    }

    /**
     * Marquer un cours comme terminé
     *
     * CORRECTIONS :
     * - getValidAccess() a un fallback sur Auth::user() si la session e-learning est perdue
     * - On vérifie que le cours n'est pas déjà marqué terminé avant d'incrémenter
     */
    public function completeCours(Request $request, $coursId)
    {
        Log::info('=== completeCours ===', ['cours_id' => $coursId, 'user_id' => Auth::id()]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            Log::warning('completeCours : accès invalide', [
                'session_access_id' => session('elearning_access_id'),
                'user_email' => Auth::user()?->email,
            ]);
            return response()->json(['error' => 'Accès invalide. Veuillez recharger la page.'], 401);
        }

        $forfait = $acces->forfait;
        if (!$this->isCoursIncludedInForfait($forfait, $coursId)) {
            return response()->json(['error' => 'Cours non inclus dans votre forfait'], 403);
        }

        $this->updateSessionActivity($acces);

        $progression = ElearningProgression::where('acces_id', $acces->id)
            ->where('cours_id', $coursId)
            ->first();

        // Le cours était-il déjà marqué terminé ?
        $dejaTermine = $progression && $progression->cours_completed;

        if (!$progression) {
            // Créer la progression si elle n'existe pas encore
            $progression = ElearningProgression::create([
                'acces_id' => $acces->id,
                'cours_id' => $coursId,
                'cours_completed' => true,
                'cours_completed_at' => now(),
            ]);
        } else {
            $progression->update([
                'cours_completed' => true,
                'cours_completed_at' => now(),
            ]);
        }

        // N'incrémenter le compteur que si le cours n'était pas déjà terminé
        if (!$dejaTermine) {
            $acces->increment('cours_completed');
        }

        Log::info('Cours marqué comme terminé', [
            'cours_id' => $coursId,
            'acces_id' => $acces->id,
            'deja_termine' => $dejaTermine,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Passer un QCM
     */
    public function showQcm($qcmId)
    {
        Log::info('=== DÉBUT showQcm Client ===', ['qcm_id' => $qcmId]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            return redirect()->route('client.elearning.index')
                ->with('error', 'Accès non valide ou expiré.');
        }

        $forfait = $acces->forfait;
        if (!$this->isQcmIncludedInForfait($forfait, $qcmId)) {
            return redirect()->route('client.elearning.dashboard')
                ->with('error', 'Ce QCM n\'est pas inclus dans votre forfait.');
        }

        $this->updateSessionActivity($acces);

        try {
            $qcm = ElearningQcm::findOrFail($qcmId);

            if (empty($qcm->questions_data)) {
                Log::error('questions_data est vide pour le QCM ID: ' . $qcmId);
                throw new \Exception('Le QCM ne contient pas de questions.');
            }

            // S'assurer que questions_data est un tableau
            $questionsData = $qcm->questions_data;
            if (is_string($questionsData)) {
                $questionsData = json_decode($questionsData, true);
            }

            // Extraire les questions
            $questions = $questionsData['questions'] ?? [];
            $questionsCount = count($questions);

            Log::info('QCM chargé', [
                'qcm_id' => $qcm->id,
                'questions_count' => $questionsCount
            ]);

            return view('client.elearning.qcm', [
                'acces' => $acces,
                'qcm' => $qcm,
                'questions' => $questions,
                'questionsCount' => $questionsCount
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur showQcm: ' . $e->getMessage(), [
                'qcm_id' => $qcmId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('client.elearning.dashboard')
                ->with('error', 'Erreur lors du chargement du QCM: ' . $e->getMessage());
        }
    }

    /**
     * Soumettre un QCM
     */
    public function submitQcm(Request $request, $qcmId)
    {
        $acces = $this->getValidAccess();
        if (!$acces) {
            return response()->json(['error' => 'Accès invalide'], 401);
        }

        $forfait = $acces->forfait;
        if (!$this->isQcmIncludedInForfait($forfait, $qcmId)) {
            return response()->json(['error' => 'QCM non inclus dans votre forfait'], 403);
        }

        $this->updateSessionActivity($acces);

        try {
            $qcm = ElearningQcm::findOrFail($qcmId);
            $userAnswers = $request->input('answers', []);

            $scoreResult = $this->calculateQcmScore($qcm, $userAnswers);
            $score = $scoreResult['score'];
            $details = $scoreResult['details'];

            $progression = ElearningProgression::where('acces_id', $acces->id)
                ->where('qcm_id', $qcmId)
                ->first();

            if ($progression) {
                if ($qcm->attempts_allowed > 0 && $progression->qcm_attempts >= $qcm->attempts_allowed) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Nombre maximum de tentatives atteint.'
                    ], 403);
                }

                $attemptNumber = $progression->qcm_attempts + 1;
                $progression->update([
                    'qcm_completed' => 1,
                    'qcm_score' => $score,
                    'qcm_attempts' => $attemptNumber,
                    'qcm_completed_at' => now(),
                    'qcm_answers' => $userAnswers,
                    'qcm_details' => $details,
                ]);
            } else {
                $progressionData = [
                    'acces_id' => $acces->id,
                    'qcm_id' => $qcm->id,
                    'qcm_completed' => 1,
                    'qcm_score' => $score,
                    'qcm_attempts' => 1,
                    'qcm_completed_at' => now(),
                    'qcm_answers' => $userAnswers,
                    'qcm_details' => $details,
                ];

                if ($qcm->cours_id) {
                    $progressionData['cours_id'] = $qcm->cours_id;
                }

                $progression = ElearningProgression::create($progressionData);
            }

            $this->updateAverageScore($acces);

            return response()->json([
                'success' => true,
                'score' => round($score, 2),
                'passed' => $score >= $qcm->passing_score,
                'questions_count' => $qcm->questions_count,
                'passing_score' => $qcm->passing_score,
                'attempt_number' => $progression->qcm_attempts,
                'max_attempts' => $qcm->attempts_allowed,
                'details' => $details,
                'is_examen_blanc' => $qcm->is_examen_blanc,
                'allow_multiple_correct' => $qcm->allow_multiple_correct,
                'redirect' => route('client.elearning.dashboard')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur submitQcm: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion de l'e-learning
     */
    public function logout()
    {
        if (session('elearning_access_id')) {
            $acces = ElearningAcces::find(session('elearning_access_id'));
            if ($acces) {
                $acces->update([
                    'current_session_token' => null,
                    'current_session_start' => null,
                    'current_session_ip' => null,
                ]);
            }

            \App\Models\ElearningSession::where('session_token', session('elearning_session_token'))
                ->update(['logout_at' => now()]);
        }

        session()->forget([
            'elearning_access_id',
            'elearning_session_token',
        ]);

        return redirect()->route('client.elearning.index')
            ->with('success', 'Déconnexion de l\'e-learning réussie.');
    }

    // ==============================================
    // MÉTHODES PRIVÉES
    // ==============================================

    private function updateSessionActivity($acces)
    {
        $session = \App\Models\ElearningSession::where('session_token', session('elearning_session_token'))
            ->whereNull('logout_at')
            ->first();

        if ($session) {
            $session->update(['last_activity_at' => now()]);
        }
    }

    /**
     * Récupère l'accès valide de l'utilisateur connecté.
     *
     * CORRECTION : Fallback sur Auth::user() si la session e-learning
     * (elearning_access_id / elearning_session_token) a expiré ou est absente.
     * Cela évite le 401 lors des requêtes AJAX après une reconnexion implicite.
     */
    private function getValidAccess()
    {
        $accessId    = session('elearning_access_id');
        $sessionToken = session('elearning_session_token');

        // --- Chemin nominal : session e-learning présente ---
        if ($accessId && $sessionToken) {
            $acces = ElearningAcces::find($accessId);

            if ($acces && $acces->current_session_token === $sessionToken && $acces->isActive()) {
                $acces->update(['last_access_at' => now()]);
                $this->updateSessionActivity($acces);
                return $acces;
            }

            // Session e-learning incohérente → on la supprime et on tente le fallback
            Log::warning('Session e-learning incohérente, tentative de fallback Auth', [
                'access_id'    => $accessId,
                'token_match'  => $acces ? ($acces->current_session_token === $sessionToken) : false,
                'is_active'    => $acces ? $acces->isActive() : false,
            ]);

            session()->forget(['elearning_access_id', 'elearning_session_token']);
        }

        // --- Fallback : retrouver l'accès via l'utilisateur Auth connecté ---
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $acces = ElearningAcces::where('email', $user->email)
            ->where('status', 'active')
            ->where('access_end', '>', now())
            ->first();

        if (!$acces) {
            Log::info('getValidAccess fallback : aucun accès actif pour', ['email' => $user->email]);
            return null;
        }

        // Recréer la session e-learning à la volée
        $sessionToken = Str::random(60);
        $acces->update([
            'current_session_token' => $sessionToken,
            'current_session_start' => now(),
            'current_session_ip'    => request()->ip(),
            'current_session_browser' => request()->userAgent(),
            'last_access_at'        => now(),
        ]);

        \App\Models\ElearningSession::create([
            'acces_id'         => $acces->id,
            'session_token'    => $sessionToken,
            'ip_address'       => request()->ip(),
            'user_agent'       => request()->userAgent(),
            'login_at'         => now(),
            'last_activity_at' => now(),
        ]);

        session([
            'elearning_access_id'      => $acces->id,
            'elearning_session_token'  => $sessionToken,
        ]);

        Log::info('getValidAccess : session e-learning recréée via fallback Auth', [
            'acces_id' => $acces->id,
            'email'    => $user->email,
        ]);

        return $acces;
    }

    private function getIncludedCoursFromForfait($forfait)
    {
        if ($forfait->include_all_cours) {
            return ElearningCours::active()->ordered()->get();
        }

        if (empty($forfait->selected_cours_ids)) {
            return collect();
        }

        return ElearningCours::active()
            ->whereIn('id', $forfait->selected_cours_ids)
            ->ordered()
            ->get();
    }

    private function getIncludedQcmsFromForfait($forfait)
    {
        $qcms = collect();

        if ($forfait->includes_qcm) {
            if ($forfait->include_all_qcms) {
                $qcms = $qcms->merge(
                    ElearningQcm::active()
                        ->where('is_examen_blanc', false)
                        ->get()
                );
            } elseif (!empty($forfait->selected_qcms_ids)) {
                $qcms = $qcms->merge(
                    ElearningQcm::active()
                        ->where('is_examen_blanc', false)
                        ->whereIn('id', $forfait->selected_qcms_ids)
                        ->get()
                );
            }
        }

        if ($forfait->includes_examens_blancs) {
            if ($forfait->include_all_examens) {
                $qcms = $qcms->merge(
                    ElearningQcm::active()
                        ->where('is_examen_blanc', true)
                        ->get()
                );
            } elseif (!empty($forfait->selected_examens_ids)) {
                $qcms = $qcms->merge(
                    ElearningQcm::active()
                        ->where('is_examen_blanc', true)
                        ->whereIn('id', $forfait->selected_examens_ids)
                        ->get()
                );
            }
        }

        return $qcms;
    }

    private function isCoursIncludedInForfait($forfait, $coursId): bool
    {
        if ($forfait->include_all_cours) {
            return true;
        }
        return in_array($coursId, $forfait->selected_cours_ids ?? []);
    }

    private function isQcmIncludedInForfait($forfait, $qcmId): bool
    {
        $qcm = ElearningQcm::find($qcmId);
        if (!$qcm) return false;

        if ($qcm->is_examen_blanc) {
            if (!$forfait->includes_examens_blancs) return false;
            if ($forfait->include_all_examens) return true;
            return in_array($qcmId, $forfait->selected_examens_ids ?? []);
        } else {
            if (!$forfait->includes_qcm) return false;
            if ($forfait->include_all_qcms) return true;
            return in_array($qcmId, $forfait->selected_qcms_ids ?? []);
        }
    }

    private function calculateQcmScore($qcm, array $userAnswers): array
    {
        $questions = $qcm->questions_data['questions'] ?? [];
        $allowMultiple = $qcm->allow_multiple_correct;

        $correctQuestions = 0;
        $details = [];

        foreach ($questions as $index => $question) {
            $questionId = $question['id'] ?? $index;

            $correctAnswers = [];
            if ($allowMultiple && isset($question['correct_answers'])) {
                $correctAnswers = is_array($question['correct_answers'])
                    ? $question['correct_answers']
                    : [$question['correct_answers']];
            } elseif (isset($question['correct_answer'])) {
                $correctAnswers = [$question['correct_answer']];
            }

            $userAnswer = $userAnswers[$questionId] ?? null;
            $isCorrect = false;

            if ($allowMultiple) {
                $userSelections = is_array($userAnswer) ? $userAnswer : [$userAnswer];
                $userSelections = array_filter($userSelections);
                $correctSelections = array_filter($correctAnswers);

                if (!empty($correctSelections)) {
                    $correctCount = count(array_intersect($userSelections, $correctSelections));
                    $wrongCount = count(array_diff($userSelections, $correctSelections));
                    $isCorrect = ($correctCount === count($correctSelections) && $wrongCount === 0);
                }
            } else {
                $correctAnswer = $correctAnswers[0] ?? '';
                $isCorrect = ($userAnswer === $correctAnswer && !empty($userAnswer));
            }

            if ($isCorrect) {
                $correctQuestions++;
            }

            $details[] = [
                'question_index' => $index + 1,
                'question' => $question['text'] ?? 'Question ' . ($index + 1),
                'correct' => $isCorrect,
                'user_answer' => $userAnswer,
                'correct_answer' => $allowMultiple ? $correctAnswers : ($correctAnswers[0] ?? ''),
                'explanation' => $question['explanation'] ?? '',
            ];
        }

        $totalQuestions = count($questions);
        $score = $totalQuestions > 0 ? ($correctQuestions / $totalQuestions) * 100 : 0;

        return [
            'score' => $score,
            'details' => $details,
        ];
    }

    private function updateAverageScore($acces)
    {
        $progressions = ElearningProgression::where('acces_id', $acces->id)
            ->whereNotNull('qcm_score')
            ->get();

        if ($progressions->count() > 0) {
            $average = $progressions->avg('qcm_score');
            $acces->update(['average_qcm_score' => round($average, 2)]);
        }
    }
}
