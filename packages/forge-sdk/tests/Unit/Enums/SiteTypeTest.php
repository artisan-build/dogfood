<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\SiteType;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('site type has all expected cases', function (): void {
    $cases = SiteType::cases();

    expect($cases)->toHaveCount(11)
        ->and(SiteType::LARAVEL->value)->toBe('laravel')
        ->and(SiteType::SYMFONY->value)->toBe('symfony')
        ->and(SiteType::STATAMIC->value)->toBe('statamic')
        ->and(SiteType::WORDPRESS->value)->toBe('wordpress')
        ->and(SiteType::PHPMYADMIN->value)->toBe('phpmyadmin')
        ->and(SiteType::PHP->value)->toBe('php')
        ->and(SiteType::NEXT_JS->value)->toBe('next.js')
        ->and(SiteType::NUXT_JS->value)->toBe('nuxt.js')
        ->and(SiteType::STATIC_HTML->value)->toBe('static-html')
        ->and(SiteType::OTHER->value)->toBe('other')
        ->and(SiteType::CUSTOM->value)->toBe('custom');
});

test('site type validate succeeds for valid values', function (string $value): void {
    $type = SiteType::validate($value);

    expect($type)->toBeInstanceOf(SiteType::class)
        ->and($type->value)->toBe($value);
})->with([
    'laravel',
    'symfony',
    'statamic',
    'wordpress',
    'phpmyadmin',
    'php',
    'next.js',
    'nuxt.js',
    'static-html',
    'other',
    'custom',
]);

test('site type validate throws exception for invalid value', function (): void {
    expect(fn () => SiteType::validate('nodejs'))
        ->toThrow(ValidationException::class, 'Invalid site type: nodejs');
});

test('site type tryFrom returns null for invalid value', function (): void {
    $result = SiteType::tryFrom('nodejs');

    expect($result)->toBeNull();
});
