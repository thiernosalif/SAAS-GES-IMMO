<?php

namespace App\Livewire\Reclamations;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Reclamation;
use Livewire\Component;

class ReclamationTable extends Component
{
    use WithSearchAndPagination;

    public string $filterStatut = '';

    public function updatingFilterStatut(): void { $this->resetPage(); }

    public function render()
    {
        $query = Reclamation::with('locataire');

        $query = $this->applySearch($query, $this->search, ['motif', 'description']);

        if ($this->filterStatut !== '') {
            $query->where('statut', $this->filterStatut);
        }

        $reclamations = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.reclamations.reclamation-table', compact('reclamations'));
    }
}
