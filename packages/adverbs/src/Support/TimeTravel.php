<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Support;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Date;

class TimeTravel
{
    public static function to(Carbon|string $time): TimeTravel
    {
        Date::setTestNow($time);

        return new self;
    }

    public function then(Closure $closure): mixed
    {
        $return = $closure();
        Date::setTestNow();

        return $return;
    }
}
