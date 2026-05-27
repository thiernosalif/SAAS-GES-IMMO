<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'agency_id', 'zone_id', 'nom', 'prenom', 'email',
        'password', 'role', 'telephone', 'pays', 'locale',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAgencyAdmin(): bool
    {
        return $this->role === 'agency_admin';
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function isReadonly(): bool
    {
        return $this->role === 'readonly';
    }

    public function canManageAgency(): bool
    {
        return in_array($this->role, ['super_admin', 'agency_admin']);
    }

    public function hasZoneRestriction(): bool
    {
        return in_array($this->role, ['gestionnaire', 'readonly']);
    }
}
