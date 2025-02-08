<?php

namespace ArtisanBuild\Till\Models;

use App\Models\User;
use ArtisanBuild\Verbstream\Verbstream;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Sushi\Sushi;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $user_id
 * @property-read User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillTeam whereUserId($value)
 * @mixin \Eloquent
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Verbstream::userModel(), 'user_id');
    }

    /**
     * Get all of the team's users including its owner.
     *
     * @return Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Verbstream::userModel(), Verbstream::membershipModel())
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Determine if the given user belongs to the team.
     *
     * @param  User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsTeam($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     *
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(fn ($user) => $user->email === $email);
    }

    /**
     * Determine if the given user has the given permission on the team.
     *
     * @param  User  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasTeamPermission($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the team.
     *
     * @return HasMany
     */
    public function teamInvitations()
    {
        return $this->hasMany(Verbstream::teamInvitationModel());
    }

    /**
     * Remove the given user from the team.
     *
     * @param  User  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_team_id === $this->id) {
            $user->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the team's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
