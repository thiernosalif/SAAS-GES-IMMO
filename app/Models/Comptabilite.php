<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Comptabilite extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'type', 'montant', 'motif', 'categorie',
        'reference_paiement_id', 'saisi_par',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function paiementReference()
    {
        return $this->belongsTo(Paiement::class, 'reference_paiement_id');
    }

    public function saisiPar()
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }
}
