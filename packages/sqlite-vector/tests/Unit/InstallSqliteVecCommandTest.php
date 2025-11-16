<?php

declare(strict_types=1);

use ArtisanBuild\SqliteVector\Commands\InstallSqliteVecCommand;

test('command detects macOS Intel platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('Darwin', 'x86_64');

    expect($platform)->toBe('macos-x86_64');
});

test('command detects macOS ARM platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('Darwin', 'arm64');

    expect($platform)->toBe('macos-aarch64');
});

test('command detects Linux x86_64 platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('Linux', 'x86_64');

    expect($platform)->toBe('linux-x86_64');
});

test('command detects Linux ARM64 platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('Linux', 'aarch64');

    expect($platform)->toBe('linux-aarch64');
});

test('command detects Windows x86_64 platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('Windows', 'AMD64');

    expect($platform)->toBe('windows-x86_64');
});

test('command returns null for unsupported platform', function () {
    $command = new InstallSqliteVecCommand;

    $platform = $command->detectPlatform('BSD', 'x86_64');

    expect($platform)->toBeNull();
});

test('command gets correct extension filename for macOS', function () {
    $command = new InstallSqliteVecCommand;

    $filename = $command->getExtensionFilename('macos-x86_64');

    expect($filename)->toContain('vec0')->toContain('dylib');
});

test('command gets correct extension filename for Linux', function () {
    $command = new InstallSqliteVecCommand;

    $filename = $command->getExtensionFilename('linux-x86_64');

    expect($filename)->toContain('vec0')->toContain('so');
});

test('command gets correct extension filename for Windows', function () {
    $command = new InstallSqliteVecCommand;

    $filename = $command->getExtensionFilename('windows-x86_64');

    expect($filename)->toContain('vec0')->toContain('dll');
});
