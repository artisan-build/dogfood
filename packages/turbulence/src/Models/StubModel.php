<?php

namespace ArtisanBuild\Turbulence\Models;

use ArtisanBuild\Turbulence\Traits\HasStubs;
use Illuminate\Database\Eloquent\Model;

/**
 * @internal
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \ArtisanBuild\Turbulence\Models\Stub> $stubs
 * @property-read int|null $stubs_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel query()
 *
 * @mixin \Eloquent
 */
class StubModel extends Model
{
    use HasStubs;
}
