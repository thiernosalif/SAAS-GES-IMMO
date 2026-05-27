<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AgencyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $agency = app('current_agency');

        if ($agency) {
            $builder->where($model->getTable() . '.agency_id', $agency->id);
        }
    }
}
