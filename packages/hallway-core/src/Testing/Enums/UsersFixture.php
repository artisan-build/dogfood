<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Testing\Enums;

use App\Models\User;
use ArtisanBuild\FatEnums\Attributes\WithData;
use ArtisanBuild\FatEnums\Traits\DatabaseRecordsEnum;
use ArtisanBuild\FatEnums\Traits\HasKeyValueAttributes;
use ArtisanBuild\Hallway\Moderation\Enums\ModerationMemberStates;
use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;

enum UsersFixture: int
{
    use DatabaseRecordsEnum;
    use HasKeyValueAttributes;

    #[WithData([
        'name' => 'Owner',
        'email' => 'owner@hallway.fm',
        'moderation_state' => ModerationMemberStates::Active,
        'payment_state' => PaymentStates::Exempt,
        'role' => UserRoles::Owner,
    ])]
    case Owner = 236023265936146432;

    #[WithData([
        'name' => 'Free User',
        'email' => 'free@hallway.fm',
        'moderation_state' => ModerationMemberStates::Active,
        'payment_state' => PaymentStates::Free,
        'role' => UserRoles::User,
    ])]
    case FreeUser = 229216212576559104;

    #[WithData([
        'name' => 'Premium User',
        'email' => 'paid@hallway.fm',
        'moderation_state' => ModerationMemberStates::Active,
        'payment_state' => PaymentStates::Premium,
        'role' => UserRoles::User,
    ])]
    case PremiumUser = 229216351140798464;

    #[WithData([
        'name' => 'Admin',
        'email' => 'admin@hallway.fm',
        'moderation_state' => ModerationMemberStates::Active,
        'payment_state' => PaymentStates::Exempt,
        'role' => UserRoles::Admin,
    ])]
    case Admin = 229216542009831424;

    #[WithData([
        'name' => 'Naughty Free User',
        'email' => 'naughtyfree@hallway.fm',
        'moderation_state' => ModerationMemberStates::Suspended,
        'payment_state' => PaymentStates::Free,
        'role' => UserRoles::User,
    ])]
    case SuspendedFreeForViolationUser = 229216569771302912;

    #[WithData([
        'name' => 'Naughty Premium User',
        'email' => 'naughtypaid@hallway.fm',
        'moderation_state' => ModerationMemberStates::Suspended,
        'payment_state' => PaymentStates::Premium,
        'role' => UserRoles::User,
    ])]
    case SuspendedPremiumForViolationUser = 229857418629480448;

    #[WithData([
        'name' => 'Limited Free User',
        'email' => 'limitedfree@hallway.fm',
        'moderation_state' => ModerationMemberStates::Limited,
        'payment_state' => PaymentStates::Free,
        'role' => UserRoles::User,
    ])]
    case LimitedForViolationUser = 229216918613446656;

    #[WithData([
        'name' => 'Limited Appealed Free User',
        'email' => 'limitedappealed@hallway.fm',
        'moderation_state' => ModerationMemberStates::LimitAppealed,
        'payment_state' => PaymentStates::Free,
        'role' => UserRoles::User,
    ])]
    case LimitedForViolationAppealedUser = 229858454199193600;

    #[WithData([
        'name' => 'Broke User',
        'email' => 'broke@hallway.fm',
        'moderation_state' => ModerationMemberStates::Active,
        'payment_state' => PaymentStates::Suspended,
        'role' => UserRoles::User,
    ])]
    case SuspendedForNonPaymentUser = 229228023440744448;
    public const ModelName = User::class;
}
