<?php

namespace App\Livewire\Biens;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Bien;
use Livewire\Component;

class BienTable extends Component
{
    use WithSearchAndPagination;

    public string $filterType  = '';
    public string $filterDispo = '';

    public function render()
    {
        $query = Bien::with(['proprietaire', 'contratActif']);

        $query = $this->applySearch($query, $this->search, ['description', 'adresse', 'quartier']);

        if ($this->filterType !== '') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterDispo === 'libre') {
            $query->whereDoesntHave('contratActif');
        } elseif ($this->filterDispo === 'occupe') {
            $query->whereHas('contratActif');
        }

        $biens = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.biens.bien-table', compact('biens'));
    }

    public function updatingFilterType(): void  { $this->resetPage(); }
    public function updatingFilterDispo(): void { $this->resetPage(); }
}
