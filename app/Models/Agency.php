<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $fillable = [
        'name', 'slug', 'email', 'telephone', 'adresse', 'ville',
        'logo', 'couleur_principale', 'is_active', 'plan', 'expires_at',
        'taux_commission', 'pays', 'locale_defaut',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'taux_commission' => 'decimal:2',
    ];

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function proprietaires()
    {
        return $this->hasMany(Proprietaire::class);
    }

    public function biens()
    {
        return $this->hasMany(Bien::class);
    }

    public function locataires()
    {
        return $this->hasMany(Locataire::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }
}
