<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'service_id',
        'service_type',
        'reservation_id',
        'location_id',
        'conciergerie_id',
        'formation_internationale_id',
        'elearning_forfait_id',
        'reference',
        'amount',
        'currency',
        'status',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'stripe_response',
        'paid_at',
        'customer_info',
        'service_details',
        'refunded_at',
        'refund_reason',
        'refund_data',
        'deleted_by',
        'deleted_reason',
    ];

    protected $casts = [
        'stripe_response' => 'array',
        'customer_info' => 'array',
        'service_details' => 'array',
        'refund_data' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'amount' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (peut être null pour les achats sans compte)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'utilisateur qui a supprimé le paiement
     */
    public function deletedByUser()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Relation avec le forfait e-learning
     */
    public function elearningForfait()
    {
        return $this->belongsTo(ElearningForfait::class, 'elearning_forfait_id');
    }

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        // CORRECTION : Pas de condition where ici
        return $this->belongsTo(Formation::class, 'service_id');
    }

    /**
     * Relation avec la réservation VTC
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Relation avec la location
     */
    public function location()
    {
        return $this->belongsTo(\App\Models\LocationReservation::class, 'location_id');
    }

    /**
     * Relation avec la conciergerie
     */
    public function conciergerie()
    {
        return $this->belongsTo(\App\Models\ConciergerieDemande::class);
    }

    /**
     * Relation avec la formation internationale
     */
    public function formationInternationale()
    {
        return $this->belongsTo(\App\Models\DemandeFormationInternationale::class);
    }

    /**
     * Relation avec les inscriptions utilisateur (UserFormation)
     */
    public function userFormations()
    {
        return $this->hasMany(UserFormation::class, 'formation_id', 'service_id')
            ->where('user_id', $this->user_id);
    }

    /**
     * Relation avec les participants
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Récupérer le service associé
     */
    public function getServiceAttribute()
    {
        switch ($this->service_type) {
            case 'formation':
                return $this->formation;
            case 'reservation':
                return $this->reservation;
            case 'location':
                return $this->location;
            case 'conciergerie':
                return $this->conciergerie;
            case 'formation_internationale':
                return $this->formationInternationale;
            case 'elearning':
                return $this->elearningForfait;
            default:
                return null;
        }
    }

    /**
     * Récupérer le nom du service
     */
    public function getServiceNameAttribute()
    {
        if ($this->service) {
            switch ($this->service_type) {
                case 'formation':
                    return $this->formation->title ?? 'Formation inconnue';
                case 'reservation':
                    return 'Réservation VTC ' . ($this->reservation->reference ?? 'N/A');
                case 'location':
                    return 'Location ' . ($this->location->vehicule->marque ?? 'Véhicule');
                case 'conciergerie':
                    return 'Service conciergerie ' . ($this->conciergerie->reference ?? 'N/A');
                case 'formation_internationale':
                    return 'Formation internationale ' . ($this->formationInternationale->reference ?? 'N/A');
                case 'elearning':
                    return 'Forfait E-learning ' . ($this->elearningForfait->name ?? 'N/A');
            }
        }

        return $this->service_details['service_name'] ?? 'Service inconnu';
    }

    /**
     * Accessor pour l'email du client
     */
    public function getCustomerEmailAttribute()
    {
        // Priorité: email de l'utilisateur lié
        if ($this->user && $this->user->email) {
            return $this->user->email;
        }

        // Sinon: email des infos client Stripe
        return $this->customer_info['email'] ?? null;
    }

    /**
     * Accessor pour le nom du client
     */
    public function getCustomerNameAttribute()
    {
        // Priorité: nom de l'utilisateur lié
        if ($this->user && $this->user->name) {
            return $this->user->name;
        }

        // Sinon: nom des infos client Stripe
        return $this->customer_info['name'] ?? 'Client';
    }

    /**
     * Vérifier si c'est un paiement pour formations
     */
    public function isFormation()
    {
        return $this->service_type === 'formation';
    }

    /**
     * Vérifier si c'est un paiement pour VTC
     */
    public function isReservation()
    {
        return $this->service_type === 'reservation';
    }

    /**
     * Vérifier si c'est un paiement pour location
     */
    public function isLocation()
    {
        return $this->service_type === 'location';
    }

    /**
     * Vérifier si c'est un paiement pour conciergerie
     */
    public function isConciergerie()
    {
        return $this->service_type === 'conciergerie';
    }

    /**
     * Vérifier si c'est un paiement pour formation internationale
     */
    public function isFormationInternationale()
    {
        return $this->service_type === 'formation_internationale';
    }

    /**
     * Déterminer si l'achat est lié à un compte utilisateur
     */
    public function isLinkedToAccount()
    {
        return !is_null($this->user_id) && !is_null($this->user);
    }

    /**
     * Déterminer si l'achat est fait par un visiteur
     */
    public function isGuestPurchase()
    {
        return is_null($this->user_id) && isset($this->customer_info['email']);
    }

    /**
     * Vérifier si le paiement est payé
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Vérifier si le paiement est remboursé
     */
    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    /**
     * Vérifier si le paiement est en attente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le paiement est supprimé
     */
    public function isDeleted()
    {
        return !is_null($this->deleted_at);
    }

    /**
     * Marquer comme payé
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Marquer comme remboursé
     */
    public function markAsRefunded($reason = null, $refundData = null)
    {
        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_reason' => $reason,
            'refund_data' => $refundData,
        ]);
    }

    /**
     * Supprimer le paiement avec log (soft delete)
     */
    public function safeDelete($deletedBy = null, $reason = null)
    {
        $this->deleted_by = $deletedBy ?? auth()->id();
        $this->deleted_reason = $reason;
        $this->save();

        return $this->delete();
    }

    /**
     * Restaurer un paiement supprimé
     */
    public function restorePayment()
    {
        $this->deleted_by = null;
        $this->deleted_reason = null;
        $this->save();

        return $this->restore();
    }

    /**
     * Récupérer le participant associé (pour formations)
     */
    public function getAssociatedParticipant()
    {
        if (!$this->isFormation()) {
            return null;
        }

        // Chercher d'abord par paiement_id
        $participant = $this->participants()->first();

        if (!$participant && $this->customer_email) {
            // Chercher par email si pas trouvé par paiement_id
            $participant = Participant::where('email', $this->customer_email)
                ->where('formation_id', $this->service_id)
                ->first();
        }

        return $participant;
    }

    /**
     * Récupérer les inscriptions utilisateur associées (pour formations)
     */
    public function getAssociatedUserFormations()
    {
        if (!$this->isFormation() || !$this->user_id) {
            return collect();
        }

        return UserFormation::where('user_id', $this->user_id)
            ->where('formation_id', $this->service_id)
            ->get();
    }

    /**
     * Scope pour les paiements payés
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les paiements remboursés
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    /**
     * Scope pour les paiements annulés
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Scope pour les paiements par type de service
     */
    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    /**
     * Scope pour les paiements non supprimés (inclus par défaut avec SoftDeletes)
     * Les paiements supprimés sont exclus automatiquement
     */
    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope pour les paiements supprimés
     */
    public function scopeOnlyDeleted($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope pour les paiements supprimés par un utilisateur spécifique
     */
    public function scopeDeletedBy($query, $userId)
    {
        return $query->where('deleted_by', $userId);
    }

    /**
     * Formater le montant pour l'affichage
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', ' ') . ' €';
    }

    /**
     * Obtenir le statut formaté
     */
    public function getFormattedStatusAttribute()
    {
        $statusMap = [
            'paid' => 'Payé',
            'pending' => 'En attente',
            'refunded' => 'Remboursé',
            'canceled' => 'Annulé',
            'failed' => 'Échoué',
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Obtenir la couleur du badge selon le statut
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            'canceled' => 'bg-red-100 text-red-800',
            'failed' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obtenir la couleur du badge selon le type de service
     */
    public function getServiceTypeColorAttribute()
    {
        $colors = [
            'formation' => 'bg-blue-100 text-blue-800',
            'reservation' => 'bg-green-100 text-green-800',
            'location' => 'bg-purple-100 text-purple-800',
            'conciergerie' => 'bg-yellow-100 text-yellow-800',
            'formation_internationale' => 'bg-indigo-100 text-indigo-800',
            'elearning' => 'bg-pink-100 text-pink-800',
        ];

        return $colors[$this->service_type] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obtenir le type de service formaté
     */
    public function getFormattedServiceTypeAttribute()
    {
        $types = [
            'formation' => 'Formation',
            'reservation' => 'Réservation VTC',
            'location' => 'Location',
            'conciergerie' => 'Conciergerie',
            'formation_internationale' => 'Formation Internationale',
            'elearning' => 'E-learning',
        ];

        return $types[$this->service_type] ?? $this->service_type;
    }

    /**
     * Vérifier si le paiement peut être supprimé
     * On ne peut supprimer que les paiements non payés et non remboursés
     */
    public function isDeletable()
    {
        return !in_array($this->status, ['paid', 'refunded']);
    }

    /**
     * Obtenir la raison pour laquelle le paiement ne peut pas être supprimé
     */
    public function getNonDeletableReasonAttribute()
    {
        if ($this->status === 'paid') {
            return 'Ce paiement a déjà été effectué et ne peut pas être supprimé pour des raisons de traçabilité financière.';
        }

        if ($this->status === 'refunded') {
            return 'Ce paiement a déjà été remboursé et ne peut pas être supprimé pour des raisons de traçabilité financière.';
        }

        return null;
    }


    // Ajoutez ces méthodes à la fin du modèle Paiement

    /**
     * Obtenir la couleur du badge pour le soft delete
     */
    public function getDeletedStatusColorAttribute()
    {
        if ($this->trashed()) {
            return 'bg-red-100 text-red-800';
        }
        return 'bg-green-100 text-green-800';
    }

    /**
     * Obtenir le statut formaté du soft delete
     */
    public function getDeletedStatusAttribute()
    {
        if ($this->trashed()) {
            return 'Supprimé le ' . $this->deleted_at->format('d/m/Y H:i');
        }
        return 'Actif';
    }

    /**
     * Obtenir le nom de l'utilisateur qui a supprimé
     */
    public function getDeletedByNameAttribute()
    {
        if ($this->deleted_by && $this->deletedByUser) {
            return $this->deletedByUser->name;
        }
        return 'Inconnu';
    }
}
