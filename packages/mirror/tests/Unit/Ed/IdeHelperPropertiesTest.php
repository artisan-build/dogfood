<?php

declare(strict_types=1);

use ArtisanBuild\Mirror\Facades\Mirror;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\ClassAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\PropertyAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\ExtendsAbstractClass;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\HasClassAttribute;

describe('the docblocked properties return the right stuff', function (): void {
    it('works with reflection_class', function (): void {
        expect(Mirror::reflect(ExtendsAbstractClass::class)->reflection_class)->toBeInstanceOf(ReflectionClass::class);
    });
    it('works with reflection_property', function (): void {
        expect(Mirror::reflect(ExtendsAbstractClass::class)->property('protected_string')->reflection_property)->toBeInstanceOf(ReflectionProperty::class);
    });
    it('works with reflection_attribute', function (): void {
        expect(Mirror::reflect(HasClassAttribute::class)->attribute(ClassAttribute::class)->reflection_attribute)->toBeInstanceOf(ReflectionAttribute::class)
            ->and(Mirror::reflect(HasClassAttribute::class)->property('has_attribute')->attribute(PropertyAttribute::class)->reflection_attribute)->toBeInstanceOf(ReflectionAttribute::class);
    });
    it('works with reflection_method', function (): void {
        expect(Mirror::reflect(ExtendsAbstractClass::class)->method('public_method')->reflection_method)->toBeInstanceOf(ReflectionMethod::class);
    });
});
