<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace ArtisanBuild\Till\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $team_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TillMembership whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTillMembership {}
}

namespace ArtisanBuild\Till\Models{
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
 * @method static Builder<static>|TillTeam newModelQuery()
 * @method static Builder<static>|TillTeam newQuery()
 * @method static Builder<static>|TillTeam query()
 * @method static Builder<static>|TillTeam whereId($value)
 * @method static Builder<static>|TillTeam whereName($value)
 * @method static Builder<static>|TillTeam whereUserId($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTillTeam {}
}

namespace ArtisanBuild\Till\Models{
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
 * @method static Builder<static>|TillUser newModelQuery()
 * @method static Builder<static>|TillUser newQuery()
 * @method static Builder<static>|TillUser query()
 * @method static Builder<static>|TillUser whereCurrentTeamId($value)
 * @method static Builder<static>|TillUser whereEmail($value)
 * @method static Builder<static>|TillUser whereId($value)
 * @method static Builder<static>|TillUser whereName($value)
 * @method static Builder<static>|TillUser wherePassword($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTillUser {}
}

