<?php

declare(strict_types=1);

use ArtisanBuild\Mirror\Facades\Mirror;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\ClassAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\ExtendsAbstractClass;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\HasClassAttribute;

test('The reflected property is ReflectionClass at first', function (): void {
    expect(Mirror::reflect(ExtendsAbstractClass::class)->reflection)->toBeInstanceOf(ReflectionClass::class);
});

test('The reflected property is ReflectionProperty when getProperty is used', function (): void {
    expect(Mirror::reflect(ExtendsAbstractClass::class)->property('public_string')->reflection)->toBeInstanceOf(ReflectionProperty::class);
});

it('throws if the requested property does not exist', function (): void {
    expect(Mirror::reflect(ExtendsAbstractClass::class)->property('not_here')->reflection);
})->throws(Exception::class);

test('The reflected property is ReflectionAttribute when getAttribute is used', function (): void {
    expect(Mirror::reflect(HasClassAttribute::class)->attribute(ClassAttribute::class)->reflection)->toBeInstanceOf(ReflectionAttribute::class);
});

it('throws if the requested attribute does not exist', function (): void {
    Mirror::reflect(HasClassAttribute::class)->attribute('NotHere')->reflection;
})->throws(Exception::class);
