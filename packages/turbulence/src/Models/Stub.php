<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @internal
 *
 * @property-read \ArtisanBuild\Turbulence\Models\StubProfile|null $profile
 *
 * @method static Builder<static>|Stub newModelQuery()
 * @method static Builder<static>|Stub newQuery()
 * @method static Builder<static>|Stub query()
 *
 * @mixin \Eloquent
 */
class Stub extends OrganizationalUnit
{
    public function profile(): HasOne
    {
        return $this->hasOne(StubProfile::class);
    }

    #[\Override]
    protected static function booted(): void
    {
        static::addGlobalScope('stub', function (Builder $builder): void {
            $builder->where('group_type', 'stub');
        });
    }
}
