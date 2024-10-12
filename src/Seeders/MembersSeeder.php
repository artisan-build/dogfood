<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Seeders;

use ArtisanBuild\Hallway\Members\Events\MemberCreated;
use ArtisanBuild\Jetstream\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MembersSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            MemberCreated::commit(
                member_id: snowflake_id(),
                user_id: $user->id,
                handle: Str::snake(current(explode('@', $user->email))),
            );
        }
    }

}
