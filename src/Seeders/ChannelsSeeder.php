<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Seeders;

use ArtisanBuild\Hallway\Channels\Enums\ChannelsFixture;
use ArtisanBuild\Hallway\Channels\Events\CommunityChannelCreated;
use Illuminate\Database\Seeder;

class ChannelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ChannelsFixture::cases() as $case) {
            CommunityChannelCreated::commit(
                channel_id: $case->value,
                name: $case->data($case, 'name'),
                type: $case->data($case, 'type'),
            );
        }
    }
}
