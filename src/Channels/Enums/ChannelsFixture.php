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
        'name' => 'Free Open',
        'type' => ChannelTypes::OpenFree,
    ])]
    case FreeOpen = 229906193380057088;

    #[WithData([
        'name' => 'Premium Open Channel',
        'type' => ChannelTypes::OpenPremium,
    ])]
    case PremiumOpen = 229906217885343744;

    #[WithData([
        'name' => 'Free Private Channel',
        'type' => ChannelTypes::PrivateFree,
    ])]
    case FreePrivate = 229906241970610176;

    #[WithData([
        'name' => 'Premium Private Channel',
        'type' => ChannelTypes::PrivatePremium,
    ])]
    case PremiumPrivate = 229906264325832704;
}
