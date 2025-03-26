<?php

namespace App\Events;

use App\States\CampCoachState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CoachAssignmentsHandedOffToAnotherCoach extends Event
{
    #[StateId(CampCoachState::class)]
    public int $original_coach_id;

    #[StateId(CampCoachState::class)]
    public int $new_coach_id;

    public function apply(CampCoachState $original_coach, CampCoachState $new_coach)
    {
        $new_coach->assignments = $original_coach->assignments;
        $original_coach->original_assignments = $original_coach->assignments;
        $original_coach->assignments = [];
    }

    public function handle()
    {
        // I am the beast. Feed me rappers or feed me beats. - Lil Wayne
    }
}
