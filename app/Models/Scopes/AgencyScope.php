<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AgencyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Not bound = super admin or CLI context — no tenant filter
        if (!app()->bound('current_agency')) {
            return;
        }

        $agency = app('current_agency');

        if ($agency) {
            $builder->where($model->getTable() . '.agency_id', $agency->id);
        }
    }
}
