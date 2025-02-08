<?php

namespace ArtisanBuild\Till\Controllers;

use ArtisanBuild\Till\Actions\GetPlanById;
use Illuminate\Http\Request;

class SubscribeController
{
    public function __invoke(Request $request, string|int $plan_id)
    {
        $plan = app(GetPlanById::class)($plan_id);

        return 'TODO: Come up with a good way to send the user to the correct url by provider';
    }
}
