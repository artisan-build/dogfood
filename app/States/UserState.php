<?php

namespace App\States;

use Carbon\CarbonInterface;
use Thunk\Verbs\State;

class UserState extends State
{
    public string $name;

    public string $email;

    public CarbonInterface $last_login;

    public ?CarbonInterface $two_factor_confirmed_at = null;

    public ?int $current_team_id = null;
}
