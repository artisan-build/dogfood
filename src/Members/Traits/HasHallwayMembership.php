<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Traits;

use ArtisanBuild\Hallway\Members\Models\Member;

trait HasHallwayMembership
{
    public function hallway_members()
    {
        return $this->hasMany(Member::class);
    }
}
