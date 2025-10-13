<?php

declare(strict_types=1);

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

/**
 * @internal
 *
 * @property-read StubProfile|null $profile
 *
 * @method static Builder<static>|Stub newModelQuery()
 * @method static Builder<static>|Stub newQuery()
 * @method static Builder<static>|Stub query()
 *
 * @mixin \Eloquent
 * @mixin IdeHelperStub
 */
class Stub extends OrganizationalUnit
{
    #[Override]
    protected static function booted(): void
    {
        static::addGlobalScope('stub', function (Builder $builder): void {
            $builder->where('group_type', 'stub');
        });
    }

    public function profile(): HasOne
    {
        return $this->hasOne(StubProfile::class);
    }
}
