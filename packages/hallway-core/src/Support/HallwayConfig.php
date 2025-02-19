<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Support;

class HallwayConfig
{
    public function get(string $key, ?string $ui = null)
    {
        return config(implode('.', [$ui ?? 'hallway', $key]));
    }
}
