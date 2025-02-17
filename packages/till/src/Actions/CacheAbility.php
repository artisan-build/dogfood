<?php

namespace ArtisanBuild\Till\Actions;

use Illuminate\Support\Facades\Cache;

class CacheAbility
{
    public function __invoke(string $key, bool $value): bool
    {
        $keys = Cache::get('abilities_keys', []);
        $keys[] = $key;
        Cache::rememberForever('ability_keys', fn () => array_unique($keys));

        Cache::rememberForever($key, fn () => $value);

        return $value;
    }
}
