<?php

namespace App\Livewire\Situations;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Situation;
use Livewire\Component;

class SituationTable extends Component
{
    use WithSearchAndPagination;

    public string $filterStatut = '';
    public string $filterType   = '';

    public function updatingFilterStatut(): void { $this->resetPage(); }
    public function updatingFilterType(): void   { $this->resetPage(); }

    public function render()
    {
        $query = Situation::with(['proprietaire']);

        if (!empty(trim($this->search))) {
            $term = $this->search;
            $query->whereHas('proprietaire', fn($q) => $q
                ->where('nom', 'LIKE', "%{$term}%")
                ->orWhere('prenom', 'LIKE', "%{$term}%")
            );
        }

        if ($this->filterStatut !== '') {
            $query->where('statut', $this->filterStatut);
        }

        if ($this->filterType !== '') {
            $query->where('type', $this->filterType);
        }

        $situations = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.situations.situation-table', compact('situations'));
    }
}
