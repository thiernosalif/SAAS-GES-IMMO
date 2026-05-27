<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'cin', 'nom', 'prenom', 'adresse', 'telephone', 'email',
        'date_deb_mandat', 'date_fin_mandat', 'taux_commission_specifique',
    ];

    protected $casts = [
        'date_deb_mandat' => 'date',
        'date_fin_mandat' => 'date',
        'taux_commission_specifique' => 'decimal:2',
    ];

    public function biens()
    {
        return $this->hasMany(Bien::class);
    }

    public function situations()
    {
        return $this->hasMany(Situation::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getTauxCommissionEffectifAttribute(): float
    {
        if ($this->taux_commission_specifique !== null) {
            return (float) $this->taux_commission_specifique;
        }

        $agency = app('current_agency');
        return $agency ? (float) $agency->taux_commission : 10.0;
    }
}
