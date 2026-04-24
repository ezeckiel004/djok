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
        // Codes promo
        'promo_codes',
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
        // Codes promo
        'promo_codes' => 'array',
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

    // ==============================================
    // MÉTHODES POUR LES CODES PROMO
    // ==============================================

    /**
     * Vérifie si un code promo est valide pour ce forfait
     *
     * @param string $code
     * @return bool
     */
    public function isPromoCodeValid(string $code): bool
    {
        // Vérifier si promo_codes existe et n'est pas vide
        if (empty($this->promo_codes)) {
            return false;
        }

        // Récupérer les codes promo (déjà casté en array)
        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes) || empty($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));

        foreach ($promoCodes as $promo) {
            // Vérifier que le code existe
            if (!isset($promo['code'])) {
                continue;
            }

            $promoCode = strtoupper(trim($promo['code']));

            if ($promoCode === $inputCode) {
                // Vérifier si actif
                $isActive = isset($promo['is_active']) ? (bool)$promo['is_active'] : false;
                if (!$isActive) {
                    return false;
                }

                // Vérifier le nombre d'utilisations max
                if (isset($promo['max_uses']) && $promo['max_uses'] > 0) {
                    $usedCount = isset($promo['used_count']) ? (int)$promo['used_count'] : 0;
                    if ($usedCount >= $promo['max_uses']) {
                        return false;
                    }
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Utilise un code promo (incrémente le compteur)
     *
     * @param string $code
     * @return bool
     */
    public function usePromoCode(string $code): bool
    {
        // Vérifier si promo_codes existe et n'est pas vide
        if (empty($this->promo_codes)) {
            return false;
        }

        // Récupérer les codes promo actuels (déjà casté en array)
        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes) || empty($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));
        $modified = false;

        foreach ($promoCodes as $index => $promo) {
            // Vérifier que le code existe
            if (!isset($promo['code'])) {
                continue;
            }

            $promoCode = strtoupper(trim($promo['code']));

            if ($promoCode === $inputCode) {
                // Vérifier si actif
                $isActive = isset($promo['is_active']) ? (bool)$promo['is_active'] : false;
                if (!$isActive) {
                    return false;
                }

                // Vérifier les utilisations max
                if (isset($promo['max_uses']) && $promo['max_uses'] > 0) {
                    $usedCount = isset($promo['used_count']) ? (int)$promo['used_count'] : 0;
                    if ($usedCount >= $promo['max_uses']) {
                        return false;
                    }
                }

                // Incrémenter le compteur
                $currentCount = isset($promo['used_count']) ? (int)$promo['used_count'] : 0;
                $promoCodes[$index]['used_count'] = $currentCount + 1;
                $modified = true;
                break;
            }
        }

        if (!$modified) {
            return false;
        }

        // Réassigner le tableau modifié et sauvegarder
        $this->promo_codes = $promoCodes;
        $this->save();

        return true;
    }

    /**
     * Récupère tous les codes promo actifs
     *
     * @return array
     */
    public function getActivePromoCodes(): array
    {
        if (empty($this->promo_codes)) {
            return [];
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return [];
        }

        return array_filter($promoCodes, function($promo) {
            if (!isset($promo['is_active']) || !$promo['is_active']) {
                return false;
            }

            if (isset($promo['max_uses']) && $promo['max_uses'] > 0) {
                $usedCount = $promo['used_count'] ?? 0;
                if ($usedCount >= $promo['max_uses']) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Récupère tous les codes promo (incluant les inactifs et expirés)
     *
     * @return array
     */
    public function getAllPromoCodes(): array
    {
        return $this->promo_codes ?? [];
    }

    /**
     * Vérifie si un code promo existe (même inactif)
     *
     * @param string $code
     * @return bool
     */
    public function hasPromoCode(string $code): bool
    {
        if (empty($this->promo_codes)) {
            return false;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));

        foreach ($promoCodes as $promo) {
            if (isset($promo['code']) && strtoupper(trim($promo['code'])) === $inputCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * Récupère les détails d'un code promo spécifique
     *
     * @param string $code
     * @return array|null
     */
    public function getPromoCodeDetails(string $code): ?array
    {
        if (empty($this->promo_codes)) {
            return null;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return null;
        }

        $inputCode = strtoupper(trim($code));

        foreach ($promoCodes as $promo) {
            if (isset($promo['code']) && strtoupper(trim($promo['code'])) === $inputCode) {
                return $promo;
            }
        }

        return null;
    }

    /**
     * Active ou désactive un code promo
     *
     * @param string $code
     * @param bool $active
     * @return bool
     */
    public function setPromoCodeActive(string $code, bool $active): bool
    {
        if (empty($this->promo_codes)) {
            return false;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));
        $modified = false;

        foreach ($promoCodes as $index => $promo) {
            if (isset($promo['code']) && strtoupper(trim($promo['code'])) === $inputCode) {
                $promoCodes[$index]['is_active'] = $active;
                $modified = true;
                break;
            }
        }

        if (!$modified) {
            return false;
        }

        $this->promo_codes = $promoCodes;
        $this->save();

        return true;
    }

    /**
     * Réinitialise le compteur d'utilisations d'un code promo
     *
     * @param string $code
     * @return bool
     */
    public function resetPromoCodeUsage(string $code): bool
    {
        if (empty($this->promo_codes)) {
            return false;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));
        $modified = false;

        foreach ($promoCodes as $index => $promo) {
            if (isset($promo['code']) && strtoupper(trim($promo['code'])) === $inputCode) {
                $promoCodes[$index]['used_count'] = 0;
                $modified = true;
                break;
            }
        }

        if (!$modified) {
            return false;
        }

        $this->promo_codes = $promoCodes;
        $this->save();

        return true;
    }

    /**
     * Ajoute un nouveau code promo
     *
     * @param string $code
     * @param int|null $maxUses
     * @param bool $isActive
     * @return bool
     */
    public function addPromoCode(string $code, ?int $maxUses = null, bool $isActive = true): bool
    {
        $promoCodes = $this->promo_codes ?? [];

        if (!is_array($promoCodes)) {
            $promoCodes = [];
        }

        $newCode = strtoupper(trim($code));

        // Vérifier si le code existe déjà
        foreach ($promoCodes as $promo) {
            if (isset($promo['code']) && $promo['code'] === $newCode) {
                return false;
            }
        }

        $promoCodes[] = [
            'code' => $newCode,
            'max_uses' => $maxUses,
            'used_count' => 0,
            'is_active' => $isActive,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->promo_codes = $promoCodes;
        $this->save();

        return true;
    }

    /**
     * Supprime un code promo
     *
     * @param string $code
     * @return bool
     */
    public function removePromoCode(string $code): bool
    {
        if (empty($this->promo_codes)) {
            return false;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return false;
        }

        $inputCode = strtoupper(trim($code));
        $newPromoCodes = [];
        $found = false;

        foreach ($promoCodes as $promo) {
            if (isset($promo['code']) && strtoupper(trim($promo['code'])) === $inputCode) {
                $found = true;
                continue;
            }
            $newPromoCodes[] = $promo;
        }

        if (!$found) {
            return false;
        }

        $this->promo_codes = $newPromoCodes;
        $this->save();

        return true;
    }

    /**
     * Récupère le nombre total d'utilisations de tous les codes promo
     *
     * @return int
     */
    public function getTotalPromoCodeUsageCount(): int
    {
        if (empty($this->promo_codes)) {
            return 0;
        }

        $promoCodes = $this->promo_codes;

        if (!is_array($promoCodes)) {
            return 0;
        }

        $total = 0;
        foreach ($promoCodes as $promo) {
            $total += $promo['used_count'] ?? 0;
        }

        return $total;
    }

    /**
     * Vérifie si le forfait a des codes promo actifs
     *
     * @return bool
     */
    public function hasActivePromoCodes(): bool
    {
        return count($this->getActivePromoCodes()) > 0;
    }

    /**
     * Récupère le pourcentage d'utilisation d'un code promo
     *
     * @param string $code
     * @return float|null
     */
    public function getPromoCodeUsagePercentage(string $code): ?float
    {
        $details = $this->getPromoCodeDetails($code);

        if (!$details || !isset($details['max_uses']) || $details['max_uses'] <= 0) {
            return null;
        }

        $usedCount = $details['used_count'] ?? 0;
        return round(($usedCount / $details['max_uses']) * 100, 2);
    }

    /**
     * Génère un code promo aléatoire
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomPromoCode(int $length = 8): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $code;
    }
}
