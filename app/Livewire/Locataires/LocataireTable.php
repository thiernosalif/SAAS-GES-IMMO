<?php

namespace App\Livewire\Locataires;

use App\Livewire\Concerns\WithSearchAndPagination;
use App\Models\Locataire;
use Livewire\Component;

class LocataireTable extends Component
{
    use WithSearchAndPagination;

    public function render()
    {
        $query = Locataire::with('contratActif');
        $query = $this->applySearch($query, $this->search, ['nom', 'prenom', 'cin', 'telephone', 'mobileref']);

        $locataires = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.locataires.locataire-table', compact('locataires'));
    }

    public function delete(int $id): void
    {
        Locataire::findOrFail($id)->delete();
        session()->flash('success', 'Locataire supprimé.');
    }
}
