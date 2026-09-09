<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactConfirmation;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact
     */
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('contact', compact('services'));
    }

    /**
     * Traiter l'envoi du formulaire de contact public
     */
    public function store(Request $request)
    {
        Log::info('Formulaire contact public reçu', [
            'data' => $request->except(['_token']),
        ]);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'service_id' => 'required',
            'autre_service' => 'nullable|string|max:255|required_if:service_id,autre',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'service_id.required' => 'Veuillez sélectionner un service',
            'autre_service.required_if' => 'Veuillez préciser votre demande lorsque vous sélectionnez "Autre service"',
            'message.min' => 'Veuillez donner plus de détails sur votre demande (minimum 10 caractères)',
            'message.required' => 'Veuillez décrire votre demande',
        ]);

        if ($validator->fails()) {
            Log::error('Validation contact échouée:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Détecter si c'est une demande de formation
            $isFormationRequest = str_contains($request->header('referer'), 'formation');

            // Vérification que le service_id est valide (sauf "autre")
            $validServiceIds = Service::pluck('id')->toArray();
            $validServiceIds[] = 'autre';

            if (!in_array($request->service_id, $validServiceIds)) {
                return back()->withErrors(['service_id' => 'Le service sélectionné est invalide'])->withInput();
            }

            // Créer le contact dans la table contact_messages
            $contactMessage = ContactMessage::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'service_id' => $request->service_id === 'autre' ? null : $request->service_id,
                'autre_service' => $request->service_id === 'autre' ? $request->autre_service : null,
                'message' => $request->message,
                'is_read' => false,
                'is_replied' => false,
            ]);

            Log::info('Message créé avec ID:', [
                'id' => $contactMessage->id,
                'email' => $contactMessage->email,
            ]);

            // Envoyer l'email de confirmation
            try {
                $mailClass = new ContactConfirmation($contactMessage);
                if ($isFormationRequest) {
                    $mailClass->subject('Demande d\'information formation reçue - DJOK PRESTIGE');
                }
                Mail::to($contactMessage->email)->send($mailClass);
                Log::info('Email confirmation envoyé à:', ['email' => $contactMessage->email]);
            } catch (\Exception $e) {
                Log::error('Erreur email confirmation:', ['error' => $e->getMessage()]);
            }

            // Envoyer la notification à l'admin (Sirarou2@yahoo.fr)
            Log::info('Notification admin - appel depuis ContactController@store', [
                'contact_id' => $contactMessage->id,
                'is_formation_request' => $isFormationRequest,
            ]);
            AdminContactController::notifyAdmin($contactMessage);

            $successMessage = $isFormationRequest
                ? 'Votre demande d\'information sur la formation a été envoyée avec succès ! Notre équipe formation vous répondra dans les plus brefs délais.'
                : 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';

            return back()->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Erreur soumission formulaire contact : ' . $e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.')->withInput();
        }
    }

    /**
     * Traiter le formulaire de support depuis l'espace client
     */
    public function storeSupport(Request $request)
    {
        Log::info('Formulaire support reçu depuis espace client', [
            'data' => $request->except(['_token']),
        ]);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'service_type' => 'required|string|in:formations,location,vtc,conciergerie,facturation,autre',
            'autre_service' => 'nullable|string|max:255|required_if:service_type,autre',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'service_type.required' => 'Veuillez sélectionner un type de demande',
            'service_type.in' => 'Le type de demande sélectionné est invalide',
            'autre_service.required_if' => 'Veuillez préciser votre demande lorsque vous sélectionnez "Autre demande"',
            'message.min' => 'Veuillez donner plus de détails sur votre demande (minimum 10 caractères)',
            'message.required' => 'Veuillez décrire votre demande',
        ]);

        if ($validator->fails()) {
            Log::error('Validation support échouée:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Convertir le service_type en service_id correspondant
            $serviceMapping = [
                'formations' => Service::where('name', 'like', '%formation%')->first()?->id,
                'location' => Service::where('name', 'like', '%location%')->first()?->id,
                'vtc' => Service::where('name', 'like', '%vtc%')->orWhere('name', 'like', '%transport%')->first()?->id,
                'conciergerie' => Service::where('name', 'like', '%conciergerie%')->first()?->id,
                'facturation' => Service::where('name', 'like', '%facturation%')->orWhere('name', 'like', '%paiement%')->first()?->id,
            ];

            $service_id = null;
            $autre_service = null;

            if ($request->service_type === 'autre') {
                $autre_service = $request->autre_service;
            } else {
                $service_id = $serviceMapping[$request->service_type] ?? null;
            }

            // Préparer le message complet pour le support
            $messageContent = "Demande de support depuis l'espace client\n\n";

            if ($request->service_type === 'autre' && $request->autre_service) {
                $messageContent .= "Type de demande : " . $request->autre_service . "\n\n";
            }

            $messageContent .= "Message :\n" . $request->message . "\n\n";
            $messageContent .= "---\n";
            $messageContent .= "Client : " . $request->nom . "\n";
            $messageContent .= "Email : " . $request->email . "\n";
            $messageContent .= "Téléphone : " . ($request->telephone ?? 'Non fourni') . "\n";
            $messageContent .= "Type de service : " . $request->service_type . "\n";
            $messageContent .= "Utilisateur connecté : Oui (ID: " . auth()->id() . ")";

            // Créer le contact dans la table contact_messages
            $contactMessage = ContactMessage::create([
                'nom' => $request->nom . ' [Espace Client]',
                'email' => $request->email,
                'telephone' => $request->telephone,
                'service_id' => $service_id,
                'autre_service' => $autre_service,
                'message' => $messageContent,
                'is_read' => false,
                'is_replied' => false,
            ]);

            Log::info('Support créé avec ID:', [
                'id' => $contactMessage->id,
                'email' => $contactMessage->email,
                'service_type' => $request->service_type,
            ]);

            // Envoyer l'email de confirmation
            try {
                $mailClass = new ContactConfirmation($contactMessage);
                $mailClass->subject('Confirmation de votre demande de support - DJOK PRESTIGE');
                Mail::to($contactMessage->email)->send($mailClass);
                Log::info('Email confirmation support envoyé à:', ['email' => $contactMessage->email]);
            } catch (\Exception $e) {
                Log::error('Erreur email confirmation support:', ['error' => $e->getMessage()]);
            }

            // Envoyer la notification à l'admin (Sirarou2@yahoo.fr)
            Log::info('Notification admin - appel depuis ContactController@storeSupport', [
                'contact_id' => $contactMessage->id,
                'service_type' => $request->service_type,
            ]);
            AdminContactController::notifyAdmin($contactMessage);

            return back()->with('success', 'Votre demande de support a été envoyée avec succès ! Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            Log::error('Erreur soumission formulaire support : ' . $e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.')->withInput();
        }
    }
}
