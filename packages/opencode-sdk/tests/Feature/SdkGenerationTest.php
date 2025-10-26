<?php

declare(strict_types=1);

test('OpenCode connector class exists', function () {
    expect(class_exists('ArtisanBuild\OpencodeSdk\OpenCode\OpenCode'))->toBeTrue();
});

test('OpenCode connector is autoloadable', function () {
    $connectorClass = 'ArtisanBuild\OpencodeSdk\OpenCode\OpenCode';

    expect(class_exists($connectorClass, true))->toBeTrue();
});

test('OpenCode connector file exists at expected path', function () {
    $path = __DIR__.'/../../src/OpenCode/OpenCode.php';

    expect(file_exists($path))->toBeTrue();
});

test('OpenCode directory structure exists', function () {
    $baseDir = __DIR__.'/../../src/OpenCode';

    expect(is_dir($baseDir))->toBeTrue();
});

test('Resource directory exists in OpenCode structure', function () {
    $resourceDir = __DIR__.'/../../src/OpenCode/Resource';

    expect(is_dir($resourceDir))->toBeTrue();
});

test('Requests directory exists in OpenCode structure', function () {
    $requestsDir = __DIR__.'/../../src/OpenCode/Requests';

    expect(is_dir($requestsDir))->toBeTrue();
});
