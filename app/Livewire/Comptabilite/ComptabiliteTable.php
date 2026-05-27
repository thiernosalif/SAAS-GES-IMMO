<?php

namespace App\Livewire\Comptabilite;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Comptabilite;
use Livewire\Component;

class ComptabiliteTable extends Component
{
    use WithSearchAndPagination;

    public string $filterType = '';

    public function updatingFilterType(): void { $this->resetPage(); }

    public function render()
    {
        $query = Comptabilite::query();

        $query = $this->applySearch($query, $this->search, ['motif', 'categorie']);

        if ($this->filterType !== '') {
            $query->where('type', $this->filterType);
        }

        $mouvements = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.comptabilite.comptabilite-table', compact('mouvements'));
    }
}
