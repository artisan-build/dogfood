<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Enums;

use ArtisanBuild\FatEnums\Attributes\WithData;
use ArtisanBuild\FatEnums\Traits\DatabaseRecordsEnum;
use ArtisanBuild\FatEnums\Traits\HasKeyValueAttributes;

enum GatheringsFixture: int
{
    use DatabaseRecordsEnum;
    use HasKeyValueAttributes;

    #[WithData([
        'title' => 'Past Free Event',
        'description' => 'A free event that has already happened',
        'start' => '-16',
        'end' => '-15',
        'invitation_level' => InvitationLevels::Free,
    ])]
    case PastFree = 230759683206791168;

    #[WithData([
        'title' => 'Past Premium Event',
        'description' => 'A paid event that has already happened',
        'start' => '-16',
        'end' => '-15',
        'invitation_level' => InvitationLevels::Premium,
    ])]
    case PastPremium = 230759733484122112;

    #[WithData([
        'title' => 'Ongoing Free Event',
        'description' => 'A free event that is currently happening',
        'start' => '-100',
        'end' => '100',
        'invitation_level' => InvitationLevels::Free,
    ])]
    case OngoingFree = 230759817777078272;

    #[WithData([
        'title' => 'Ongoing Premium Event',
        'description' => 'A paid event that is currently happening',
        'start' => '-100',
        'end' => '100',
        'invitation_level' => InvitationLevels::Premium,
    ])]
    case OngoingPremium = 230759863271579648;

    #[WithData([
        'title' => 'Upcoming Free Event',
        'description' => 'A free event that has not yet happened',
        'start' => '100',
        'end' => '101',
        'invitation_level' => InvitationLevels::Free,
    ])]
    case UpcomingFree = 230759893013221376;

    #[WithData([
        'title' => 'Upcoming Premium Event',
        'description' => 'A paid event that has not yet happened',
        'start' => '100',
        'end' => '101',
        'invitation_level' => InvitationLevels::Premium,
    ])]
    case UpcomingPremium = 230759936195096576;

    public const ModelName = '';
}
