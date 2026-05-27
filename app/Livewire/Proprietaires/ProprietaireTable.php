<?php

namespace App\Livewire\Proprietaires;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Proprietaire;
use Livewire\Component;

class ProprietaireTable extends Component
{
    use WithSearchAndPagination;

    public function render()
    {
        $query = Proprietaire::withCount('biens');
        $query = $this->applySearch($query, $this->search, ['nom', 'prenom', 'cin', 'telephone']);
        $proprietaires = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.proprietaires.proprietaire-table', compact('proprietaires'));
    }

    public function delete(int $id): void
    {
        $proprietaire = Proprietaire::findOrFail($id);
        $proprietaire->delete();
        session()->flash('success', 'Propriétaire supprimé.');
    }
}
