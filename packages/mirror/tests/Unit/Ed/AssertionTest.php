<?php

declare(strict_types=1);

use ArtisanBuild\Mirror\Facades\Mirror;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\ClassAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\Attributes\PropertyAttribute;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\ExtendsAbstractClass;
use ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures\HasClassAttribute;

describe('it returns true when relect() is chained on', function (): void {
    it('works for classes', fn () => expect(Mirror::reflect(ExtendsAbstractClass::class)->assert())->toBeTrue());
    it('works for properties', fn () => expect(Mirror::reflect(ExtendsAbstractClass::class)->property('public_string')->assert())->toBeTrue());
    it('works for class attributes', fn () => expect(Mirror::reflect(HasClassAttribute::class)->attribute(ClassAttribute::class)->assert())->toBeTrue());
    it('works for property attributes', fn () => expect(Mirror::reflect(HasClassAttribute::class)->property('has_attribute')->attribute(PropertyAttribute::class)->assert())->toBeTrue());
});
