<?php

namespace ArtisanBuild\Turbulence\Traits;

use ArtisanBuild\Turbulence\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAccounts
{
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function account(): BelongsTo
    {
        if ($this->current_account_id === null) {
            $this->switchAccount($this->accounts->first());
        }

        return $this->belongsTo(Account::class, 'current_team_id');
    }

    public function switchAccount(Account $account): bool
    {
        if (! $this->ownsAccount($account)) {
            return false;
        }

        $this->forceFill(['current_account_id' => $account->id])->save();

        $this->setRelation('current_account', $account);

        return true;
    }

    public function ownsAccount(Account $account)
    {
        return $this->accounts->contains(fn ($acc) => $acc->id === $account->id);
    }
}
