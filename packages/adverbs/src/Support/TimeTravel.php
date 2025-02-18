<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Support;

use Carbon\Carbon;
use Closure;

class TimeTravel
{
    public static function to(Carbon|string $time): TimeTravel
    {
        Carbon::setTestNow($time);

        return new self;
    }

    public function then(Closure $closure): mixed
    {
        $return = $closure();
        Carbon::setTestNow();

        return $return;
    }
}
