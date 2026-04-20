<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElearningForfait extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'max_concurrent_connections',
        'includes_qcm',
        'includes_examens_blancs',
        'includes_certification',
        'access_order',
        'is_active',
        'features',
        'stripe_product_id',
        'stripe_price_id',
        // Nouveaux champs
        'selected_cours_ids',
        'selected_qcms_ids',
        'selected_examens_ids',
        'include_all_cours',
        'include_all_qcms',
        'include_all_examens',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_concurrent_connections' => 'integer',
        'includes_qcm' => 'boolean',
        'includes_examens_blancs' => 'boolean',
        'includes_certification' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'array',
        'access_order' => 'integer',
        // Nouveaux casts
        'selected_cours_ids' => 'array',
        'selected_qcms_ids' => 'array',
        'selected_examens_ids' => 'array',
        'include_all_cours' => 'boolean',
        'include_all_qcms' => 'boolean',
        'include_all_examens' => 'boolean',
    ];

    // ⚠️ IMPORTANT: La relation doit s'appeler 'acces' (sans 's' au pluriel)
    // ou 'accesses' selon la convention. Pour utiliser withCount('acces'),
    // la méthode doit s'appeler 'acces()'
    public function acces(): HasMany
    {
        return $this->hasMany(ElearningAcces::class, 'forfait_id');
    }

    // Alternative avec le nom pluriel (optionnel, mais plus clair)
    // Si vous préférez utiliser 'accesses', modifiez le withCount en conséquence
    public function accesses(): HasMany
    {
        return $this->hasMany(ElearningAcces::class, 'forfait_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' €';
    }

    public function getDurationLabelAttribute(): string
    {
        return $this->duration_days . ' jours d\'accès';
    }

    public function getFeaturesListAttribute(): array
    {
        return $this->features ?? [];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('access_order')->orderBy('price');
    }

    // ==============================================
    // MÉTHODES POUR RÉCUPÉRER LE CONTENU INCLUS
    // ==============================================

    /**
     * Récupère les cours inclus dans ce forfait
     */
    public function getIncludedCours()
    {
        if ($this->include_all_cours) {
            return ElearningCours::active()->ordered()->get();
        }

        if (empty($this->selected_cours_ids)) {
            return collect();
        }

        return ElearningCours::active()
            ->whereIn('id', $this->selected_cours_ids)
            ->ordered()
            ->get();
    }

    /**
     * Récupère les QCM normaux inclus dans ce forfait
     */
    public function getIncludedQcms()
    {
        if (!$this->includes_qcm) {
            return collect();
        }

        if ($this->include_all_qcms) {
            return ElearningQcm::active()
                ->where('is_examen_blanc', false)
                ->get();
        }

        if (empty($this->selected_qcms_ids)) {
            return collect();
        }

        return ElearningQcm::active()
            ->where('is_examen_blanc', false)
            ->whereIn('id', $this->selected_qcms_ids)
            ->get();
    }

    /**
     * Récupère les examens blancs inclus dans ce forfait
     */
    public function getIncludedExamens()
    {
        if (!$this->includes_examens_blancs) {
            return collect();
        }

        if ($this->include_all_examens) {
            return ElearningQcm::active()
                ->where('is_examen_blanc', true)
                ->get();
        }

        if (empty($this->selected_examens_ids)) {
            return collect();
        }

        return ElearningQcm::active()
            ->where('is_examen_blanc', true)
            ->whereIn('id', $this->selected_examens_ids)
            ->get();
    }

    /**
     * Vérifie si un cours spécifique est inclus
     */
    public function hasCours($coursId): bool
    {
        if ($this->include_all_cours) {
            return true;
        }

        return in_array($coursId, $this->selected_cours_ids ?? []);
    }

    /**
     * Vérifie si un QCM spécifique est inclus
     */
    public function hasQcm($qcmId): bool
    {
        $qcm = ElearningQcm::find($qcmId);
        if (!$qcm) return false;

        if ($qcm->is_examen_blanc) {
            if ($this->include_all_examens) return true;
            return in_array($qcmId, $this->selected_examens_ids ?? []);
        } else {
            if ($this->include_all_qcms) return true;
            return in_array($qcmId, $this->selected_qcms_ids ?? []);
        }
    }

    /**
     * Compte le nombre total de cours inclus
     */
    public function getTotalCoursCount(): int
    {
        if ($this->include_all_cours) {
            return ElearningCours::active()->count();
        }

        return count($this->selected_cours_ids ?? []);
    }

    /**
     * Compte le nombre total de QCM inclus
     */
    public function getTotalQcmsCount(): int
    {
        $count = 0;

        if ($this->includes_qcm) {
            if ($this->include_all_qcms) {
                $count += ElearningQcm::active()->where('is_examen_blanc', false)->count();
            } else {
                $count += count($this->selected_qcms_ids ?? []);
            }
        }

        if ($this->includes_examens_blancs) {
            if ($this->include_all_examens) {
                $count += ElearningQcm::active()->where('is_examen_blanc', true)->count();
            } else {
                $count += count($this->selected_examens_ids ?? []);
            }
        }

        return $count;
    }
}
