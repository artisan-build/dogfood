<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Seeders;

use ArtisanBuild\Hallway\Calendar\Enums\GatheringsFixture;
use ArtisanBuild\Hallway\Calendar\Events\GatheringCreated;
use Illuminate\Database\Seeder;

class GatheringsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (GatheringsFixture::cases() as $case) {
            GatheringCreated::commit(
                gathering_id: $case->value,
                title: $case->data('title'),
                description: $case->data('description'),
                start: now()->addDays((int) $case->data('start')),
                end: now()->addDays((int) $case->data('end')),
                invitation_level: $case->data('invitation_level'),
            );
        }
    }
}
