<?php

namespace ArtisanBuild\Till\Models;

use App\Models\Membership;
use App\Models\Team;
use ArtisanBuild\Till\Traits\HasTokens;
use ArtisanBuild\Till\Traits\Tillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property int|null $current_team_id
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Membership|null $membership
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static Builder<static>|TillUser newModelQuery()
 * @method static Builder<static>|TillUser newQuery()
 * @method static Builder<static>|TillUser query()
 * @method static Builder<static>|TillUser whereCurrentTeamId($value)
 * @method static Builder<static>|TillUser whereEmail($value)
 * @method static Builder<static>|TillUser whereId($value)
 * @method static Builder<static>|TillUser whereName($value)
 * @method static Builder<static>|TillUser wherePassword($value)
 *
 * @mixin Eloquent
 */
class TillUser extends User
{
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
