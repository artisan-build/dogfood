<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @internal
 *
 * @property-read \ArtisanBuild\Turbulence\Models\Account|null $account
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile query()
 *
 * @mixin \Eloquent
 */
class AccountProfile extends Model
{
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
