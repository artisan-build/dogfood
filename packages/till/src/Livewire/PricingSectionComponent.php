<?php

namespace ArtisanBuild\Till\Livewire;

use App\View\Components\AppLayout;
use ArtisanBuild\Till\Actions\GetVisiblePlans;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(AppLayout::class)]
class PricingSectionComponent extends Component
{
    public string $display = '';

    public function mount()
    {
        $this->display = config('till.default_display');
    }

    public function render()
    {
        $plans = app(GetVisiblePlans::class)();

        $displays = [
            'month' => $plans->some(fn ($plan) => $plan->prices['month'] !== null),
            'year' => $plans->some(fn ($plan) => $plan->prices['year'] !== null),
            'life' => $plans->some(fn ($plan) => $plan->prices['life'] !== null),
        ];

        return view(config('till.pricing_section_template'))
            ->with('plans', $plans)
            ->with('displays', $displays);
    }
}
