<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

trait WithSearchAndPagination
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public int $perPage = 25;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applySearch(Builder $query, string $search, array $fields): Builder
    {
        $terms = collect(explode(' ', trim($search)))->filter();

        if ($terms->isEmpty()) {
            return $query;
        }

        return $query->where(function ($q) use ($terms, $fields) {
            foreach ($terms as $term) {
                $q->where(function ($q2) use ($term, $fields) {
                    foreach ($fields as $i => $field) {
                        if (str_contains($field, '.')) {
                            [$relation, $col] = explode('.', $field, 2);
                            $method = $i === 0 ? 'whereHas' : 'orWhereHas';
                            $q2->{$method}($relation, fn ($r) => $r->where($col, 'LIKE', "%{$term}%"));
                        } else {
                            $method = $i === 0 ? 'where' : 'orWhere';
                            $q2->{$method}($field, 'LIKE', "%{$term}%");
                        }
                    }
                });
            }
        });
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }
}
