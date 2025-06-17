<?php

namespace ArtisanBuild\Till\Models;

use App\Models\Membership;
use App\Models\TeamInvitation;
use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string|null $name
 * @property int|null $user_id
 * @property-read User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static Builder<static>|TillTeam newModelQuery()
 * @method static Builder<static>|TillTeam newQuery()
 * @method static Builder<static>|TillTeam query()
 * @method static Builder<static>|TillTeam whereId($value)
 * @method static Builder<static>|TillTeam whereName($value)
 * @method static Builder<static>|TillTeam whereUserId($value)
 *
 * @mixin Eloquent
 */
class TillTeam extends Model
{
    use Sushi;

    protected $with = ['owner'];

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function getRows(): array
    {
        return [
            ['id' => 1, 'name' => 'Team', 'user_id' => 1],
        ];
    }

    /**
     * Get the owner of the team.
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
