<?php

namespace App\States;

use Thunk\Verbs\State;

class CampCoachState extends State
{
    public array $assignments = [];
    public array $original_assignments = [];
}
