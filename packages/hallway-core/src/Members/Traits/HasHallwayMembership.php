<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\Hallway\Members\Models\Member;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasHallwayMembership
{
    public function hallway_members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
