<?php
// app/Http/Controllers/FormationInternationaleController.php

namespace App\Http\Controllers;

use App\Models\DemandeFormationInternationale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormationInternationaleConfirmation;
use App\Mail\FormationInternationaleNotificationAdmin;

class FormationInternationaleController extends Controller
{
    public function store(Request $request)
    {
        // Validation adaptée - certains champs optionnels
        $validator = Validator::make($request->all(), [
            'nom_entreprise' => 'nullable|string|max:255',
            'nom_responsable' => 'required|string|max:255', // On garde nom_complet
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'destination_souhaitee' => 'nullable|string|in:dubai,usa,europe,afrique,autre',
            'nombre_participants' => 'nullable|integer|min:1|max:999',
            'type_evenement' => 'nullable|array',
            'type_evenement.*' => 'string|in:formation,seminaire,voyage_business,team_building',
            'message' => 'required|string|min:10|max:5000', // Gardé pour compatibilité
            'objectifs_projet' => 'nullable|string|max:5000',
        ], [
            'nom_responsable.required' => 'Le nom du responsable est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'message.required' => 'Veuillez décrire votre projet.',
            'message.min' => 'Veuillez donner plus de détails sur votre projet.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('formation.international')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        try {
            // Préparer les données
            $data = [
                'nom_complet' => $request->nom_responsable,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'message' => $request->message ?? $request->objectifs_projet,
                'statut' => 'nouveau',
                'notes_admin' => 'Demande créée via formulaire public'
            ];

            // Ajouter les nouveaux champs s'ils existent
            if ($request->has('nom_entreprise')) {
                $data['nom_entreprise'] = $request->nom_entreprise;
            }

            if ($request->has('destination_souhaitee')) {
                $data['destination_souhaitee'] = $request->destination_souhaitee;
            }

            if ($request->has('nombre_participants')) {
                $data['nombre_participants'] = $request->nombre_participants;
            }

            if ($request->has('type_evenement')) {
                $data['type_evenement'] = $request->type_evenement;
            }

            if ($request->has('objectifs_projet')) {
                $data['objectifs_projet'] = strip_tags($request->objectifs_projet);
            }

            // Création de la demande
            $demande = DemandeFormationInternationale::create($data);

            // Envoyer emails...
            Mail::to($request->email)->send(new FormationInternationaleConfirmation($demande));
            $this->sendNotificationEmail($demande);

            Log::info('Nouvelle demande de séminaire/formation internationale créée', [
                'id' => $demande->id,
                'responsable' => $request->nom_responsable,
                'email' => $request->email
            ]);

            return redirect()
                ->route('formation.international')
                ->with('success', 'Votre demande a été envoyée avec succès ! Nous vous contacterons dans les plus brefs délais.')
                ->with('email', $request->email);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la demande: ' . $e->getMessage());

            return redirect()
                ->route('formation.international')
                ->withInput()
                ->with('error', 'Une erreur technique est survenue. Veuillez réessayer.');
        }
    }

    private function sendNotificationEmail($demande)
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@djokprestige.com');
            $internationalEmail = config('mail.international_email', 'international@djokprestige.com');

            $adminEmails = array_filter([$adminEmail, $internationalEmail]);

            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new FormationInternationaleNotificationAdmin($demande));
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email notification: ' . $e->getMessage());
        }
    }
}
