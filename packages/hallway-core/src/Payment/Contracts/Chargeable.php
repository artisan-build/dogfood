<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Contracts;

interface Chargeable
{
    public function paymentState();
}
