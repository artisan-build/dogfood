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
