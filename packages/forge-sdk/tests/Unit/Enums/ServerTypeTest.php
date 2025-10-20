<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\ServerType;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('server type has all expected cases', function (): void {
    $cases = ServerType::cases();

    expect($cases)->toHaveCount(7)
        ->and(ServerType::APP->value)->toBe('app')
        ->and(ServerType::WEB->value)->toBe('web')
        ->and(ServerType::LOADBALANCER->value)->toBe('loadbalancer')
        ->and(ServerType::DATABASE->value)->toBe('database')
        ->and(ServerType::CACHE->value)->toBe('cache')
        ->and(ServerType::WORKER->value)->toBe('worker')
        ->and(ServerType::MEILISEARCH->value)->toBe('meilisearch');
});

test('server type validate succeeds for valid values', function (string $value): void {
    $type = ServerType::validate($value);

    expect($type)->toBeInstanceOf(ServerType::class)
        ->and($type->value)->toBe($value);
})->with([
    'app',
    'web',
    'loadbalancer',
    'database',
    'cache',
    'worker',
    'meilisearch',
]);

test('server type validate throws exception for invalid value', function (): void {
    expect(fn () => ServerType::validate('invalid'))
        ->toThrow(ValidationException::class, 'Invalid server type: invalid');
});

test('server type tryFrom returns null for invalid value', function (): void {
    $result = ServerType::tryFrom('invalid');

    expect($result)->toBeNull();
});

test('server type label returns human-readable names', function (): void {
    expect(ServerType::APP->label())->toBe('Application Server')
        ->and(ServerType::WEB->label())->toBe('Web Server')
        ->and(ServerType::LOADBALANCER->label())->toBe('Load Balancer')
        ->and(ServerType::DATABASE->label())->toBe('Database Server')
        ->and(ServerType::CACHE->label())->toBe('Cache Server')
        ->and(ServerType::WORKER->label())->toBe('Worker Server')
        ->and(ServerType::MEILISEARCH->label())->toBe('Meilisearch Server');
});

test('server type description returns detailed information', function (): void {
    expect(ServerType::APP->description())->toContain('application server')
        ->and(ServerType::WEB->description())->toContain('Web server')
        ->and(ServerType::DATABASE->description())->toContain('database server');
});

test('server type includesPhp returns correct values', function (): void {
    expect(ServerType::APP->includesPhp())->toBeTrue()
        ->and(ServerType::WORKER->includesPhp())->toBeTrue()
        ->and(ServerType::WEB->includesPhp())->toBeFalse()
        ->and(ServerType::DATABASE->includesPhp())->toBeFalse()
        ->and(ServerType::CACHE->includesPhp())->toBeFalse();
});

test('server type includesDatabase returns correct values', function (): void {
    expect(ServerType::APP->includesDatabase())->toBeTrue()
        ->and(ServerType::DATABASE->includesDatabase())->toBeTrue()
        ->and(ServerType::WEB->includesDatabase())->toBeFalse()
        ->and(ServerType::WORKER->includesDatabase())->toBeFalse()
        ->and(ServerType::CACHE->includesDatabase())->toBeFalse();
});

test('server type canHostSites returns correct values', function (): void {
    expect(ServerType::APP->canHostSites())->toBeTrue()
        ->and(ServerType::WEB->canHostSites())->toBeTrue()
        ->and(ServerType::DATABASE->canHostSites())->toBeFalse()
        ->and(ServerType::WORKER->canHostSites())->toBeFalse()
        ->and(ServerType::CACHE->canHostSites())->toBeFalse()
        ->and(ServerType::LOADBALANCER->canHostSites())->toBeFalse();
});

test('server type values returns array of all server type values', function (): void {
    $values = ServerType::values();

    expect($values)->toBeArray()
        ->toHaveCount(7)
        ->toContain('app', 'web', 'loadbalancer', 'database', 'cache', 'worker', 'meilisearch');
});
