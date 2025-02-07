<?php

namespace ArtisanBuild\Till\Traits;

trait IsPricingPlan
{
    public function subscribeUrl(string $display): string
    {
        return route('till:subscribe', ['plan_id' => $this->id, 'period' => $display]);
    }
}
