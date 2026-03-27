<?php

declare(strict_types=1);

use ArtisanBuild\FatEnums\Collections\EnumCollection;
use ArtisanBuild\FatEnums\Tests\Fixtures\CollectibleStringEnum;

test('it can be constructed with an empty array', function (): void {
    $collection = new EnumCollection([]);

    expect($collection)->toBeEmpty()
        ->and($collection)->toBeInstanceOf(EnumCollection::class);
});

test('filter returning empty result does not crash', function (): void {
    $collection = new EnumCollection(CollectibleStringEnum::class);

    $filtered = $collection->filter(fn ($case) => false);

    expect($filtered)->toBeEmpty();
});

test('reject returning empty result does not crash', function (): void {
    $collection = new EnumCollection(CollectibleStringEnum::class);

    $rejected = $collection->reject(fn ($case) => true);

    expect($rejected)->toBeEmpty();
});
