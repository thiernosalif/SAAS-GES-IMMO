<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'locataire_id', 'motif', 'description',
        'statut', 'traite_par',
    ];

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }
}
