<?php

namespace ArtisanBuild\Till\Traits;

use Illuminate\Support\Facades\Cache;

trait HasAbilitiesBasedOnSubscription
{
    public function getPlan() {}

    public function ableTo(string $ability)
    {
        if (! array_key_exists($ability, $this->abilities())) {
            return false;
        }

        return data_get($this->abilities(), $ability);
    }

    public function abilitiesKey(): string
    {
        return 'subscription-abilities-'.$this->id;
    }

    public function abilities(): array
    {
        return Cache::rememberForever($this->abilitiesKey(), fn () => []);
    }

    public function resetAbilities(): array
    {
        Cache::forget($this->abilitiesKey());

        return $this->abilities();
    }
}
