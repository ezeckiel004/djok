<?php

namespace App\Http\Controllers;

use App\Models\FormationSession;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        try {
            // Récupérer les sessions à venir
            $upcomingSessions = FormationSession::with('formation')
                ->where('is_active', true)
                ->where('start_date', '>=', now())
                ->where('available_places', '>', 0)
                ->orderBy('start_date', 'asc')
                ->limit(6)
                ->get();

            // Log pour le débogage
            \Log::info('Sessions à venir chargées', [
                'count' => $upcomingSessions->count(),
                'sessions' => $upcomingSessions->map(function($session) {
                    return [
                        'id' => $session->id,
                        'name' => $session->name,
                        'start_date' => $session->start_date,
                        'formation' => $session->formation->title ?? 'N/A'
                    ];
                })->toArray()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur chargement sessions', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $upcomingSessions = collect();
        }

        return view('welcome', compact('upcomingSessions'));
    }
}
