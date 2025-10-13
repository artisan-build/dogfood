<?php

declare(strict_types=1);

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @internal
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile query()
 *
 * @mixin \Eloquent
 * @mixin IdeHelperStubProfile
 */
class StubProfile extends Model
{
    protected function stub(): BelongsTo
    {
        return $this->belongsTo(Stub::class);
    }
}
