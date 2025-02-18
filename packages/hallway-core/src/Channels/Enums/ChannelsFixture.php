<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

use ArtisanBuild\FatEnums\Attributes\WithData;
use ArtisanBuild\FatEnums\Traits\DatabaseRecordsEnum;
use ArtisanBuild\FatEnums\Traits\HasKeyValueAttributes;

enum ChannelsFixture: int
{
    //use DatabaseRecordsEnum;
    use HasKeyValueAttributes;

    #[WithData([
        'name' => 'General',
        'description' => 'Talk about whatever you like in here.',
        'type' => ChannelTypes::OpenFree,
    ])]
    case FreeOpen = 229906193380057088;

    #[WithData([
        'name' => 'Premium Chat',
        'description' => 'Freestyle chat for premium members',
        'type' => ChannelTypes::OpenPremium,
    ])]
    case PremiumOpen = 229906217885343744;

    #[WithData([
        'name' => 'Invite Only',
        'description' => 'An invitation-only channel open to free and premium members',
        'type' => ChannelTypes::PrivateFree,
    ])]
    case FreePrivate = 229906241970610176;

    #[WithData([
        'name' => 'Premium Invite Only',
        'description' => 'An invitation-only channel for premium members only',
        'type' => ChannelTypes::PrivatePremium,
    ])]
    case PremiumPrivate = 229906264325832704;
}
