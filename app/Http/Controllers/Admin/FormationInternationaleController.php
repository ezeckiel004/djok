<?php
// app/Http/Controllers/Admin/FormationInternationaleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeFormationInternationale;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeStatutChangedMail;

class FormationInternationaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DemandeFormationInternationale::query()
            ->orderBy('created_at', 'desc');

        // Filtre par statut
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        // Filtre par destination
        if ($request->has('destination') && $request->destination != '') {
            $query->where('destination_souhaitee', $request->destination);
        }

        $demandes = $query->paginate(20);

        return view('admin.formation-internationale.index', compact('demandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.formation-internationale.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_entreprise' => 'nullable|string|max:255',
            'nom_responsable' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'destination_souhaitee' => 'nullable|string|in:dubai,usa,europe,afrique,autre',
            'nombre_participants' => 'nullable|integer|min:1',
            'type_evenement' => 'nullable|array',
            'type_evenement.*' => 'string|in:formation,seminaire,voyage_business,team_building',
            'message' => 'required|string|min:10',
            'statut' => 'required|in:nouveau,en_cours,traite,annule',
            'notes_admin' => 'nullable|string'
        ]);

        $demande = DemandeFormationInternationale::create([
            'nom_complet' => $request->nom_responsable,
            'nom_entreprise' => $request->nom_entreprise,
            'nom_responsable' => $request->nom_responsable,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'destination_souhaitee' => $request->destination_souhaitee,
            'nombre_participants' => $request->nombre_participants,
            'type_evenement' => $request->type_evenement ?? [],
            'message' => strip_tags($request->message),
            'objectifs_projet' => strip_tags($request->message),
            'statut' => $request->statut,
            'notes_admin' => $request->notes_admin
        ]);

        return redirect()
            ->route('admin.demandes-formation-internationale.index')
            ->with('success', 'Demande créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeFormationInternationale $demande)
    {
        return view('admin.formation-internationale.show', compact('demande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeFormationInternationale $demande)
    {
        return view('admin.formation-internationale.edit', compact('demande'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DemandeFormationInternationale $demande)
    {
        $request->validate([
            'nom_entreprise' => 'nullable|string|max:255',
            'nom_responsable' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'destination_souhaitee' => 'nullable|string|in:dubai,usa,europe,afrique,autre',
            'nombre_participants' => 'nullable|integer|min:1',
            'type_evenement' => 'nullable|array',
            'type_evenement.*' => 'string|in:formation,seminaire,voyage_business,team_building',
            'message' => 'required|string|min:10',
            'statut' => 'required|in:nouveau,en_cours,traite,annule',
            'notes_admin' => 'nullable|string'
        ]);

        $ancienStatut = $demande->statut;

        $demande->update([
            'nom_complet' => $request->nom_responsable,
            'nom_entreprise' => $request->nom_entreprise,
            'nom_responsable' => $request->nom_responsable,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'destination_souhaitee' => $request->destination_souhaitee,
            'nombre_participants' => $request->nombre_participants,
            'type_evenement' => $request->type_evenement ?? [],
            'message' => strip_tags($request->message),
            'objectifs_projet' => strip_tags($request->message),
            'statut' => $request->statut,
            'notes_admin' => $request->notes_admin
        ]);

        if ($ancienStatut !== $request->statut) {
            try {
                Mail::to($demande->email)->send(new DemandeStatutChangedMail($demande, $ancienStatut));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email statut: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.demandes-formation-internationale.show', $demande)
            ->with('success', 'Demande mise à jour avec succès.');
    }

    /**
     * Mettre à jour seulement le statut
     */
    public function updateStatut(Request $request, DemandeFormationInternationale $demande)
    {
        $request->validate([
            'statut' => 'required|in:nouveau,en_cours,traite,annule',
            'notes_admin' => 'nullable|string'
        ]);

        $ancienStatut = $demande->statut;

        $demande->update([
            'statut' => $request->statut,
            'notes_admin' => $request->notes_admin
        ]);

        if ($ancienStatut !== $request->statut) {
            try {
                Mail::to($demande->email)->send(new DemandeStatutChangedMail($demande, $ancienStatut));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email statut: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.demandes-formation-internationale.show', $demande)
            ->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeFormationInternationale $demande)
    {
        $demande->delete();

        return redirect()
            ->route('admin.demandes-formation-internationale.index')
            ->with('success', 'Demande supprimée avec succès.');
    }

    public static function getStatistics()
    {
        return [
            'total' => DemandeFormationInternationale::count(),
            'nouveau' => DemandeFormationInternationale::where('statut', 'nouveau')->count(),
            'en_cours' => DemandeFormationInternationale::where('statut', 'en_cours')->count(),
            'traite' => DemandeFormationInternationale::where('statut', 'traite')->count(),
            'annule' => DemandeFormationInternationale::where('statut', 'annule')->count(),
        ];
    }

    public static function getRecentDemandes($limit = 5)
    {
        return DemandeFormationInternationale::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
