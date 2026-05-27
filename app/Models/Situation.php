<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'proprietaire_id', 'periode_debut', 'periode_fin',
        'type', 'total_loyers_percus', 'total_charges',
        'commission_agence', 'net_proprietaire', 'statut',
        'genere_par', 'pdf_path',
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'total_loyers_percus' => 'decimal:2',
        'total_charges' => 'decimal:2',
        'commission_agence' => 'decimal:2',
        'net_proprietaire' => 'decimal:2',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function lignes()
    {
        return $this->hasMany(SituationLigne::class);
    }

    public function generePar()
    {
        return $this->belongsTo(User::class, 'genere_par');
    }
}
