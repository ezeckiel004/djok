<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Paiement;
use App\Models\UserFormation;
use App\Models\Participant;
use App\Services\PaymentService;
use App\Mail\ElearningPurchaseConfirmation;
use App\Mail\ElearningPurchaseAdminNotification;
use App\Mail\FormationInscriptionConfirmation;
use App\Mail\FormationInscriptionAdminNotification;
use App\Models\FormationSession;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FormationController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Afficher la page formation
     */
    public function index()
    {
        $formations = Formation::with(['media' => function ($query) {
            $query->orderBy('order');
        }])
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('formation', compact('formations'));
    }

    /**
     * Afficher une formation spécifique
     */
    public function show($slug)
    {
        $formation = Formation::with(['media' => function ($query) {
            $query->orderBy('order');
        }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Vérifier si l'utilisateur connecté a accès à cette formation
        $hasAccess = false;
        $userFormation = null;

        if (auth()->check()) {
            $userFormation = UserFormation::where('user_id', auth()->id())
                ->where('formation_id', $formation->id)
                ->where('status', 'active')
                ->first();

            $hasAccess = !is_null($userFormation);
        }

        return view('formations.show', compact('formation', 'hasAccess', 'userFormation'));
    }

    /**
     * Page d'inscription pour présentiel - ACCESSIBLE SANS AUTHENTIFICATION
     * SANS RESTRICTION SUR LE TYPE DE FORMATION
     */
    public function inscrirePresentiel($id)
    {
        // Ne pas filtrer par type_formation, juste vérifier que la formation existe et est active
        $formation = Formation::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('formations.inscrire-presentiel', compact('formation'));
    }

    /**
     * Traiter l'inscription présentiel - ACCESSIBLE SANS AUTHENTIFICATION
     */
    public function storeInscriptionPresentiel(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'code_postal' => 'required|string',
            'date_naissance' => 'required|date',
            'permis_date' => 'required|date',
            'message' => 'nullable|string',
            'financement' => 'required|in:perso,cpf,pole_emploi',
            'terms' => 'required|accepted',
            'session_id' => 'nullable|exists:formation_sessions,id',
        ]);

        try {
            $user = auth()->user();

            // Si l'utilisateur n'est pas connecté, vérifier si un compte existe déjà
            if (!$user) {
                $existingUser = \App\Models\User::where('email', $validated['email'])->first();

                if ($existingUser) {
                    $user = $existingUser;
                    Log::info('Utilisateur existant trouvé', ['user_id' => $user->id, 'email' => $validated['email']]);
                } else {
                    // Récupérer le rôle client (par défaut)
                    $clientRole = Role::where('name', 'client')->first();

                    if (!$clientRole) {
                        Log::error('Rôle client non trouvé dans la base de données');
                        throw new \Exception('Configuration des rôles incorrecte. Contactez l\'administrateur.');
                    }

                    // Créer un compte utilisateur automatiquement
                    $tempPassword = Str::random(12);
                    $user = \App\Models\User::create([
                        'name' => $validated['prenom'] . ' ' . $validated['nom'],
                        'email' => $validated['email'],
                        'password' => bcrypt($tempPassword),
                        'phone' => $validated['telephone'],
                        'email_verified_at' => now(),
                        'role_id' => $clientRole->id,
                    ]);

                    Log::info('Nouvel utilisateur créé automatiquement', [
                        'user_id' => $user->id,
                        'email' => $validated['email'],
                        'role_id' => $clientRole->id,
                        'temp_password' => $tempPassword
                    ]);
                }
            }

            // Récupérer la session si spécifiée - CORRIGÉ AVEC startOfDay()
            $session = null;
            if ($request->filled('session_id')) {
                $session = FormationSession::where('id', $request->session_id)
                    ->where('formation_id', $formation->id)
                    ->where('is_active', true)
                    ->where('start_date', '>=', Carbon::now()->startOfDay())
                    ->where('available_places', '>', 0)
                    ->first();

                if (!$session) {
                    return redirect()->back()
                        ->with('error', 'Cette session n\'est plus disponible.')
                        ->withInput();
                }
            }

            DB::beginTransaction();

            // Créer le participant AVEC session_id
            $participant = Participant::create([
                'formation_id' => $formation->id,
                'session_id' => $session ? $session->id : null,
                'user_id' => $user ? $user->id : null,
                'paiement_id' => null,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'code_postal' => $validated['code_postal'],
                'date_naissance' => $validated['date_naissance'],
                'permis_date' => $validated['permis_date'],
                'type_formation' => 'presentiel',
                'statut' => 'en_attente',
                'progression' => 0,
                'notes' => $validated['message'] ?? null,
                'donnees_supplementaires' => [
                    'financement' => $validated['financement'],
                    'inscription_date' => now()->format('Y-m-d H:i:s'),
                    'formation_title' => $formation->title,
                    'formation_price' => $formation->price,
                    'formation_duree' => $formation->duree,
                    'formation_format' => $formation->format_affichage,
                    'frais_examen' => $formation->frais_examen,
                    'session_name' => $session ? $session->name : null,
                    'session_dates' => $session ? $session->formatted_dates : null,
                    'user_created_automatically' => isset($user->wasRecentlyCreated) ? $user->wasRecentlyCreated : false,
                ],
            ]);

            Log::info('Nouvelle inscription présentielle', [
                'participant_id' => $participant->id,
                'formation_id' => $formation->id,
                'session_id' => $session ? $session->id : null,
                'email' => $validated['email'],
                'user_connected' => auth()->check(),
                'user_created' => isset($user->wasRecentlyCreated) ? $user->wasRecentlyCreated : false,
            ]);

            // Réduire les places disponibles de la session
            if ($session) {
                $session->decrement('available_places');
                Log::info('Places de la session mises à jour', [
                    'session_id' => $session->id,
                    'places_restantes' => $session->available_places
                ]);
            }

            DB::commit();

            // Envoi des emails
            try {
                Mail::to($validated['email'])->send(new FormationInscriptionConfirmation($participant, $formation, $session));
                Log::info('Email de confirmation envoyé à: ' . $validated['email']);

                $adminEmail = config('mail.admin_email', 'formation@djokprestige.com');
                Mail::to($adminEmail)->send(new FormationInscriptionAdminNotification($participant, $formation, $session));
                Log::info('Email de notification envoyé à l\'admin: ' . $adminEmail);
            } catch (\Exception $emailException) {
                Log::error('Erreur lors de l\'envoi des emails', [
                    'error' => $emailException->getMessage(),
                    'participant_id' => $participant->id,
                ]);
            }

            // Message de succès avec information sur le compte créé
            $successMessage = 'Votre inscription a été envoyée avec succès. Un email de confirmation vous a été envoyé.';

            if (isset($user->wasRecentlyCreated) && $user->wasRecentlyCreated) {
                $successMessage .= ' Un compte client a été automatiquement créé avec votre email. Vous pourrez vous connecter pour suivre votre dossier.';
            }

            return redirect()->route('formation')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'inscription présentielle', [
                'error' => $e->getMessage(),
                'formation_id' => $id,
                'email' => $validated['email'] ?? 'N/A',
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de votre inscription. Veuillez réessayer ou nous contacter.')
                ->withInput();
        }
    }

    /**
     * Page d'achat pour e-learning - NÉCESSITE AUTHENTIFICATION
     */
    public function acheterElearning($id)
    {
        $formation = Formation::where('id', $id)
            ->where('type_formation', 'e_learning')
            ->where('is_active', true)
            ->firstOrFail();

        // Vérifier si l'utilisateur a déjà acheté cette formation
        if (auth()->check()) {
            $hasAccess = UserFormation::where('user_id', auth()->id())
                ->where('formation_id', $formation->id)
                ->where('status', 'active')
                ->exists();

            if ($hasAccess) {
                return redirect()->route('dashboard')
                    ->with('info', 'Vous avez déjà accès à cette formation.');
            }
        }

        return view('formations.acheter-elearning', compact('formation'));
    }

    /**
     * Créer une session de paiement - NÉCESSITE AUTHENTIFICATION
     */
    public function createPaymentSession(Request $request, $id)
    {
        Log::info('=== DÉBUT createPaymentSession ===');
        Log::info('ID formation: ' . $id);
        Log::info('Utilisateur connecté: ' . (auth()->check() ? auth()->user()->email : 'non connecté'));
        Log::info('Données reçues:', $request->all());

        try {
            $formation = Formation::where('id', $id)
                ->where('type_formation', 'e_learning')
                ->where('is_active', true)
                ->firstOrFail();

            Log::info('Formation trouvée:', [
                'id' => $formation->id,
                'title' => $formation->title,
                'price' => $formation->price,
            ]);

            // Récupérer l'email et le nom de l'utilisateur
            $userEmail = null;
            $userName = null;

            if (auth()->check()) {
                $userEmail = auth()->user()->email;
                $userName = auth()->user()->name;
                Log::info('Utilisateur connecté - email: ' . $userEmail . ', nom: ' . $userName);
            } else {
                $userEmail = $request->email;
                $userName = $request->nom;
                Log::info('Utilisateur non connecté - email fourni: ' . $userEmail . ', nom: ' . $userName);
            }

            if (!$userEmail) {
                Log::error('Email non disponible pour la création de la session');
                return response()->json([
                    'error' => 'Email requis pour le paiement'
                ], 400);
            }

            // Créer un paiement en attente
            $paiement = Paiement::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'service_id' => $formation->id,
                'service_type' => 'formation',
                'reference' => 'PAY_' . Str::upper(Str::random(10)),
                'amount' => $formation->price,
                'currency' => 'eur',
                'status' => 'pending',
                'customer_info' => [
                    'email' => $userEmail,
                    'name' => $userName,
                ],
                'service_details' => [
                    'service_name' => 'Formation e-learning: ' . $formation->title,
                    'description' => 'Accès 12 mois à la formation "' . $formation->title . '"',
                    'formation_data' => $formation->toArray(),
                ],
            ]);

            Log::info('Paiement créé:', [
                'reference' => $paiement->reference,
                'amount' => $paiement->amount,
                'status' => $paiement->status,
            ]);

            // Créer un participant en attente
            $participantData = [
                'formation_id' => $formation->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'paiement_id' => $paiement->id,
                'nom' => $userName,
                'prenom' => '',
                'email' => $userEmail,
                'type_formation' => 'en_ligne',
                'statut' => 'en_attente',
                'donnees_supplementaires' => [
                    'inscription_date' => now()->format('Y-m-d H:i:s'),
                    'formation_title' => $formation->title,
                    'paiement_reference' => $paiement->reference,
                    'type_paiement' => 'e_learning',
                ],
            ];

            Participant::create($participantData);
            Log::info('Participant créé pour l\'e-learning');

            // Préparer les données pour PaymentService
            $serviceData = [
                'amount' => $formation->price,
                'service_name' => 'Formation e-learning: ' . $formation->title,
                'description' => 'Accès 12 mois à la formation "' . $formation->title . '"',
                'formation_data' => $formation->toArray(),
            ];

            $customerData = [
                'name' => $userName,
                'email' => $userEmail,
                'phone' => '',
            ];

            $metadata = [
                'formation_id' => $formation->id,
                'paiement_reference' => $paiement->reference,
                'service_type' => 'formation',
            ];

            Log::info('Création de la session via PaymentService...');

            // Utiliser le PaymentService unifié
            $paymentSession = $this->paymentService->createPaymentSession(
                'formation',
                $serviceData,
                $customerData,
                $metadata
            );

            // Mettre à jour le paiement avec l'ID de session
            $paiement->update([
                'stripe_session_id' => $paymentSession['session_id'],
            ]);

            Log::info('=== FIN createPaymentSession - Succès ===');

            return response()->json([
                'sessionId' => $paymentSession['session_id'],
                'url' => $paymentSession['url'],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans createPaymentSession:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la création de la session de paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redirection pour succès (utilise PaymentController)
     */
    public function paymentSuccess(Request $request)
    {
        return redirect()->route('payment.success', ['session_id' => $request->get('session_id')]);
    }

    /**
     * Redirection pour annulation (utilise PaymentController)
     */
    public function paymentCancel()
    {
        Log::info('Paiement annulé par l\'utilisateur');
        return redirect()->route('payment.cancel');
    }

    /**
     * Corriger le slug d'une formation spécifique
     */
    public function fixSlug($id)
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $formation = Formation::findOrFail($id);
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

            Log::info('Slug corrigé', [
                'formation_id' => $formation->id,
                'old_slug' => $oldSlug,
                'new_slug' => $newSlug,
                'title' => $formation->title,
                'type_formation' => $formation->type_formation,
            ]);

            return redirect()->back()
                ->with('success', "Slug corrigé : {$oldSlug} → {$newSlug}");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la correction du slug', [
                'formation_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', "Erreur lors de la correction du slug : {$e->getMessage()}");
        }
    }

    /**
     * Corriger tous les slugs des formations
     */
    public function fixAllSlugs()
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $formations = Formation::all();
            $updatedCount = 0;
            $errors = [];

            foreach ($formations as $formation) {
                try {
                    $oldSlug = $formation->slug;
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
                        $formation->saveQuietly();

                        Log::info('Slug corrigé', [
                            'formation_id' => $formation->id,
                            'old_slug' => $oldSlug,
                            'new_slug' => $newSlug,
                        ]);

                        $updatedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Formation {$formation->id} ({$formation->title}) : {$e->getMessage()}";
                    Log::error('Erreur lors de la correction du slug', [
                        'formation_id' => $formation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $message = "{$updatedCount} slugs ont été corrigés avec succès.";

            if (!empty($errors)) {
                $message .= " Erreurs : " . implode(' | ', $errors);
                return redirect()->back()
                    ->with('warning', $message);
            }

            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la correction de tous les slugs', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', "Erreur lors de la correction des slugs : {$e->getMessage()}");
        }
    }

    /**
     * Vérifier les doublons de slugs
     */
    public function checkSlugDuplicates()
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $duplicates = Formation::select('slug')
                ->groupBy('slug')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            if ($duplicates->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'Aucun doublon de slug détecté.');
            }

            $duplicateList = [];
            foreach ($duplicates as $dup) {
                $formations = Formation::where('slug', $dup->slug)->get();
                $duplicateList[] = [
                    'slug' => $dup->slug,
                    'formations' => $formations->map(function ($f) {
                        return "ID: {$f->id}, Titre: {$f->title}, Type: {$f->type_formation}";
                    })->implode(' | ')
                ];
            }

            return redirect()->back()
                ->with('warning', 'Doublons détectés : ' .
                    collect($duplicateList)->map(function ($item) {
                        return "Slug '{$item['slug']}' : {$item['formations']}";
                    })->implode('; '));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification des doublons', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', "Erreur lors de la vérification des doublons : {$e->getMessage()}");
        }
    }
}
