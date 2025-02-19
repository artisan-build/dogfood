<?php

namespace ArtisanBuild\Hallway\Seeders;

use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Events\MemberCreated;
use ArtisanBuild\Hallway\Testing\Enums\UserRoles;
use ArtisanBuild\Hallway\Testing\Enums\UsersFixture;
use ArtisanBuild\Verbstream\Events\UserCreated;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UsersFixture::cases() as $case) {

            UserCreated::commit(
                user_id: $case->value,
                name: $case->data('name'),
                email: $case->data('email'),
                role: $case->data('role'),
                payment_status: $case->data('payment_status', null),
                moderation_status: $case->data('moderation_status', null),
                password: Hash::make('password'),
            );

            $member_role = match (true) {
                $case->data('role') === UserRoles::Owner => MemberRoles::Owner,
                $case->data('role') === UserRoles::Admin => MemberRoles::Admin,
                $case->data('email') === 'moderator@hallway.fm' => MemberRoles::Moderator,
                default => MemberRoles::Member,
            };

            MemberCreated::commit(
                user_id: $case->value,
                handle: current(explode('@', (string) $case->data('email'))),
                display_name: $case->data('name'),
                role: $member_role,
                payment_state: $case->data('payment_state', null),
                moderation_state: $case->data('moderation_state', null),
                profile_picture_url: 'https://ui-avatars.com/api/?name='.urlencode((string) $case->data('name')),
            );
        }
    }
}
