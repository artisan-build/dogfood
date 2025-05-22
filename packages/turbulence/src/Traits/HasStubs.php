<?php

namespace ArtisanBuild\Turbulence\Traits;

use ArtisanBuild\Turbulence\Models\Stub;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasStubs
{
    public function stubs(): HasMany
    {
        return $this->hasMany(Stub::class);
    }
}
