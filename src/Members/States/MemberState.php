<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\States;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use Illuminate\Support\Facades\Context;
use ReflectionClass;
use Thunk\Verbs\State;

class MemberState extends State
{
    public int|string $user_id;

    public string $name = '';
    public string $handle = '';
    public string $display_name = '';

    public MemberRoles $role;

    public PaymentStates $payment_state;

    public ?string $profile_picture_url = null;

    public array $channel_ids = [];



    // Member notification preferences
    public array $notify_channel_ids = [];
    public array $notify_member_ids = [];

    public function inChannel(): bool
    {
        return in_array(Context::get('channel')?->id, $this->channel_ids, true);
    }

    public function can(string $event)
    {
        $reflection = new ReflectionClass(new $event());

        if ($reflection->hasProperty('authorized_member_roles')) {

            if ( ! in_array($this->role, $reflection->getProperty('authorized_member_roles')->getDefaultValue(), true)) {
                return false;
            }
        }

        if ($reflection->hasProperty('authorized_payment_states')) {
            if ( ! in_array($this->payment_state, $reflection->getProperty('authorized_payment_states')->getDefaultValue(), true)) {
                return false;
            }
        }

        return true;
    }
}
