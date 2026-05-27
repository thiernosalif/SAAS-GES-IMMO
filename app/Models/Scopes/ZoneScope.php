<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ZoneScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if ($user && in_array($user->role, ['gestionnaire', 'readonly']) && $user->zone_id) {
            $table = $model->getTable();
            if (\Schema::hasColumn($table, 'zone_id')) {
                $builder->where($table . '.zone_id', $user->zone_id);
            }
        }
    }
}
