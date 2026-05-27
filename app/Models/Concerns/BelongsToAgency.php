<?php

namespace App\Models\Concerns;

use App\Models\Agency;
use App\Models\Scopes\AgencyScope;

trait BelongsToAgency
{
    public static function bootBelongsToAgency(): void
    {
        static::addGlobalScope(new AgencyScope());
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
