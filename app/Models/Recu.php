<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'paiement_id', 'numero', 'pdf_path',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
