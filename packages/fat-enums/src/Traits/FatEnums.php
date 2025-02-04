<?php

declare(strict_types=1);

namespace ArtisanBuild\FatEnums\Traits;

trait FatEnums
{
    public static function random(int $num = 1): static|array
    {
        assert($num > 0);

        return 1 === $num
            ? collect(static::cases())->shuffle()->first()
            : collect(static::cases())->shuffle()->take($num)->toArray();
    }
}
