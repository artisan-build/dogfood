<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\States;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use Thunk\Verbs\State;

class MemberState extends State
{
    public int|string $user_id;

    public string $name = '';
    public string $handle = '';
    public string $display_name = '';

    public MemberRoles $role;



    // Member notification preferences
    public array $notify_channel_ids = [];
    public array $notify_member_ids = [];



}
