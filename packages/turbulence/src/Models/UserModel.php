<?php

namespace ArtisanBuild\Turbulence\Models;

use ArtisanBuild\Turbulence\Traits\HasAccounts;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

/**
 * @internal
 *
 * @property-read int $current_account_id
 * @property-read Collection $accounts
 * @property-read Account $account
 * @property-read int|null $accounts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel query()
 *
 * @mixin \Eloquent
 */
class UserModel extends User
{
    use HasAccounts;
}
