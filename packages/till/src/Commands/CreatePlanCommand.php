<?php

namespace ArtisanBuild\Till\Commands;

use ArtisanBuild\Till\Actions\GetPlanNamespace;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class CreatePlanCommand extends Command
{
    protected $signature = 'till:create-plan
        {name?}
        {heading?}
        {subheading?}
        {week?}
        {month?}
        {year?}
        {life?}
    ';

    protected $description = 'Create a new pricing plan for your SaaS';

    // TODO: Figure out why this has to be __invoke() instead of handle.
    public function __invoke(): int
    {
        $replace = [];

        $replace['namespace'] = app(GetPlanNamespace::class)();

        $replace['imports'] = collect([
            'use ArtisanBuild\Till\Attributes\IndividualPlan;' => ! config('till.team_mode'),
            'use ArtisanBuild\Till\Attributes\TeamPlan;' => config('till.team_mode'),
        ])->filter()->keys()->implode("\n");

        $replace['attributes'] = collect([
            '#[IndividualPlan]' => ! config('till.team_mode'),
            '#[TeamPlan]' => config('till.team_mode'),
        ])->filter()->keys()->implode("\n");

        $replace['name'] = Str::of($this->argument('name') ?? text(
            label: 'Classname for this plan',
            placeholder: 'MyNewPlan',
            required: true,
            validate: [
                'string',
                'alpha',
                'ends_with:Plan',
            ],
            hint: 'This file will be created in '.config('till.plan_path'),
        ))->studly();

        $replace['heading'] = $this->argument('heading') ?? text(
            label: 'Heading',
            default: Str::of($replace['name'])->replaceLast('Plan', '')->headline(),
            required: true,
            validate: [
                'string',
                // TODO: Set reasonable min / max values
            ],
            hint: 'The name of this plan, used as the heading on the pricing and plans page',
        );

        $replace['subheading'] = $this->argument('subheading') ?? text(
            label: 'Sub-Heading',
            required: true,
            validate: [
                'string',
                // TODO: Set reasonable min / max values
            ],
            hint: 'A tagline for this plan, used as the subheading in the pricing and plans page'
        );

        $plan_file = config('till.plan_path').'/'.$replace['name'].'.php';

        foreach (['week', 'month', 'year', 'life'] as $key) {
            $replace[$key] = $this->argument($key) ?? text(
                label: 'Price per '.strtoupper($key),
                validate: [
                    'numeric',
                ],
            );
        }

        File::ensureDirectoryExists(dirname($plan_file));

        $plan = File::get(__DIR__.'/../../stubs/SubscriptionPlan.stub');

        foreach ($replace as $key => $value) {
            if (blank($value)) {
                $value = 'null';
            }
            $plan = str_replace('{'.$key.'}', $value, $plan);
        }

        File::put($plan_file, $plan);
        $this->info("Created {$plan_file}");

        return self::SUCCESS;
    }
}
