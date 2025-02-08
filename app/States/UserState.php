<?php

namespace App\States;

use Carbon\CarbonInterface;
use Thunk\Verbs\State;

class UserState extends State
{
    public string $email;

    public CarbonInterface $last_login;
}
