<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Support;

use Carbon\Carbon;
use Closure;

class TimeTravel
{
    public static function to(Carbon|string $time): TimeTravel
    {
        \Illuminate\Support\Facades\Date::setTestNow($time);

        return new self;
    }

    public function then(Closure $closure): mixed
    {
        $return = $closure();
        \Illuminate\Support\Facades\Date::setTestNow();

        return $return;
    }
}
