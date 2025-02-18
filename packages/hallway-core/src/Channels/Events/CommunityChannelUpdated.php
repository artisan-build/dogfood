<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Channels\Enums\ChannelTypes;
use ArtisanBuild\Hallway\Channels\Models\Channel;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Illuminate\Support\Facades\Route;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[EventForm(
    submit_text: 'Update Channel',
)]
class CommunityChannelUpdated extends Event
{
    use AuthorizesBasedOnMemberState;
    use SimpleApply;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(ChannelState::class)]
    public int $channel_id;

    #[EventInput(
        type: InputTypes::Text,
        rules: ['string', 'min:6'],
    )]
    public ?string $name = null;

    #[EventInput(
        type: InputTypes::Text,
        params: ['maxlength' => '128'],
        rules: ['string', 'required', 'max:128'],
    )]
    public string $description = '';

    #[EventInput(
        type: InputTypes::Select,
        rules: ['required'],
        options: ChannelTypes::class,
        options_filter: 'isCommunityChannel',
    )]
    public ?ChannelTypes $type = null;
    /*
        public function apply(ChannelState $state)
        {
            if ($this->type !== null) {
                $state->type = $this->type;
            }

            if ($this->name !== null) {
                $state->name = $this->name;
            }
        }*/

    #[Once]
    public function handle(): ?Channel
    {
        if (Route::has(config('hallway-flux.route-name-prefix').'channel')) {
            return new Channel([
                'id' => $this->channel_id,
                'flux_url' => route(config('hallway-flux.route-name-prefix').'channel', ['channel' => $this->channel_id]),
            ]);
        }

        return null;
    }
}
