<?php

namespace ArtisanBuild\Till\Traits;

trait IsPricingPlan
{
    public function getId(): string|int
    {
        return $this->id;
    }

    public function subscribeUrl(string $display): string
    {
        return route('till:subscribe', ['plan_id' => $this->id, 'period' => $display]);
    }
}
