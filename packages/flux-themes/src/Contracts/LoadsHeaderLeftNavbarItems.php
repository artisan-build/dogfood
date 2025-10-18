<?php

declare(strict_types=1);

namespace ArtisanBuild\FluxThemes\Contracts;

interface LoadsHeaderLeftNavbarItems
{
    public function __invoke(): array;
}
