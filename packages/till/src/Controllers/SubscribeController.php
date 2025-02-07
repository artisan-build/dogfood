<?php

namespace ArtisanBuild\Till\Controllers;

use ArtisanBuild\Till\Actions\GetPlanById;
use Illuminate\Http\Request;

class SubscribeController
{
    public function __invoke(Request $request, string|int $plan_id): never
    {
        $plan = app(GetPlanById::class)($plan_id);

        dd($plan);
    }
}
