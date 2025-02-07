<?php

use ArtisanBuild\Till\Actions\GetDefaultPlan;
use ArtisanBuild\Till\Plans\HobbyistPlan;
use ArtisanBuild\Till\Plans\SoloPlan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MultipleItemsFoundException;

afterEach(function (): void {
    File::delete(implode('/', [config('till.plan_path'), 'ExtraDefaultPlan.php']));
});

it('gets the default plan', function (): void {
    expect(app(GetDefaultPlan::class)())->toBeInstanceOf(SoloPlan::class);
});

it('gets the default individual plan', function (): void {
    Config::set('till.team_mode', false);
    expect(app(GetDefaultPlan::class)())->toBeInstanceOf(HobbyistPlan::class);
});

it('throws if there is more than one plan marked as default', function (): void {
    File::put(implode('/', [config('till.plan_path'), 'ExtraDefaultPlan.php']), File::get(__DIR__.'/files/ExtraDefaultPlan.php.stub'));
    app(GetDefaultPlan::class)();
})->throws(MultipleItemsFoundException::class);
