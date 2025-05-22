<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @internal
 *
 * @property-read \ArtisanBuild\Turbulence\Models\UserModel|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 *
 * @mixin \Eloquent
 */
class Account extends Model
{
    // There are no columns in the accounts table except for those that are required to
    // associate the account with the user. Please do not add any. Use the AccountProfile
    // model and account_profiles table to build out your account information.

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('turbulence.user_model'));
    }
}
