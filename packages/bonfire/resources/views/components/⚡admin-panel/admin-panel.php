<?php

declare(strict_types=1);

use ArtisanBuild\Bonfire\Enums\BonfireRole;
use ArtisanBuild\Bonfire\Facades\Bonfire;
use Livewire\Component;

new class extends Component
{
    public function mount(): void
    {
        $member = Bonfire::memberFor(auth()->user());

        abort_unless(
            $member !== null && $member->is_active && $member->hasRoleAtLeast(BonfireRole::Admin),
            403,
        );
    }
};
