<?php

namespace ArtisanBuild\Turbulence\Models;

use ArtisanBuild\Turbulence\Traits\HasAccounts;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

/**
 * @internal
 * @property-read int $current_account_id
 * @property-read Collection $accounts
 * @property-read Account $account
 */
class UserModel extends User
{
    use HasAccounts;
}
