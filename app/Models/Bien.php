<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use App\Models\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'zone_id', 'proprietaire_id',
        'description', 'adresse', 'ville', 'quartier',
        'type', 'nombre_unites',
    ];

    public static function bootBien(): void
    {
        static::addGlobalScope(new ZoneScope());
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function contratActif()
    {
        return $this->hasOne(Contrat::class)->where('statut', 'actif');
    }

    public function isDisponible(): bool
    {
        return $this->contratActif === null;
    }

    public function getTypeLibelleAttribute(): string
    {
        return match ($this->type) {
            'appartement' => 'Appartement',
            'chambre' => 'Chambre',
            'studio' => 'Studio',
            'villa' => 'Villa',
            'bureau' => 'Bureau',
            'magasin' => 'Magasin',
            default => 'Autre',
        };
    }
}
