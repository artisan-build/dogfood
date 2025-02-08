<?php

namespace App\States;

use Carbon\CarbonInterface;
use Thunk\Verbs\State;

class UserState extends State
{
    public string $email;

    public CarbonInterface $last_login;

    public ?string $two_factor_secret = null;

    public ?array $two_factor_recovery_codes = null;

    public ?CarbonInterface $two_factor_confirmed_at = null;
}
