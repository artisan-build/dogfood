<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\States;

use ArtisanBuild\Hallway\Members\Enums\MemberChannelRoles;
use Carbon\Carbon;
use Thunk\Verbs\State;

class ChannelMembershipState extends State
{
    public int $member_id;
    public int $channel_id;
    public ?Carbon $added_at = null;
    public ?int $added_by = null;

    public ?Carbon $removed_at = null;
    public ?int $removed_by = null;

    public MemberChannelRoles $role;
    public ?Carbon $role_at = null;
    public ?int $role_by = null;
    public ?string $role_by_type = null;

    public ?int $last_message_read_id = null;



}
