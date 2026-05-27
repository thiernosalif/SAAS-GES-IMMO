<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SituationLigne extends Model
{
    protected $fillable = [
        'situation_id', 'contrat_id', 'locataire_nom',
        'bien_description', 'periode',
        'loyer_du', 'montant_percu', 'ecart', 'commission',
    ];

    protected $casts = [
        'periode' => 'date',
        'loyer_du' => 'decimal:2',
        'montant_percu' => 'decimal:2',
        'ecart' => 'decimal:2',
        'commission' => 'decimal:2',
    ];

    public function situation()
    {
        return $this->belongsTo(Situation::class);
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
}
