<?php

namespace ArtisanBuild\Turbulence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @internal */
class AccountProfile extends Model
{
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
