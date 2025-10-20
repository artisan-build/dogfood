<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\IntegrationType;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('integration type has all expected cases', function (): void {
    $cases = IntegrationType::cases();

    expect($cases)->toHaveCount(4)
        ->and(IntegrationType::GITHUB->value)->toBe('github')
        ->and(IntegrationType::GITLAB->value)->toBe('gitlab')
        ->and(IntegrationType::BITBUCKET->value)->toBe('bitbucket')
        ->and(IntegrationType::CUSTOM->value)->toBe('custom');
});

test('integration type validate succeeds for valid values', function (string $value): void {
    $type = IntegrationType::validate($value);

    expect($type)->toBeInstanceOf(IntegrationType::class)
        ->and($type->value)->toBe($value);
})->with([
    'github',
    'gitlab',
    'bitbucket',
    'custom',
]);

test('integration type validate throws exception for invalid value', function (): void {
    expect(fn () => IntegrationType::validate('gitea'))
        ->toThrow(ValidationException::class, 'Invalid integration type: gitea');
});

test('integration type tryFrom returns null for invalid value', function (): void {
    $result = IntegrationType::tryFrom('gitea');

    expect($result)->toBeNull();
});
