<?php

namespace App\Http\Controllers;

use App\Models\ElearningForfait;
use App\Models\ElearningAcces;
use App\Models\ElearningCours;
use App\Models\ElearningQcm;
use App\Models\ElearningProgression;
use App\Models\ElearningSession;
use App\Models\Paiement;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ElearningController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Page publique des forfaits e-learning
     */
    public function index()
    {
        $forfaits = ElearningForfait::active()->ordered()->get();

        return view('elearning.index', compact('forfaits'));
    }

    /**
     * Page d'achat d'un forfait
     */
    public function acheter($forfaitSlug)
    {
        Log::info('Accès page achat e-learning', ['slug' => $forfaitSlug]);

        $forfait = ElearningForfait::where('slug', $forfaitSlug)->active()->firstOrFail();

        return view('elearning.acheter', compact('forfait'));
    }

    /**
     * Traitement de l'achat
     */
    public function processPayment(Request $request, $forfaitSlug)
    {
        Log::info('=== DÉBUT processPayment E-learning ===', [
            'forfait_slug' => $forfaitSlug,
            'email' => $request->email,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson()
        ]);

        $request->validate([
            'email' => 'required|email',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        $forfait = ElearningForfait::where('slug', $forfaitSlug)->active()->firstOrFail();

        Log::info('Forfait trouvé', [
            'forfait_id' => $forfait->id,
            'name' => $forfait->name,
            'price' => $forfait->price,
            'slug' => $forfait->slug
        ]);

        $serviceData = [
            'amount' => $forfait->price,
            'service_name' => 'Forfait E-learning: ' . $forfait->name,
            'description' => $forfait->description ?? 'Accès à la plateforme e-learning DJOK PRESTIGE',
        ];

        $customerData = [
            'email' => $request->email,
            'name' => $request->prenom . ' ' . $request->nom,
            'phone' => $request->telephone,
        ];

        $metadata = [
            'forfait_id' => $forfait->id,
            'forfait_name' => $forfait->name,
            'forfait_slug' => $forfait->slug,
            'duration_days' => $forfait->duration_days,
            'customer_email' => $request->email,
            'customer_nom' => $request->nom,
            'customer_prenom' => $request->prenom,
            'customer_telephone' => $request->telephone,
            'service_type' => 'elearning',
        ];

        try {
            Log::info('Création session de paiement avec PaymentService');

            $paymentSession = $this->paymentService->createPaymentSession(
                'elearning',
                $serviceData,
                $customerData,
                $metadata
            );

            Log::info('Session de paiement créée avec succès', [
                'forfait_id' => $forfait->id,
                'session_id' => $paymentSession['session_id'],
                'reference' => $paymentSession['reference'],
                'url' => $paymentSession['url']
            ]);

            return redirect()->away($paymentSession['url']);
        } catch (\Exception $e) {
            Log::error('Erreur création session paiement e-learning: ' . $e->getMessage(), [
                'forfait_slug' => $forfaitSlug,
                'error' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création du paiement: ' . $e->getMessage()]);
        }
    }

    /**
     * Succès du paiement e-learning
     */
    public function paymentSuccess(Request $request)
    {
        Log::info('=== DÉBUT paymentSuccess e-learning ===');

        // Récupérer le session_id depuis la query string
        $sessionId = $request->get('session_id');

        Log::info('Session ID reçu:', ['session_id' => $sessionId]);

        if (!$sessionId) {
            Log::warning('Aucun session_id fourni dans paymentSuccess');
            return redirect()->route('elearning.index')
                ->with('error', 'Session de paiement invalide.');
        }

        try {
            Log::info('Recherche paiement pour session_id', ['session_id' => $sessionId]);

            // Récupérer le paiement déjà traité
            $paiement = Paiement::where('stripe_session_id', $sessionId)->first();

            if (!$paiement) {
                Log::warning('Paiement non trouvé pour session_id', ['session_id' => $sessionId]);
                return redirect()->route('elearning.index')
                    ->with('error', 'Paiement non trouvé.');
            }

            Log::info('Paiement trouvé', [
                'paiement_id' => $paiement->id,
                'reference' => $paiement->reference,
                'service_type' => $paiement->service_type,
                'elearning_forfait_id' => $paiement->elearning_forfait_id,
                'service_details' => $paiement->service_details,
            ]);

            // Vérifier si l'accès existe déjà
            $acces = ElearningAcces::where('paiement_id', $paiement->id)->first();

            if (!$acces) {
                Log::info('Création accès e-learning manquante');

                // Créer l'accès à partir des données du paiement
                $acces = $this->createElearningAccessFromPaiement($paiement);
            } else {
                Log::info('Accès e-learning existe déjà', ['acces_id' => $acces->id]);
            }

            Log::info('Accès e-learning récupéré/créé avec succès', ['acces_id' => $acces->id]);

            return view('elearning.success', compact('paiement', 'acces'))
                ->with('success', 'Votre achat a été confirmé ! Vous recevrez vos codes d\'accès par email.');
        } catch (\Exception $e) {
            Log::error('Erreur traitement paiement e-learning: ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('elearning.index')
                ->with('error', 'Erreur lors du traitement de votre achat.');
        }
    }

    /**
     * Créer un accès e-learning à partir d'un paiement
     */
    private function createElearningAccessFromPaiement(Paiement $paiement)
    {
        Log::info('=== DÉBUT createElearningAccessFromPaiement ===');
        Log::info('Données du paiement:', [
            'paiement_id' => $paiement->id,
            'elearning_forfait_id' => $paiement->elearning_forfait_id,
            'service_type' => $paiement->service_type,
            'service_details' => $paiement->service_details,
        ]);

        // Essayer de récupérer le forfait ID de différentes manières
        $forfaitId = $paiement->elearning_forfait_id;

        if (!$forfaitId) {
            Log::warning('elearning_forfait_id est NULL, recherche dans service_details');

            // Essayer de récupérer depuis service_details
            $serviceDetails = is_array($paiement->service_details)
                ? $paiement->service_details
                : json_decode($paiement->service_details, true);

            $forfaitId = $serviceDetails['forfait_id'] ?? null;

            if ($forfaitId) {
                Log::info('Forfait ID trouvé dans service_details: ' . $forfaitId);
                // Mettre à jour le paiement avec le forfait_id
                $paiement->update(['elearning_forfait_id' => $forfaitId]);
                Log::info('Paiement mis à jour avec elearning_forfait_id: ' . $forfaitId);
            }
        }

        if (!$forfaitId) {
            Log::error('Forfait ID manquant dans le paiement');
            throw new \Exception('Forfait ID manquant dans le paiement');
        }

        Log::info('Forfait ID à chercher: ' . $forfaitId);

        $forfait = ElearningForfait::find($forfaitId);

        if (!$forfait) {
            Log::error('Forfait non trouvé avec ID: ' . $forfaitId);
            throw new \Exception('Forfait non trouvé: ' . $forfaitId);
        }

        Log::info('Forfait trouvé:', [
            'id' => $forfait->id,
            'name' => $forfait->name,
            'duration_days' => $forfait->duration_days,
        ]);

        // Récupérer les informations client
        $customerInfo = is_array($paiement->customer_info)
            ? $paiement->customer_info
            : json_decode($paiement->customer_info, true);

        $serviceDetails = is_array($paiement->service_details)
            ? $paiement->service_details
            : json_decode($paiement->service_details, true);

        Log::info('Customer info:', $customerInfo);
        Log::info('Service details:', $serviceDetails);

        $email = $customerInfo['email'] ?? $serviceDetails['customer_email'] ?? null;
        $nom = $customerInfo['name'] ?? $serviceDetails['customer_nom'] ?? 'Client';
        $prenom = $serviceDetails['customer_prenom'] ?? '';
        $telephone = $serviceDetails['customer_telephone'] ?? null;

        Log::info('Données client extraites:', [
            'email' => $email,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
        ]);

        if (!$email) {
            Log::error('Email client manquant');
            throw new \Exception('Email client manquant');
        }

        // Séparer nom et prénom si nécessaire
        if (empty($prenom) && strpos($nom, ' ') !== false) {
            $parts = explode(' ', $nom, 2);
            $prenom = $parts[0] ?? '';
            $nom = $parts[1] ?? $nom;
            Log::info('Nom séparé:', ['prenom' => $prenom, 'nom' => $nom]);
        }

        // Générer les codes d'accès
        $accessCode = Str::upper(Str::random(10));
        $virtualRoomCode = 'ROOM-' . Str::upper(Str::random(8));

        // Dates d'accès
        $accessStart = now();
        $accessEnd = now()->addDays($forfait->duration_days);

        Log::info('Création accès e-learning avec données:', [
            'forfait_id' => $forfait->id,
            'paiement_id' => $paiement->id,
            'email' => $email,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'access_code' => $accessCode,
            'virtual_room_code' => $virtualRoomCode,
            'access_start' => $accessStart->format('Y-m-d H:i:s'),
            'access_end' => $accessEnd->format('Y-m-d H:i:s'),
        ]);

        // Créer l'accès
        $acces = ElearningAcces::create([
            'forfait_id' => $forfait->id,
            'paiement_id' => $paiement->id,
            'email' => $email,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'access_code' => $accessCode,
            'virtual_room_code' => $virtualRoomCode,
            'access_start' => $accessStart,
            'access_end' => $accessEnd,
            'total_cours' => ElearningCours::active()->count(),
            'status' => 'active',
        ]);

        Log::info('Accès e-learning créé:', [
            'acces_id' => $acces->id,
            'access_code' => $accessCode,
            'virtual_room_code' => $virtualRoomCode,
            'email' => $email,
        ]);

        // Envoyer l'email avec les codes d'accès
        $this->sendElearningAccessEmail($acces, $forfait);

        Log::info('=== FIN createElearningAccessFromPaiement - Succès ===');

        return $acces;
    }

    /**
     * Envoyer l'email d'accès e-learning
     */
    private function sendElearningAccessEmail(ElearningAcces $acces, ElearningForfait $forfait)
    {
        try {
            Log::info('Envoi email d\'accès à: ' . $acces->email);
            Mail::to($acces->email)->send(new \App\Mail\ElearningAccessMail($acces, $forfait));
            Log::info('Email d\'accès e-learning envoyé à: ' . $acces->email);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email e-learning: ' . $e->getMessage());
        }
    }

    /**
     * Accès à la salle virtuelle (page de login avec code)
     */
    public function salle()
    {
        return view('elearning.salle');
    }

    /**
     * Connexion à la salle virtuelle
     */
    public function login(Request $request)
    {
        Log::info('=== DÉBUT login e-learning ===', [
            'email' => $request->email,
            'has_access_code' => !empty($request->access_code)
        ]);

        $request->validate([
            'access_code' => 'required|string|max:20',
            'email' => 'required|email',
        ]);

        $acces = ElearningAcces::where('access_code', $request->access_code)
            ->where('email', $request->email)
            ->first();

        if (!$acces) {
            Log::warning('Accès non trouvé', [
                'email' => $request->email,
                'access_code' => $request->access_code
            ]);
            return back()->withErrors([
                'error' => 'Code d\'accès ou email incorrect.'
            ]);
        }

        if (!$acces->isActive()) {
            Log::warning('Accès non actif', ['acces_id' => $acces->id, 'status' => $acces->status]);
            return back()->withErrors([
                'error' => 'Votre accès a expiré ou a été suspendu.'
            ]);
        }

        if ($acces->hasActiveSession()) {
            Log::warning('Session déjà active', ['acces_id' => $acces->id]);
            return back()->withErrors([
                'error' => 'Une session est déjà active avec ce compte.'
            ]);
        }

        $sessionToken = Str::random(60);

        $acces->update([
            'current_session_token' => $sessionToken,
            'current_session_start' => now(),
            'current_session_ip' => $request->ip(),
            'current_session_browser' => $request->userAgent(),
            'last_access_at' => now(),
        ]);

        ElearningSession::create([
            'acces_id' => $acces->id,
            'session_token' => $sessionToken,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'last_activity_at' => now(),
        ]);

        session([
            'elearning_access_id' => $acces->id,
            'elearning_session_token' => $sessionToken,
            'elearning_virtual_room' => $acces->virtual_room_code,
        ]);

        Log::info('Connexion réussie', [
            'acces_id' => $acces->id,
            'session_token' => $sessionToken,
            'virtual_room_code' => $acces->virtual_room_code
        ]);

        return redirect()->route('elearning.virtual-room');
    }

    /**
     * Mettre à jour l'activité d'une session
     */
    private function updateSessionActivity(ElearningAcces $acces)
    {
        $session = ElearningSession::where('session_token', session('elearning_session_token'))
            ->whereNull('logout_at')
            ->first();

        if ($session) {
            $session->update(['last_activity_at' => now()]);
            Log::debug('Activité session mise à jour', ['session_id' => $session->id]);
        } else {
            Log::warning('Session non trouvée pour mise à jour', [
                'session_token' => session('elearning_session_token'),
                'acces_id' => $acces->id
            ]);
        }
    }

    /**
     * Salle virtuelle principale - VERSION CORRIGÉE ET OPTIMISÉE
     */
    public function virtualRoom()
    {
        Log::info('=== DÉBUT virtualRoom ===');

        if (!session('elearning_access_id')) {
            Log::warning('Pas d\'ID d\'accès en session');
            return redirect()->route('elearning.salle');
        }

        $acces = ElearningAcces::find(session('elearning_access_id'));

        if (!$acces) {
            Log::error('Accès non trouvé en base', ['access_id' => session('elearning_access_id')]);
            session()->forget([
                'elearning_access_id',
                'elearning_session_token',
                'elearning_virtual_room',
            ]);
            return redirect()->route('elearning.salle');
        }

        if ($acces->current_session_token !== session('elearning_session_token')) {
            Log::warning('Token de session invalide');
            return redirect()->route('elearning.salle');
        }

        // Mettre à jour l'activité
        $this->updateSessionActivity($acces);
        $acces->update(['last_access_at' => now()]);

        // Récupérer les données
        $cours = ElearningCours::active()->ordered()->get();
        $qcms = ElearningQcm::active()->get();

        // Récupérer TOUTES les progressions de l'utilisateur en UNE SEULE REQUÊTE
        $allProgressions = ElearningProgression::where('acces_id', $acces->id)->get();

        // Indexer par cours_id pour les cours
        $progressions = $allProgressions->keyBy('cours_id');

        // Créer un tableau des progressions indexé par qcm_id
        $qcmsProgressions = [];
        foreach ($allProgressions as $progression) {
            if ($progression->qcm_id) {
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

        // Calculer les QCM complétés en utilisant les progressions
        $qcmsNormauxDisponibles = collect();
        $qcmsNormauxCompletes = collect();
        $examensBlancsDisponibles = collect();
        $examensBlancsCompletes = collect();
        $allQcmsCompletes = collect();

        // Pour les QCM normaux
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

        // Pour les examens blancs
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

        // Calculer le pourcentage de progression
        $acces->progression_percentage = $acces->total_cours > 0
            ? round(($acces->cours_completed / $acces->total_cours) * 100, 1)
            : 0;

        Log::info('Données chargées pour la salle virtuelle', [
            'cours_count' => $cours->count(),
            'qcms_normaux_disponibles_count' => $qcmsNormauxDisponibles->count(),
            'qcms_normaux_completes_count' => $qcmsNormauxCompletes->count(),
            'examens_blancs_disponibles_count' => $examensBlancsDisponibles->count(),
            'examens_blancs_completes_count' => $examensBlancsCompletes->count(),
            'qcms_completes_total_count' => $allQcmsCompletes->count()
        ]);

        // Passer toutes les variables nécessaires à la vue
        return view('elearning.virtual-room', compact(
            'acces',
            'cours',
            'progressions',
            'qcmsNormaux',
            'examensBlancs',
            'qcmsNormauxDisponibles',
            'qcmsNormauxCompletes',
            'examensBlancsDisponibles',
            'examensBlancsCompletes',
            'allQcmsCompletes',
            'qcmsProgressions' // NOUVEAU : passer cette collection à la vue
        ));
    }

    /**
     * Voir un cours spécifique
     */
    public function showCours($coursId)
    {
        Log::info('=== DÉBUT showCours ===', ['cours_id' => $coursId]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            Log::warning('Accès invalide pour showCours');
            return redirect()->route('elearning.salle');
        }

        $this->updateSessionActivity($acces);

        $cours = ElearningCours::findOrFail($coursId);

        Log::info('Cours trouvé', [
            'cours_id' => $cours->id,
            'title' => $cours->title
        ]);

        $progression = ElearningProgression::where('acces_id', $acces->id)
            ->where('cours_id', $coursId)
            ->first();

        if (!$progression) {
            $existingProgression = ElearningProgression::where('acces_id', $acces->id)
                ->where('cours_id', $coursId)
                ->first();

            if ($existingProgression) {
                $progression = $existingProgression;
                Log::info('Progression existante trouvée', ['progression_id' => $progression->id]);
            } else {
                $progression = ElearningProgression::create([
                    'acces_id' => $acces->id,
                    'cours_id' => $coursId,
                    'cours_completed' => false,
                ]);
                Log::info('Nouvelle progression créée pour le cours', ['progression_id' => $progression->id]);
            }
        }

        return view('elearning.cours', compact('acces', 'cours', 'progression'));
    }

    /**
     * Marquer un cours comme terminé
     */
    public function completeCours(Request $request, $coursId)
    {
        Log::info('=== DÉBUT completeCours ===', ['cours_id' => $coursId]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            Log::warning('Accès invalide pour completeCours');
            return response()->json(['error' => 'Accès invalide'], 401);
        }

        $this->updateSessionActivity($acces);

        $progression = ElearningProgression::where('acces_id', $acces->id)
            ->where('cours_id', $coursId)
            ->first();

        if (!$progression) {
            Log::warning('Progression non trouvée pour le cours');
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

        $acces->increment('cours_completed');
        $acces->update(['total_cours' => ElearningCours::active()->count()]);

        Log::info('Cours marqué comme terminé', [
            'acces_id' => $acces->id,
            'cours_completed_count' => $acces->cours_completed
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Passer un QCM
     */
    public function showQcm($qcmId)
    {
        Log::info('=== DÉBUT showQcm ===', ['qcm_id' => $qcmId]);

        $acces = $this->getValidAccess();
        if (!$acces) {
            Log::error('Accès invalide pour showQcm');
            return redirect()->route('elearning.salle');
        }

        $this->updateSessionActivity($acces);

        try {
            $qcm = ElearningQcm::findOrFail($qcmId);

            Log::info('QCM trouvé', [
                'qcm_id' => $qcm->id,
                'title' => $qcm->title,
                'questions_count' => $qcm->questions_count,
                'has_questions_data' => !empty($qcm->questions_data)
            ]);

            if (empty($qcm->questions_data)) {
                Log::error('questions_data est vide pour le QCM');
                throw new \Exception('Le QCM ne contient pas de questions.');
            }

            $questionsData = $qcm->questions_data;
            $questions = isset($questionsData['questions']) ? $questionsData['questions'] : [];
            $qcm->questions = $questions;

            return view('elearning.qcm', compact('acces', 'qcm'));
        } catch (\Exception $e) {
            Log::error('Erreur showQcm: ' . $e->getMessage());
            return redirect()->route('elearning.virtual-room')
                ->with('error', 'Erreur lors du chargement du QCM');
        }
    }

    /**
 * Soumettre un QCM - VERSION CORRIGÉE avec gestion des doublons
 */
public function submitQcm(Request $request, $qcmId)
{
    Log::info('=== DÉBUT submitQcm ===', ['qcm_id' => $qcmId]);

    $acces = $this->getValidAccess();
    if (!$acces) {
        Log::error('Accès invalide pour submitQcm');
        return response()->json(['error' => 'Accès invalide'], 401);
    }

    $this->updateSessionActivity($acces);

    try {
        $qcm = ElearningQcm::findOrFail($qcmId);
        $userAnswers = $request->input('answers', []);

        // Calculer le score
        $scoreResult = $this->calculateQcmScore($qcm, $userAnswers);
        $score = $scoreResult['score'];
        $details = $scoreResult['details'];

        Log::info('Score calculé:', ['score' => $score]);

        // ÉTAPE 1: Chercher une progression EXISTANTE pour ce QCM
        $progression = ElearningProgression::where('acces_id', $acces->id)
            ->where('qcm_id', $qcmId)
            ->first();

        if ($progression) {
            // Cas 1: Une progression existe déjà pour ce QCM
            Log::info('Progression existante trouvée pour le QCM', ['progression_id' => $progression->id]);

            // Vérifier le nombre de tentatives
            if ($qcm->attempts_allowed > 0 && $progression->qcm_attempts >= $qcm->attempts_allowed) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nombre maximum de tentatives atteint.'
                ], 403);
            }

            $attemptNumber = $progression->qcm_attempts + 1;

            // Mettre à jour la progression existante
            $progression->qcm_completed = 1;
            $progression->qcm_score = $score;
            $progression->qcm_attempts = $attemptNumber;
            $progression->qcm_completed_at = now();
            $progression->qcm_answers = $userAnswers; // Sauvegarder les réponses
            $progression->qcm_details = $details; // Sauvegarder les détails
            $progression->save();

            Log::info('Progression mise à jour - ID: ' . $progression->id .
                      ', completed: ' . $progression->qcm_completed);

        } else {
            // ÉTAPE 2: Chercher s'il existe une progression pour ce cours (sans QCM)
            $coursProgression = ElearningProgression::where('acces_id', $acces->id)
                ->where('cours_id', $qcm->cours_id)
                ->first();

            if ($coursProgression) {
                // Cas 2: Il existe une progression pour le cours, on la met à jour avec les infos QCM
                Log::info('Progression de cours existante trouvée', ['progression_id' => $coursProgression->id]);

                $coursProgression->qcm_id = $qcm->id;
                $coursProgression->qcm_completed = 1;
                $coursProgression->qcm_score = $score;
                $coursProgression->qcm_attempts = 1;
                $coursProgression->qcm_completed_at = now();
                $coursProgression->qcm_answers = $userAnswers;
                $coursProgression->qcm_details = $details;
                $coursProgression->save();

                $progression = $coursProgression;

                Log::info('Progression de cours mise à jour avec QCM', ['progression_id' => $progression->id]);

            } else {
                // Cas 3: Aucune progression n'existe, on en crée une nouvelle
                Log::info('Aucune progression existante, création d\'une nouvelle');

                $progression = ElearningProgression::create([
                    'acces_id' => $acces->id,
                    'cours_id' => $qcm->cours_id,
                    'qcm_id' => $qcm->id,
                    'qcm_completed' => 1,
                    'qcm_score' => $score,
                    'qcm_attempts' => 1,
                    'qcm_completed_at' => now(),
                    'qcm_answers' => $userAnswers,
                    'qcm_details' => $details,
                ]);

                Log::info('Nouvelle progression créée', ['progression_id' => $progression->id]);
            }
        }

        // VÉRIFICATION : recharger depuis la base pour confirmer
        $verification = ElearningProgression::find($progression->id);
        Log::info('Vérification base - qcm_completed: ' . $verification->qcm_completed);

        // Mettre à jour la moyenne
        $this->updateAverageScore($acces);

        Log::info('=== FIN submitQcm - Succès ===');

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
            'redirect' => route('elearning.virtual-room')
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
     * Helper: Calculer le score d'un QCM
     */
    private function calculateQcmScore(ElearningQcm $qcm, array $userAnswers): array
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

    /**
     * Helper: Mettre à jour la moyenne des scores
     */
    private function updateAverageScore(ElearningAcces $acces)
    {
        $progressions = ElearningProgression::where('acces_id', $acces->id)
            ->whereNotNull('qcm_score')
            ->get();

        if ($progressions->count() > 0) {
            $average = $progressions->avg('qcm_score');
            $acces->update(['average_qcm_score' => round($average, 2)]);
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        Log::info('=== DÉBUT logout e-learning ===');

        if (session('elearning_access_id')) {
            $acces = ElearningAcces::find(session('elearning_access_id'));
            if ($acces) {
                $acces->update([
                    'current_session_token' => null,
                    'current_session_start' => null,
                    'current_session_ip' => null,
                ]);
            }

            ElearningSession::where('session_token', session('elearning_session_token'))
                ->update(['logout_at' => now()]);
        }

        session()->forget([
            'elearning_access_id',
            'elearning_session_token',
            'elearning_virtual_room',
        ]);

        return redirect()->route('elearning.salle')->with('success', 'Déconnexion réussie.');
    }

    /**
     * Helper: Vérifier et récupérer l'accès valide
     */
    private function getValidAccess()
    {
        $accessId = session('elearning_access_id');
        $sessionToken = session('elearning_session_token');

        if (!$accessId || !$sessionToken) {
            return null;
        }

        $acces = ElearningAcces::find($accessId);

        if (!$acces || $acces->current_session_token !== $sessionToken || !$acces->isActive()) {
            return null;
        }

        $acces->update(['last_access_at' => now()]);
        $this->updateSessionActivity($acces);

        return $acces;
    }
}
