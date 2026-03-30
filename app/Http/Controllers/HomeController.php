<?php

namespace App\Http\Controllers;

use App\Models\FormationSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        try {
            // Date actuelle au début de la journée (00:00:00)
            $now = Carbon::now()->startOfDay();

            // Log pour le débogage
            \Log::info('=== CHARGEMENT PAGE ACCUEIL ===', [
                'current_date' => $now->toDateTimeString(),
                'current_date_full' => Carbon::now()->toDateTimeString(),
                'timezone' => $now->timezoneName
            ]);

            // Récupérer les sessions à venir avec la formation active
            $upcomingSessions = FormationSession::with(['formation' => function($query) {
                $query->where('is_active', true);
            }])
                ->where('is_active', true)
                ->where('start_date', '>=', $now)  // Utilise startOfDay()
                ->where('available_places', '>', 0)
                ->orderBy('start_date', 'asc')
                ->limit(6)
                ->get();

            // Filtrer les sessions qui ont une formation active
            $upcomingSessions = $upcomingSessions->filter(function($session) {
                return $session->formation !== null;
            });

            // Réindexer la collection après filtrage
            $upcomingSessions = $upcomingSessions->values();

            // Log détaillé des sessions chargées
            \Log::info('Sessions à venir chargées', [
                'count' => $upcomingSessions->count(),
                'sessions' => $upcomingSessions->map(function($session) {
                    return [
                        'id' => $session->id,
                        'name' => $session->name,
                        'start_date' => $session->start_date,
                        'end_date' => $session->end_date,
                        'available_places' => $session->available_places,
                        'max_places' => $session->max_places,
                        'price' => $session->price,
                        'location' => $session->location,
                        'formation_id' => $session->formation_id,
                        'formation_title' => $session->formation->title ?? 'N/A',
                        'formation_active' => $session->formation->is_active ?? false,
                        'formatted_dates' => $session->formatted_dates ?? null,
                        'formatted_price' => $session->formatted_price ?? null,
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
