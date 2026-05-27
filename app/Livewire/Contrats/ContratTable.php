<?php

namespace App\Livewire\Contrats;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Contrat;
use Livewire\Component;

class ContratTable extends Component
{
    use WithSearchAndPagination;

    public string $filterStatut = '';

    public function updatingFilterStatut(): void { $this->resetPage(); }

    public function render()
    {
        $query = Contrat::with(['locataire', 'bien']);

        // Recherche sur locataire nom/prenom
        if (!empty(trim($this->search))) {
            $term = $this->search;
            $query->whereHas('locataire', fn($q) => $q
                ->where('nom', 'LIKE', "%{$term}%")
                ->orWhere('prenom', 'LIKE', "%{$term}%")
            );
        }

        if ($this->filterStatut !== '') {
            $query->where('statut', $this->filterStatut);
        }

        $contrats = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.contrats.contrat-table', compact('contrats'));
    }
}
