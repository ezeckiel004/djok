<?php
// app/Mail/FormationInternationaleNotificationAdmin.php

namespace App\Mail;

use App\Models\DemandeFormationInternationale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormationInternationaleNotificationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(DemandeFormationInternationale $demande)
    {
        $this->demande = $demande;
    }

    public function build()
    {
        $nomResponsable = $this->demande->nom_responsable ?? $this->demande->nom_complet;

        return $this->subject('🔔 NOUVELLE DEMANDE - ' . $nomResponsable . ' - Formation Internationale')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.formation-internationale-notification-admin')
            ->with([
                'demande' => $this->demande,
                'nomResponsable' => $nomResponsable,
                'nomEntreprise' => $this->demande->nom_entreprise,
                'destination' => $this->demande->destination_label,
                'nombreParticipants' => $this->demande->nombre_participants,
                'typeEvenements' => $this->demande->type_evenement_list,
                'dateDemande' => $this->demande->created_at->format('d/m/Y à H:i'),
                'adminUrl' => route('admin.demandes-formation-internationale.show', $this->demande->id),
            ]);
    }
}
