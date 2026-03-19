<?php
// app/Mail/FormationInternationaleConfirmation.php

namespace App\Mail;

use App\Models\DemandeFormationInternationale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormationInternationaleConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(DemandeFormationInternationale $demande)
    {
        $this->demande = $demande;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre demande - DJOK PRESTIGE International')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo('international@djokprestige.com', 'Service International DJOK PRESTIGE')
            ->view('emails.formation-internationale-confirmation')
            ->with([
                'demande' => $this->demande,
                'nomResponsable' => $this->demande->nom_responsable ?? $this->demande->nom_complet,
                'nomEntreprise' => $this->demande->nom_entreprise,
                'destination' => $this->demande->destination_label,
                'nombreParticipants' => $this->demande->nombre_participants,
                'typeEvenements' => $this->demande->type_evenement_list,
                'dateDemande' => $this->demande->created_at->format('d/m/Y à H:i'),
                'telephoneContact' => '06 99 16 44 55',
                'whatsappContact' => '06 99 16 44 55',
                'emailContact' => 'contact@djokprestige.com',
            ]);
    }
}
