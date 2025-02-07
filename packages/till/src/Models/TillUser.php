<?php

namespace ArtisanBuild\Till\Models;

use ArtisanBuild\Till\Traits\Tillable;
use ArtisanBuild\Verbstream\Traits\HasTeams;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Sushi\Sushi;

class TillUser extends User
{
    use HasTeams;
    use Sushi;
    use Tillable;

    public function getRows(): array
    {
        return [
            ['id' => 1, 'name' => 'Team Member', 'email' => 'free@artisan.build', 'password' => Hash::make('password'), 'current_team_id' => 1],
            ['id' => 2, 'name' => 'No Team', 'email' => 'solo@artisan.build', 'password' => Hash::make('password'), 'current_team_id' => 2],
        ];
    }
}
