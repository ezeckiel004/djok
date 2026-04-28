<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\Formation;
use App\Models\LocationReservation;
use App\Models\Conciergerie;
use App\Models\ConciergerieDemande;
use App\Models\DemandeFormationInternationale;
use App\Models\FormationInternationale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Afficher la liste des paiements
     */
    public function index(Request $request)
    {
        Log::info('PaiementController@index - Affichage des paiements multi-services');

        // Filtrer par type de service
        $query = Paiement::with(['user']);

        if ($request->has('service_type') && $request->service_type) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrer par date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $paiements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Charger les services associés manuellement pour éviter les erreurs
        $this->loadRelatedServices($paiements);

        // Statistiques
        $statistiques = [
            'total' => Paiement::count(),
            'payes' => Paiement::where('status', 'paid')->count(),
            'en_attente' => Paiement::where('status', 'pending')->count(),
            'canceled' => Paiement::where('status', 'canceled')->count(),
            'failed' => Paiement::where('status', 'failed')->count(),
            'total_amount' => Paiement::where('status', 'paid')->sum('amount'),
            'par_service' => [
                'formation' => Paiement::where('service_type', 'formation')->count(),
                'reservation' => Paiement::where('service_type', 'reservation')->count(),
                'location' => Paiement::where('service_type', 'location')->count(),
                'conciergerie' => Paiement::where('service_type', 'conciergerie')->count(),
                'formation_internationale' => Paiement::where('service_type', 'formation_internationale')->count(),
            ]
        ];

        // Calculer les montants par service
        $montantsParService = [
            'formation' => Paiement::where('service_type', 'formation')->where('status', 'paid')->sum('amount'),
            'reservation' => Paiement::where('service_type', 'reservation')->where('status', 'paid')->sum('amount'),
            'location' => Paiement::where('service_type', 'location')->where('status', 'paid')->sum('amount'),
            'conciergerie' => Paiement::where('service_type', 'conciergerie')->where('status', 'paid')->sum('amount'),
            'formation_internationale' => Paiement::where('service_type', 'formation_internationale')->where('status', 'paid')->sum('amount'),
        ];

        return view('admin.paiements.index', compact('paiements', 'statistiques', 'montantsParService'));
    }

    /**
     * Charger manuellement les services associés
     */
    private function loadRelatedServices($paiements)
    {
        $formationIds = [];
        $reservationIds = [];
        $locationIds = [];
        $conciergerieIds = [];
        $formationInternationaleIds = [];

        foreach ($paiements as $paiement) {
            switch ($paiement->service_type) {
                case 'formation':
                    if ($paiement->service_id) {
                        $formationIds[] = $paiement->service_id;
                    }
                    break;
                case 'reservation':
                    if ($paiement->reservation_id) {
                        $reservationIds[] = $paiement->reservation_id;
                    }
                    break;
                case 'location':
                    if ($paiement->location_id) {
                        $locationIds[] = $paiement->location_id;
                    }
                    break;
                case 'conciergerie':
                    if ($paiement->conciergerie_id) {
                        $conciergerieIds[] = $paiement->conciergerie_id;
                    }
                    break;
                case 'formation_internationale':
                    if ($paiement->formation_internationale_id) {
                        $formationInternationaleIds[] = $paiement->formation_internationale_id;
                    }
                    break;
            }
        }

        // Charger les services en une seule requête
        $formations = !empty($formationIds) ?
            Formation::whereIn('id', array_unique($formationIds))->get()->keyBy('id') : collect();
        $reservations = !empty($reservationIds) ?
            Reservation::whereIn('id', array_unique($reservationIds))->get()->keyBy('id') : collect();
        $locations = !empty($locationIds) ?
            LocationReservation::whereIn('id', array_unique($locationIds))->get()->keyBy('id') : collect();
        $conciergeries = !empty($conciergerieIds) ?
            ConciergerieDemande::whereIn('id', array_unique($conciergerieIds))->get()->keyBy('id') : collect();
        $formationsInternationales = !empty($formationInternationaleIds) ?
            DemandeFormationInternationale::whereIn('id', array_unique($formationInternationaleIds))->get()->keyBy('id') : collect();

        // Associer les services aux paiements
        foreach ($paiements as $paiement) {
            switch ($paiement->service_type) {
                case 'formation':
                    if ($paiement->service_id && $formations->has($paiement->service_id)) {
                        $paiement->setRelation('formation', $formations[$paiement->service_id]);
                    }
                    break;
                case 'reservation':
                    if ($paiement->reservation_id && $reservations->has($paiement->reservation_id)) {
                        $paiement->setRelation('reservation', $reservations[$paiement->reservation_id]);
                    }
                    break;
                case 'location':
                    if ($paiement->location_id && $locations->has($paiement->location_id)) {
                        $paiement->setRelation('location', $locations[$paiement->location_id]);
                    }
                    break;
                case 'conciergerie':
                    if ($paiement->conciergerie_id && $conciergeries->has($paiement->conciergerie_id)) {
                        $paiement->setRelation('conciergerie', $conciergeries[$paiement->conciergerie_id]);
                    }
                    break;
                case 'formation_internationale':
                    if ($paiement->formation_internationale_id && $formationsInternationales->has($paiement->formation_internationale_id)) {
                        $paiement->setRelation('formationInternationale', $formationsInternationales[$paiement->formation_internationale_id]);
                    }
                    break;
            }
        }
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Paiement $paiement)
    {
        Log::info('PaiementController@show - Détails du paiement ID: ' . $paiement->id);

        // Charger l'utilisateur
        $paiement->load(['user']);

        // Charger le service associé selon le type
        switch ($paiement->service_type) {
            case 'formation':
                // CORRECTION : Charger manuellement pour éviter l'erreur de relation
                if ($paiement->service_id) {
                    $paiement->formation = Formation::find($paiement->service_id);
                }
                $userFormations = $paiement->getAssociatedUserFormations();
                $participant = $paiement->getAssociatedParticipant();
                break;
            case 'reservation':
                $paiement->load(['reservation']);
                $userFormations = collect();
                $participant = null;
                break;
            case 'location':
                $paiement->load(['location']);
                $userFormations = collect();
                $participant = null;
                break;
            case 'conciergerie':
                $paiement->load(['conciergerie']);
                $userFormations = collect();
                $participant = null;
                break;
            case 'formation_internationale':
                $paiement->load(['formationInternationale']);
                $userFormations = collect();
                $participant = null;
                break;
            default:
                $userFormations = collect();
                $participant = null;
        }

        return view('admin.paiements.show', compact('paiement', 'userFormations', 'participant'));
    }

    /**
     * Afficher les statistiques des paiements
     */
    public function statistiques(Request $request)
    {
        $periode = $request->get('periode', 'mois');

        // Statistiques générales
        $statistiques = [
            'total_paiements' => Paiement::count(),
            'paiements_payes' => Paiement::where('status', 'paid')->count(),
            'taux_conversion' => Paiement::count() > 0 ?
                round((Paiement::where('status', 'paid')->count() / Paiement::count()) * 100, 2) : 0,
            'chiffre_affaires' => Paiement::where('status', 'paid')->sum('amount'),
            'panier_moyen' => Paiement::where('status', 'paid')->avg('amount') ?? 0,
        ];

        // Évolution temporelle
        $evolution = $this->getEvolutionData($periode);

        // Répartition par service
        $repartitionService = DB::table('paiements')
            ->select('service_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->where('status', 'paid')
            ->groupBy('service_type')
            ->get();

        // Paiements récents
        $paiementsRecents = Paiement::with(['user'])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Charger les services associés
        $this->loadRelatedServices($paiementsRecents);

        // Meilleurs clients
        $meilleursClients = DB::table('paiements')
            ->select('user_id', DB::raw('COUNT(*) as paiements_count'), DB::raw('SUM(amount) as total_depense'))
            ->where('status', 'paid')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('total_depense', 'desc')
            ->limit(10)
            ->get();

        // Charger les utilisateurs
        foreach ($meilleursClients as $client) {
            $client->user = \App\Models\User::find($client->user_id);
        }

        return view('admin.paiements.statistiques', compact(
            'statistiques',
            'evolution',
            'repartitionService',
            'paiementsRecents',
            'meilleursClients',
            'periode'
        ));
    }

    /**
     * Obtenir les données d'évolution selon la période
     */
    private function getEvolutionData($periode)
    {
        $data = [];

        if ($periode === 'jour') {
            // Derniers 30 jours
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $paiements = Paiement::whereDate('created_at', $date)
                    ->where('status', 'paid')
                    ->get();

                $data[] = [
                    'date' => now()->subDays($i)->format('d/m'),
                    'nombre' => $paiements->count(),
                    'montant' => $paiements->sum('amount'),
                ];
            }
        } elseif ($periode === 'semaine') {
            // Dernières 12 semaines
            for ($i = 11; $i >= 0; $i--) {
                $startOfWeek = now()->subWeeks($i)->startOfWeek();
                $endOfWeek = now()->subWeeks($i)->endOfWeek();

                $paiements = Paiement::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('status', 'paid')
                    ->get();

                $data[] = [
                    'date' => 'S' . $startOfWeek->weekOfYear,
                    'nombre' => $paiements->count(),
                    'montant' => $paiements->sum('amount'),
                ];
            }
        } else {
            // Derniers 12 mois (par défaut)
            for ($i = 11; $i >= 0; $i--) {
                $startOfMonth = now()->subMonths($i)->startOfMonth();
                $endOfMonth = now()->subMonths($i)->endOfMonth();

                $paiements = Paiement::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('status', 'paid')
                    ->get();

                $data[] = [
                    'date' => $startOfMonth->format('M Y'),
                    'nombre' => $paiements->count(),
                    'montant' => $paiements->sum('amount'),
                ];
            }
        }

        return $data;
    }

    /**
     * Exporter les paiements en CSV
     */
    public function export(Request $request)
    {
        $query = Paiement::with(['user']);

        if ($request->has('service_type') && $request->service_type) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $paiements = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'paiements_' . date('Y-m-d_H-i') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function () use ($paiements) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, [
                'Référence',
                'Date',
                'Type de service',
                'Service',
                'Client',
                'Email',
                'Montant (€)',
                'Statut',
                'ID Session Stripe',
                'Date paiement',
                'Créé le'
            ]);

            // Données
            foreach ($paiements as $paiement) {
                fputcsv($file, [
                    $paiement->reference,
                    $paiement->created_at->format('d/m/Y'),
                    $paiement->formatted_service_type,
                    $paiement->service_name,
                    $paiement->customer_name,
                    $paiement->customer_email,
                    number_format($paiement->amount, 2, ',', ''),
                    $paiement->formatted_status,
                    $paiement->stripe_session_id ?? '',
                    $paiement->paid_at ? $paiement->paid_at->format('d/m/Y H:i') : '',
                    $paiement->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Rembourser un paiement
     */
    public function refund(Request $request, Paiement $paiement)
    {
        Log::info('PaiementController@refund - Tentative de remboursement ID: ' . $paiement->id);

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            // Vérifier que le paiement peut être remboursé
            if (!$paiement->isPaid()) {
                return redirect()->back()
                    ->with('error', 'Seuls les paiements validés peuvent être remboursés.');
            }

            if (!$paiement->stripe_payment_intent_id) {
                return redirect()->back()
                    ->with('error', 'ID de paiement Stripe manquant. Impossible de procéder au remboursement.');
            }

            // Logique de remboursement Stripe
            $stripeService = app(\App\Services\StripeService::class);

            // Créer le remboursement
            $refund = $stripeService->createRefund($paiement->stripe_payment_intent_id, [
                'reason' => $request->reason ? 'requested_by_customer' : 'duplicate',
                'metadata' => [
                    'paiement_reference' => $paiement->reference,
                    'admin_id' => auth()->id(),
                    'reason' => $request->reason,
                ]
            ]);

            // Mettre à jour le paiement
            $paiement->markAsRefunded($request->reason, json_decode(json_encode($refund), true));

            Log::info('PaiementController@refund - Remboursement réussi ID: ' . $paiement->id);

            return redirect()->back()
                ->with('success', 'Le remboursement a été effectué avec succès.');
        } catch (\Exception $e) {
            Log::error('PaiementController@refund - Erreur: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    /**
 * Supprimer un paiement (sans vérification de statut)
 */
public function destroy(Paiement $paiement)
{
    Log::info('PaiementController@destroy - Tentative de suppression du paiement ID: ' . $paiement->id . ', Réf: ' . $paiement->reference);

    try {
        // SUPPRESSION DE LA VÉRIFICATION - On supprime tous les paiements quel que soit le statut

        // Stocker les infos pour le log avant suppression
        $paiementInfo = [
            'id' => $paiement->id,
            'reference' => $paiement->reference,
            'amount' => $paiement->amount,
            'service_type' => $paiement->service_type,
            'status' => $paiement->status,
            'user_id' => $paiement->user_id,
            'deleted_by' => auth()->user()->id,
            'deleted_by_name' => auth()->user()->name,
            'deleted_at' => now()->toDateTimeString()
        ];

        // Log AVERTISSEMENT pour les suppressions de paiements payés ou remboursés
        if (in_array($paiement->status, ['paid', 'refunded'])) {
            Log::warning('⚠️ SUPPRESSION SENSIBLE - Un admin a supprimé un paiement ' . $paiement->status, $paiementInfo);
        } else {
            Log::info('Suppression de paiement', $paiementInfo);
        }

        // Supprimer le paiement (soft delete ou définitif selon votre configuration)
        $paiement->delete();

        Log::info('PaiementController@destroy - Paiement supprimé avec succès', $paiementInfo);

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Le paiement #' . $paiementInfo['reference'] . ' (Statut: ' . $paiementInfo['status'] . ') a été supprimé avec succès.');

    } catch (\Exception $e) {
        Log::error('PaiementController@destroy - Erreur lors de la suppression: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Erreur lors de la suppression du paiement: ' . $e->getMessage());
    }
}

   /**
 * Supprimer plusieurs paiements (action groupée) - SANS VÉRIFICATION
 */
public function bulkDestroy(Request $request)
{
    $request->validate([
        'paiement_ids' => 'required|array',
        'paiement_ids.*' => 'exists:paiements,id'
    ]);

    $paiementIds = $request->paiement_ids;
    $deletedCount = 0;
    $errors = [];
    $sensitiveDeletions = [];

    foreach ($paiementIds as $id) {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            continue;
        }

        try {
            // Log pour les suppressions sensibles
            if (in_array($paiement->status, ['paid', 'refunded'])) {
                $sensitiveDeletions[] = $paiement->reference . ' (' . $paiement->status . ')';
                Log::warning('⚠️ SUPPRESSION GROUPÉE SENSIBLE - Admin a supprimé un paiement ' . $paiement->status, [
                    'id' => $paiement->id,
                    'reference' => $paiement->reference,
                    'status' => $paiement->status,
                    'amount' => $paiement->amount,
                    'deleted_by' => auth()->user()->id,
                    'deleted_by_name' => auth()->user()->name
                ]);
            } else {
                Log::info('Suppression groupée de paiement', [
                    'id' => $paiement->id,
                    'reference' => $paiement->reference,
                    'deleted_by' => auth()->user()->id,
                    'deleted_by_name' => auth()->user()->name
                ]);
            }

            $paiement->delete();
            $deletedCount++;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression groupée du paiement ID ' . $id . ': ' . $e->getMessage());
            $errors[] = 'Paiement #' . $paiement->reference . ': ' . $e->getMessage();
        }
    }

    $message = $deletedCount . ' paiement(s) supprimé(s) avec succès.';

    if (!empty($sensitiveDeletions)) {
        $message .= ' ⚠️ Attention: ' . count($sensitiveDeletions) . ' paiement(s) sensible(s) supprimés : ' . implode(', ', $sensitiveDeletions);
    }

    if (!empty($errors)) {
        $message .= ' Erreurs: ' . implode(', ', $errors);
        return redirect()->route('admin.paiements.index')
            ->with('warning', $message);
    }

    return redirect()->route('admin.paiements.index')
        ->with('success', $message);
}
}
