<?php

namespace App\Models;

use App\Models\Concerns\BelongsToAgency;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use BelongsToAgency;

    protected $fillable = [
        'agency_id', 'contrat_id', 'locataire_id', 'periode',
        'montant', 'montant_du', 'mode_paiement',
        'avance', 'acompte', 'complement',
        'transaction_reference', 'statut', 'encaisse_par',
    ];

    protected $casts = [
        'periode' => 'date',
        'montant' => 'decimal:2',
        'montant_du' => 'decimal:2',
        'avance' => 'decimal:2',
        'acompte' => 'decimal:2',
        'complement' => 'decimal:2',
    ];

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function encaissePar()
    {
        return $this->belongsTo(User::class, 'encaisse_par');
    }

    public function recu()
    {
        return $this->hasOne(Recu::class);
    }

    public function getModeLibelleAttribute(): string
    {
        return match ($this->mode_paiement) {
            'especes' => 'Espèces',
            'virement' => 'Virement',
            'mobile_money' => 'Mobile Money',
            'cheque' => 'Chèque',
            default => $this->mode_paiement,
        };
    }

    public function getStatutLibelleAttribute(): string
    {
        return match ($this->statut) {
            'complet' => 'Complet',
            'partiel' => 'Partiel',
            'avance' => 'Avance',
            default => $this->statut,
        };
    }
}
