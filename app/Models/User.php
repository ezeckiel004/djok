<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'city',
        'country',
        'birth_date',
        'cni_number',
        'driver_license',
        'newsletter',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // CORRECTION : Utiliser $casts au lieu de la méthode casts()
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'newsletter' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function assignedContacts()
    {
        return $this->hasMany(Contact::class, 'assigned_to');
    }

    // NOUVELLES RELATIONS POUR LES FORMATIONS
    public function userFormations()
    {
        return $this->hasMany(UserFormation::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'user_formations')
            ->using(UserFormation::class)
            ->withPivot('id', 'status', 'access_start', 'access_end', 'progress', 'paiement_id', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    // RELATIONS MANQUANTES CORRIGÉES
    public function locationReservations()
    {
        return $this->hasMany(LocationReservation::class);
    }

    public function conciergerieDemandes()
    {
        // Relation basée sur l'email car la table n'a pas de user_id
        return $this->hasMany(ConciergerieDemande::class, 'email', 'email');
    }

    public function formationInscriptions()
    {
        // Alias pour userFormations pour être cohérent avec le code existant
        return $this->hasMany(UserFormation::class);
    }

    // RELATIONS POUR LES FACTURES
    public function factures()
    {
        // Alias pour paiements
        return $this->hasMany(Paiement::class);
    }

    // Méthode pour vérifier l'accès à une formation
    public function hasAccessToFormation($formationId)
    {
        return $this->formations()
            ->where('formation_id', $formationId)
            ->where('user_formations.status', 'active')
            ->where(function ($query) {
                $query->whereNull('user_formations.access_end')
                    ->orWhere('user_formations.access_end', '>', now());
            })
            ->exists();
    }

    // Méthode pour récupérer les formations actives
    public function activeFormations()
    {
        return $this->formations()
            ->where('user_formations.status', 'active')
            ->where(function ($query) {
                $query->whereNull('user_formations.access_end')
                    ->orWhere('user_formations.access_end', '>', now());
            })
            ->orderBy('user_formations.created_at', 'desc');
    }

    // Méthodes pour obtenir les compteurs pour le tableau de bord
    public function getLocationReservationsCountAttribute()
    {
        return $this->locationReservations()->count();
    }

    public function getConciergerieDemandesCountAttribute()
    {
        return $this->conciergerieDemandes()->count();
    }

    public function getFormationInscriptionsCountAttribute()
    {
        // Compte les formations actives
        return $this->formationInscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('access_end')
                    ->orWhere('access_end', '>', now());
            })
            ->count();
    }

    public function getReservationsCountAttribute()
    {
        return $this->reservations()->count();
    }

    public function getFacturesPayeesCountAttribute()
    {
        // Compte les factures payées
        return $this->factures()
            ->where('status', 'paid')
            ->count();
    }

    // Méthode hasRole compatible avec vos gates
    public function hasRole($role): bool
    {
        // Si c'est un ID numérique
        if (is_numeric($role)) {
            return $this->role_id == $role;
        }

        // Si c'est une instance de Role
        if ($role instanceof Role) {
            return $this->role_id === $role->id;
        }

        // Si c'est une chaîne (slug ou nom)
        if (is_string($role)) {
            // Si la relation est déjà chargée
            if ($this->relationLoaded('role')) {
                return $this->role && (
                    $this->role->slug === $role ||
                    $this->role->name === $role
                );
            }

            // Sinon, faire une requête
            return Role::where('id', $this->role_id)
                ->where(function ($query) use ($role) {
                    $query->where('slug', $role)
                        ->orWhere('name', $role);
                })
                ->exists();
        }

        return false;
    }

    // Helpers pour vérifier les rôles spécifiques (compatibles avec vos gates)
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isChauffeur(): bool
    {
        return $this->hasRole('chauffeur');
    }

    public function isFormateur(): bool
    {
        return $this->hasRole('formateur');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    // Méthode pour vérifier plusieurs rôles
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    // Compatible avec le middleware CheckRole
    public function hasRoles($roles): bool
    {
        $rolesArray = is_array($roles) ? $roles : explode('|', $roles);
        return $this->hasAnyRole($rolesArray);
    }

    // Méthode pour vérifier les permissions
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $rolePermissions = $this->role?->permissions ?? [];

        // Vérifier si l'utilisateur a toutes les permissions (*)
        if (in_array('*', $rolePermissions)) {
            return true;
        }

        return in_array($permission, $rolePermissions);
    }

    // Helper pour récupérer le nom du rôle
    public function getRoleName(): ?string
    {
        return $this->role?->name;
    }

    public function getRoleSlug(): ?string
    {
        return $this->role?->slug;
    }

    // Méthodes pour les gates (compatibles avec votre AuthServiceProvider)
    public function canAccessAdminDashboard(): bool
    {
        return $this->hasRole('admin');
    }

    public function canAccessClientDashboard(): bool
    {
        return $this->hasRole('client');
    }

    public function canAccessChauffeurDashboard(): bool
    {
        return $this->hasRole('chauffeur');
    }

    public function canAccessFormateurDashboard(): bool
    {
        return $this->hasRole('formateur');
    }

    // Méthode pour vérifier si l'utilisateur est abonné à la newsletter
    public function isSubscribedToNewsletter(): bool
    {
        return $this->newsletter === true;
    }

    // Méthode pour s'abonner/désabonner de la newsletter
    public function toggleNewsletter(): bool
    {
        $this->newsletter = !$this->newsletter;
        return $this->save();
    }

    // Méthodes d'accès rapide aux attributs de comptage
    public function getDashboardStatsAttribute()
    {
        return [
            'location_reservations' => $this->getLocationReservationsCountAttribute(),
            'conciergerie_demandes' => $this->getConciergerieDemandesCountAttribute(),
            'formation_inscriptions' => $this->getFormationInscriptionsCountAttribute(),
            'reservations' => $this->getReservationsCountAttribute(),
            'factures_payees' => $this->getFacturesPayeesCountAttribute(),
        ];
    }

    // Formatter les attributs
    public function getFormattedPhoneAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        $phone = preg_replace('/\D/', '', $this->phone);
        if (strlen($phone) === 9 && strpos($phone, '33') !== 0) {
            return '+33 ' . substr($phone, 0, 1) . ' ' . substr($phone, 1, 2) . ' ' .
                substr($phone, 3, 2) . ' ' . substr($phone, 5, 2) . ' ' . substr($phone, 7, 2);
        }

        return $this->phone;
    }

    public function getFormattedCniAttribute(): ?string
    {
        if (empty($this->cni_number)) {
            return null;
        }

        if (preg_match('/^([A-Z]{2})(\d{5})$/', strtoupper($this->cni_number), $matches)) {
            return $matches[1] . ' ' . $matches[2];
        }

        if (preg_match('/^(\d{3})(\d{3})(\d{3})(\d{3})$/', $this->cni_number, $matches)) {
            return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3] . ' ' . $matches[4];
        }

        return $this->cni_number;
    }

    public function getFormattedDriverLicenseAttribute(): ?string
    {
        if (empty($this->driver_license)) {
            return null;
        }

        $license = preg_replace('/\D/', '', $this->driver_license);
        if (strlen($license) === 12) {
            return substr($license, 0, 3) . ' ' . substr($license, 3, 3) . ' ' .
                substr($license, 6, 3) . ' ' . substr($license, 9, 3);
        }

        return $this->driver_license;
    }
}
