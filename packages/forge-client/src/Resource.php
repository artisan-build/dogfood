<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient;

use Saloon\Http\Connector;

class Resource
{
    public function __construct(
        protected Connector $connector,
    ) {}
}
