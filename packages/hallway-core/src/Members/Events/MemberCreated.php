<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberCreated extends Event
{
    use SimpleApply;

    #[StateId(MemberState::class)]
    public ?int $member_id = null;


    public int $user_id;

    public MemberRoles $role = MemberRoles::Member;
    public PaymentStates $payment_state = PaymentStates::Free;
    public ModerationMemberStates $moderation_state = ModerationMemberStates::Active;

    public string $handle;

    public string $display_name;

    public ?string $profile_picture_url = null;


    public array $channel_ids = [];


}
