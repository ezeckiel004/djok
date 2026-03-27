<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'session_id',        // <-- AJOUTER CETTE LIGNE !
        'user_id',
        'paiement_id',
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'date_naissance',
        'permis_date',
        'type_formation',
        'statut',
        'progression',
        'date_debut',
        'date_fin',
        'notes',
        'donnees_supplementaires',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'permis_date' => 'date',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'donnees_supplementaires' => 'array',
        'progression' => 'decimal:2',
    ];

    /**
     * Relation avec la formation
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Relation avec la session de formation
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(FormationSession::class, 'session_id');
    }

    /**
     * Relation avec l'utilisateur (optionnelle)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le paiement (optionnelle)
     */
    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class);
    }

    /**
     * Vérifie si le participant a un accès actif
     */
    public function hasAccess(): bool
    {
        return in_array($this->statut, ['confirme', 'termine']) &&
            (!$this->date_fin || $this->date_fin > now());
    }

    /**
     * Nom complet du participant
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Age du participant
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance
            ? now()->diffInYears($this->date_naissance)
            : null;
    }

    /**
     * Nombre d'années de permis
     */
    public function getAnneePermisAttribute(): ?int
    {
        return $this->permis_date
            ? now()->diffInYears($this->permis_date)
            : null;
    }

    /**
     * Statut sous forme lisible
     */
    public function getStatutReadableAttribute(): string
    {
        $statuts = [
            'en_attente' => 'En attente',
            'confirme' => 'Confirmé',
            'annule' => 'Annulé',
            'termine' => 'Terminé',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Type de formation sous forme lisible
     */
    public function getTypeFormationReadableAttribute(): string
    {
        $types = [
            'presentiel' => 'Présentiel',
            'en_ligne' => 'En ligne',
            'mixte' => 'Mixte',
        ];

        return $types[$this->type_formation] ?? $this->type_formation;
    }
}
