<?php

declare(strict_types=1);

namespace ArtisanBuild\FluxThemes\Contracts;

interface LoadsHeaderRightNavbarItems
{
    public function __invoke(): array;
}
