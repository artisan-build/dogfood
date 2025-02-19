<?php

namespace ArtisanBuild\Till\Actions;

use Illuminate\Support\Facades\Cache;

class ClearAbilitiesCache
{
    public function __invoke(): void
    {
        $keys = Cache::get('abilities_keys', []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
