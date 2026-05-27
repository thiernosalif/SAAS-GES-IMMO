<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contrat extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'bien_id', 'locataire_id', 'type_logement',
        'loyer_mensuel', 'charges_mensuelles', 'avance_loyer', 'caution',
        'date_debut', 'date_fin', 'statut', 'disponibilite',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'loyer_mensuel' => 'decimal:2',
        'charges_mensuelles' => 'decimal:2',
        'caution' => 'decimal:2',
        'disponibilite' => 'boolean',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function recu()
    {
        return $this->hasOneThrough(Recu::class, Paiement::class);
    }

    public function situationLignes()
    {
        return $this->hasMany(SituationLigne::class);
    }

    public function expiresInDays(): ?int
    {
        if (!$this->date_fin) {
            return null;
        }
        return now()->diffInDays($this->date_fin, false);
    }

    public function isExpiringSoon(): bool
    {
        $days = $this->expiresInDays();
        return $days !== null && $days >= 0 && $days <= 30;
    }

    public function getTotalMensuelAttribute(): float
    {
        return (float) $this->loyer_mensuel + (float) $this->charges_mensuelles;
    }
}
