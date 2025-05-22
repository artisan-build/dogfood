<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** @internal */
class Stub extends OrganizationalUnit
{
    public function profile(): HasOne
    {
        return $this->hasOne(StubProfile::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('stub', function (Builder $builder) {
            $builder->where('group_type', 'stub');
        });
    }
}
