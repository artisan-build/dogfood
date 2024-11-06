<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire\Layout;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\MemberTimezoneUpdated;
use Flux\Flux;
use Illuminate\Support\Facades\Context;
use Livewire\Component;

class DetectMemberTimezone extends Component
{
    public ?string $timezone = null;

    public function mount(): void
    {
        $this->timezone = Context::get('active_member')?->timezone;
    }
    public function setTimezone(string $timezone): void
    {
        if ($timezone !== $this->timezone) {
            MemberTimezoneUpdated::fire(
                member_id: Context::get('active_member')->id,
                timezone: $timezone,
            );
            if (MemberRoles::Guest !== Context::get('active_member')->role) {
                Flux::toast("We have updated your time zone to {$timezone}", 'Time Zone Updated');
            }
        }
    }

    public function render()
    {
        return view('hallway-flux::livewire.layout.detect-member-timezone');
    }

}
