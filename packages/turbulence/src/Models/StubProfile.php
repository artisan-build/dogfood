<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @internal */
class StubProfile extends Model
{
    protected function stub(): BelongsTo
    {
        return $this->belongsTo(Stub::class);
    }
}
