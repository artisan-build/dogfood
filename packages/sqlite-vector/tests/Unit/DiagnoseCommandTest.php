<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Commands\DiagnoseCommand;

test('command can be instantiated', function (): void {
    $command = new DiagnoseCommand;

    expect($command)->toBeInstanceOf(DiagnoseCommand::class);
});

test('command checks extension file exists', function (): void {
    $command = new DiagnoseCommand;

    $result = $command->checkExtensionFile();

    expect($result)->toBeBool();
});

test('command checks connection configuration', function (): void {
    $command = new DiagnoseCommand;

    $result = $command->checkConnection();

    expect($result)->toBeArray()
        ->toHaveKey('connection')
        ->toHaveKey('driver');
});

test('command validates configuration values', function (): void {
    $command = new DiagnoseCommand;

    $result = $command->checkConfiguration();

    expect($result)->toBeArray()
        ->toHaveKey('connection')
        ->toHaveKey('extension_path')
        ->toHaveKey('default_dimensions')
        ->toHaveKey('table_name')
        ->toHaveKey('metadata_table_name')
        ->toHaveKey('distance_metric')
        ->toHaveKey('auto_load_extension');
});
