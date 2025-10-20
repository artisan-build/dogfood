<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\FirewallRuleType;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('firewall rule type has all expected cases', function (): void {
    $cases = FirewallRuleType::cases();

    expect($cases)->toHaveCount(2)
        ->and(FirewallRuleType::ALLOW->value)->toBe('allow')
        ->and(FirewallRuleType::DENY->value)->toBe('deny');
});

test('firewall rule type validate succeeds for valid values', function (string $value): void {
    $type = FirewallRuleType::validate($value);

    expect($type)->toBeInstanceOf(FirewallRuleType::class)
        ->and($type->value)->toBe($value);
})->with([
    'allow',
    'deny',
]);

test('firewall rule type validate throws exception for invalid value', function (): void {
    expect(fn () => FirewallRuleType::validate('block'))
        ->toThrow(ValidationException::class, 'Invalid firewall rule type: block');
});

test('firewall rule type tryFrom returns null for invalid value', function (): void {
    $result = FirewallRuleType::tryFrom('block');

    expect($result)->toBeNull();
});
