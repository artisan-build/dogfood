<?php

namespace ArtisanBuild\Till\Models;

use ArtisanBuild\Till\Traits\HasTokens;
use ArtisanBuild\Till\Traits\Tillable;
use ArtisanBuild\Verbstream\Traits\HasTeams;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property int|null $current_team_id
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillUser wherePassword($value)
 *
 * @mixin \Eloquent
 */
class TillUser extends User
{
    use HasTeams;
    use HasTokens;
    use Sushi;
    use Tillable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRows(): array
    {
        return [
            ['id' => 1, 'name' => 'Team Member', 'email' => 'free@artisan.build', 'password' => Hash::make('password'), 'current_team_id' => 1],
            ['id' => 2, 'name' => 'No Team', 'email' => 'solo@artisan.build', 'password' => Hash::make('password'), 'current_team_id' => 2],
        ];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'id' => 'integer',
            'current_team_id' => 'integer',
        ];
    }
}
