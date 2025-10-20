<?php

declare(strict_types=1);

use Saloon\Generators\Generator;

return [
    Generator::make()
        ->name('Forge SDK')
        ->input('openapi-spec.json')
        ->output('src/')
        ->namespace('ArtisanBuild\\ForgeSdk')
        ->connectorName('ForgeConnector')
        ->generateDTOs()
        ->generateResources()
        ->build(),
];
