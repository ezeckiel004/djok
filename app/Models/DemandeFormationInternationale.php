<?php
// app/Models/DemandeFormationInternationale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeFormationInternationale extends Model
{
    protected $table = 'demande_formation_internationales';

    protected $fillable = [
        'nom_complet',
        'nom_entreprise',
        'nationalite',
        'email',
        'telephone',
        'whatsapp',
        'formation_id',
        'formation_personnalisee',
        'message',
        'services',
        'date_debut',
        'duree',
        'destination_souhaitee',
        'nombre_participants',
        'type_evenement',
        'objectifs_projet',
        'statut',
        'notes_admin'
    ];

    protected $casts = [
        'services' => 'array',
        'type_evenement' => 'array',
        'date_debut' => 'date'
    ];

    // Accesseurs pour les nouveaux champs
    public function getDestinationLabelAttribute()
    {
        $destinations = [
            'dubai' => 'Dubaï',
            'usa' => 'USA',
            'europe' => 'Europe',
            'afrique' => 'Afrique',
            'autre' => 'Autre'
        ];

        return $destinations[$this->destination_souhaitee] ?? $this->destination_souhaitee ?? 'Non spécifié';
    }

    public function getTypeEvenementListAttribute()
    {
        if (empty($this->type_evenement) || !is_array($this->type_evenement)) {
            return [];
        }

        $types = [
            'formation' => 'Formation',
            'seminaire' => 'Séminaire',
            'voyage_business' => 'Voyage business',
            'team_building' => 'Team building'
        ];

        return array_map(function($type) use ($types) {
            return $types[$type] ?? $type;
        }, $this->type_evenement);
    }

    public function getStatutLabelAttribute()
    {
        $statuts = [
            'nouveau' => 'Nouveau',
            'en_cours' => 'En cours',
            'traite' => 'Traité',
            'annule' => 'Annulé'
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute()
    {
        return match ($this->statut) {
            'nouveau' => 'bg-yellow-100 text-yellow-800',
            'en_cours' => 'bg-blue-100 text-blue-800',
            'traite' => 'bg-green-100 text-green-800',
            'annule' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
