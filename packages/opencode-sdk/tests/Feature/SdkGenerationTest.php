<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;

test('OpenCode connector class exists', function (): void {
    expect(class_exists(OpenCode::class))->toBeTrue();
});

test('OpenCode connector is autoloadable', function (): void {
    $connectorClass = OpenCode::class;

    expect(class_exists($connectorClass, true))->toBeTrue();
});

test('OpenCode connector file exists at expected path', function (): void {
    $path = __DIR__.'/../../src/OpenCode/OpenCode.php';

    expect(file_exists($path))->toBeTrue();
});

test('OpenCode directory structure exists', function (): void {
    $baseDir = __DIR__.'/../../src/OpenCode';

    expect(is_dir($baseDir))->toBeTrue();
});

test('Resource directory exists in OpenCode structure', function (): void {
    $resourceDir = __DIR__.'/../../src/OpenCode/Resource';

    expect(is_dir($resourceDir))->toBeTrue();
});

test('Requests directory exists in OpenCode structure', function (): void {
    $requestsDir = __DIR__.'/../../src/OpenCode/Requests';

    expect(is_dir($requestsDir))->toBeTrue();
});
