<?php

namespace App\Livewire\Paiements;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Paiement;
use Livewire\Component;

class PaiementTable extends Component
{
    use WithSearchAndPagination;

    public string $filterMode   = '';
    public string $filterStatut = '';

    public function updatingFilterMode(): void   { $this->resetPage(); }
    public function updatingFilterStatut(): void { $this->resetPage(); }

    public function render()
    {
        $query = Paiement::with(['contrat.locataire', 'contrat.bien', 'recu']);

        if (!empty(trim($this->search))) {
            $term = $this->search;
            $query->where(fn($q) => $q
                ->where('transaction_reference', 'LIKE', "%{$term}%")
                ->orWhereHas('contrat.locataire', fn($q2) => $q2
                    ->where('nom', 'LIKE', "%{$term}%")
                    ->orWhere('prenom', 'LIKE', "%{$term}%")
                )
            );
        }

        if ($this->filterMode !== '') {
            $query->where('mode_paiement', $this->filterMode);
        }

        if ($this->filterStatut !== '') {
            $query->where('statut', $this->filterStatut);
        }

        $paiements = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.paiements.paiement-table', compact('paiements'));
    }
}
