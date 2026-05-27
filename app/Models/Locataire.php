<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'cin', 'nom', 'prenom', 'adresse',
        'telephone', 'email', 'coordonne_pro', 'mobileref',
    ];

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function contratActif()
    {
        return $this->hasOne(Contrat::class)->where('statut', 'actif');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
