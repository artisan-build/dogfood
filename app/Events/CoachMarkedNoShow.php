<?php

namespace App\Events;

use App\States\CampCoachState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CoachMarkedNoShow extends Event
{
    #[StateId(CampCoachState::class)]
    public int $camp_coach_id;

    public array $assignments = [];

    public function apply(CampCoachState $state)
    {
        $state->assignments = $this->assignments;
    }

    public function handle()
    {
        // Update the coach record so that they are a no-show
    }
}
