<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Commands\DiagnoseCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

test('command can be instantiated', function () {
    $command = new DiagnoseCommand;

    expect($command)->toBeInstanceOf(DiagnoseCommand::class);
});

test('command checks extension file exists', function () {
    $command = new DiagnoseCommand;

    $result = $command->checkExtensionFile();

    expect($result)->toBeBool();
});

test('command checks connection configuration', function () {
    $command = new DiagnoseCommand;

    $result = $command->checkConnection();

    expect($result)->toBeArray()
        ->toHaveKey('connection')
        ->toHaveKey('driver');
});

test('command validates configuration values', function () {
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
