<?php

declare(strict_types=1);

use Saloon\Generators\Generator;

return [
    // @phpstan-ignore class.notFound (Saloon generator config, not part of runtime code)
    Generator::make()
        ->name('Forge Client')
        ->input('openapi-spec.json')
        ->output('src/')
        ->namespace('ArtisanBuild\\ForgeClient')
        ->connectorName('ForgeConnector')
        ->generateDTOs()
        ->generateResources()
        ->build(),
];
