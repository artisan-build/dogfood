<?php

namespace ArtisanBuild\Hallway\Testing\Seeders;

use ArtisanBuild\Hallway\Seeders\ChannelsSeeder;
use ArtisanBuild\Hallway\Seeders\GatheringsSeeder;
use ArtisanBuild\Hallway\Seeders\UsersSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            ChannelsSeeder::class,
            GatheringsSeeder::class,
        ]);
    }
}
