<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\FormationSession;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FormationSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:access-admin-dashboard');
    }

    /**
     * Liste des sessions
     */
    public function index(Request $request)
    {
        $query = FormationSession::with('formation');

        // Filtres
        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->formation_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'upcoming') {
                $query->where('start_date', '>=', now());
            } elseif ($request->status === 'past') {
                $query->where('start_date', '<', now());
            }
        }

        $sessions = $query->orderBy('start_date', 'desc')->paginate(20);
        $formations = Formation::active()->orderBy('title')->get();

        return view('admin.sessions.index', compact('sessions', 'formations'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $formations = Formation::active()->orderBy('title')->get();
        return view('admin.sessions.create', compact('formations'));
    }

    /**
     * Créer une session
     */
    public function store(Request $request)
    {
        Log::info('=== DÉBUT FormationSessionController@store ===');

        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:presentiel,e_learning,mixte',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:500',
            'max_places' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $formation = Formation::findOrFail($validated['formation_id']);

            // Définir le prix par défaut si non spécifié
            if (empty($validated['price'])) {
                $validated['price'] = $formation->price;
            }

            // Définir les places disponibles
            $validated['available_places'] = $validated['max_places'];

            // Définir les valeurs par défaut
            $validated['is_active'] = $request->has('is_active');

            $session = FormationSession::create($validated);

            DB::commit();

            Log::info('Session créée avec succès', ['session_id' => $session->id]);

            return redirect()->route('admin.sessions.index')
                ->with('success', 'Session créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création session', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Afficher une session
     */
    public function show(FormationSession $session)
    {
        $session->load(['formation', 'participants' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $participantsCount = $session->participants()->count();
        $waitingListCount = $session->inscriptions()->where('status', 'waiting')->count();

        return view('admin.sessions.show', compact('session', 'participantsCount', 'waitingListCount'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(FormationSession $session)
    {
        $formations = Formation::active()->orderBy('title')->get();
        return view('admin.sessions.edit', compact('session', 'formations'));
    }

    /**
     * Mettre à jour une session
     */
    public function update(Request $request, FormationSession $session)
    {
        Log::info('=== DÉBUT FormationSessionController@update ===', ['session_id' => $session->id]);

        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:presentiel,e_learning,mixte',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:500',
            'max_places' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $oldMaxPlaces = $session->max_places;
            $newMaxPlaces = $validated['max_places'];

            // Ajuster les places disponibles si le nombre max change
            if ($newMaxPlaces != $oldMaxPlaces) {
                $participantsCount = $session->participants()->count();
                $validated['available_places'] = max(0, $newMaxPlaces - $participantsCount);
            }

            $validated['is_active'] = $request->has('is_active');

            $session->update($validated);

            DB::commit();

            Log::info('Session mise à jour avec succès', ['session_id' => $session->id]);

            return redirect()->route('admin.sessions.index')
                ->with('success', 'Session mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour session', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une session
     */
    public function destroy(FormationSession $session)
    {
        Log::info('=== DÉBUT FormationSessionController@destroy ===', ['session_id' => $session->id]);

        try {
            DB::beginTransaction();

            $participantsCount = $session->participants()->count();

            if ($participantsCount > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette session car ' . $participantsCount . ' participant(s) y sont inscrits.');
            }

            $session->delete();

            DB::commit();

            Log::info('Session supprimée avec succès', ['session_id' => $session->id]);

            return redirect()->route('admin.sessions.index')
                ->with('success', 'Session supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression session', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Activer/Désactiver une session
     */
    public function toggleStatus(FormationSession $session)
    {
        try {
            $session->update(['is_active' => !$session->is_active]);

            $status = $session->is_active ? 'activée' : 'désactivée';

            return response()->json([
                'success' => true,
                'is_active' => $session->is_active,
                'message' => 'Session ' . $status . ' avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur toggle status session', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

   /**
 * Ajuster les places disponibles
 */
public function adjustPlaces(Request $request, FormationSession $session)
{
    $validated = $request->validate([
        'adjustment' => 'required|integer|min:-100|max:100',
        'reason' => 'nullable|string|max:255',
    ]);

    try {
        DB::beginTransaction();

        $newPlaces = $session->available_places + $validated['adjustment'];

        // Vérification des limites
        if ($newPlaces < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de réduire les places en dessous de 0.'
            ], 400);
        }

        // Si on dépasse la capacité maximale, on met une alerte mais on accepte
        if ($newPlaces > $session->max_places) {
            $session->max_places = $newPlaces; // Augmenter automatiquement la capacité max
        }

        $session->update([
            'available_places' => $newPlaces,
            'max_places' => $newPlaces > $session->max_places ? $newPlaces : $session->max_places,
        ]);

        // Log de l'ajustement
        Log::info('Ajustement des places', [
            'session_id' => $session->id,
            'session_name' => $session->name,
            'adjustment' => $validated['adjustment'],
            'old_places' => $session->available_places - $validated['adjustment'],
            'new_places' => $newPlaces,
            'reason' => $validated['reason'],
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'available_places' => $newPlaces,
            'max_places' => $session->max_places,
            'places_remaining' => $newPlaces,
            'message' => 'Places ajustées avec succès'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur ajustement places', [
            'error' => $e->getMessage(),
            'session_id' => $session->id,
            'adjustment' => $validated['adjustment'] ?? null
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'ajustement: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Exporter les participants d'une session
     */
    public function exportParticipants(FormationSession $session)
    {
        $participants = $session->participants()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="session_' . $session->id . '_participants.csv"',
        ];

        $callback = function() use ($participants, $session) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID', 'Nom', 'Prénom', 'Email', 'Téléphone',
                'Date d\'inscription', 'Statut', 'Session'
            ]);

            foreach ($participants as $participant) {
                fputcsv($file, [
                    $participant->id,
                    $participant->nom,
                    $participant->prenom,
                    $participant->email,
                    $participant->telephone,
                    $participant->created_at->format('d/m/Y H:i'),
                    $participant->statut,
                    $session->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
