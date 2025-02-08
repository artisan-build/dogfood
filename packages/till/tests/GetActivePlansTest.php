<?php

use ArtisanBuild\Till\Actions\GetActivePlans;
use Illuminate\Support\Collection;

it('gets all of the tests', function (): void {
    expect(app(GetActivePlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(3);
});
