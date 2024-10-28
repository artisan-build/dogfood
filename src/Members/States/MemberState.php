<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\States;

use ArtisanBuild\Hallway\Channels\Attributes\ChannelPermissions;
use ArtisanBuild\Hallway\Channels\Enums\ChannelPermissionTypes;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use ArtisanBuild\Mirror\Mirror;
use Illuminate\Support\Facades\Context;
use ReflectionClass;
use ReflectionClassConstant;
use Thunk\Verbs\Event;
use Thunk\Verbs\State;

class MemberState extends State
{
    public int|string $user_id;

    public string $name = '';
    public string $handle = '';
    public string $display_name = '';

    public MemberRoles $role;

    public PaymentStates $payment_state;

    public ModerationMemberStates $moderation_state = ModerationMemberStates::Active;

    public ?string $profile_picture_url = null;

    public array $channel_ids = [];

    // Always false in real states. Using it to get around having to write to the database during permissions tests
    public bool $in_channel = false;



    // Member notification preferences
    public array $notify_channel_ids = [];
    public array $notify_member_ids = [];

    public function inChannel(): bool
    {
        return in_array(Context::get('channel')?->id, $this->channel_ids, true);
    }

    public function can(Event|string $event)
    {
        $reflection = new ReflectionClass($event);

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

        $channel = Context::get('channel');

        if ( ! $channel instanceof ChannelState) {
            // No channel level permissions required
            return true;
        }


        if (Mirror::reflect($event)->reflection_class->hasProperty('needs_channel_permissions')) {

            $channel_permission = is_string($event)
                ? Mirror::reflect($event)->property('needs_channel_permissions')->reflection_property->getDefaultValue()
                /** @phpstan-ignore-next-line  */
                : $event->needs_channel_permissions;


            assert($channel_permission instanceof ChannelPermissionTypes);

            $key = $channel_permission->value;

            $actions = (new ReflectionClassConstant(ChannelTypes::class, $channel->type->name))
                ->getAttributes(ChannelPermissions::class)[0]->newInstance()->{$key};

            foreach ($actions as $action) {
                if ( ! app($action)()) {
                    return false;
                }
            }
        }

        return true;
    }
}
