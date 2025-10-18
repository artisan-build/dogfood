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


namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership query()
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMembership {}
}

namespace App\Models{
/**
 * @template TFactory of Factory
 * @property-read User|null $owner
 * @property-read Collection<int, TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read Membership|null $membership
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static TeamFactory factory($count = null, $state = [])
 * @method static Builder<static>|Team newModelQuery()
 * @method static Builder<static>|Team newQuery()
 * @method static Builder<static>|Team query()
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $personal_team
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TFactory|null $use_factory
 * @method static Builder<static>|Team whereCreatedAt($value)
 * @method static Builder<static>|Team whereId($value)
 * @method static Builder<static>|Team whereName($value)
 * @method static Builder<static>|Team wherePersonalTeam($value)
 * @method static Builder<static>|Team whereUpdatedAt($value)
 * @method static Builder<static>|Team whereUserId($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeam {}
}

namespace App\Models{
/**
 * @property-read Team|null $team
 * @method static Builder<static>|TeamInvitation newModelQuery()
 * @method static Builder<static>|TeamInvitation newQuery()
 * @method static Builder<static>|TeamInvitation query()
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder<static>|TeamInvitation whereCreatedAt($value)
 * @method static Builder<static>|TeamInvitation whereEmail($value)
 * @method static Builder<static>|TeamInvitation whereId($value)
 * @method static Builder<static>|TeamInvitation whereRole($value)
 * @method static Builder<static>|TeamInvitation whereTeamId($value)
 * @method static Builder<static>|TeamInvitation whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeamInvitation {}
}

namespace App\Models{
/**
 * @template TFactory of Factory
 * @property-read Team|null $currentTeam
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read string $profile_photo_url
 * @property-read Membership|null $membership
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property-read TFactory|null $use_factory
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereCurrentTeamId($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User whereProfilePhotoPath($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder<static>|User whereTwoFactorSecret($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @property-read Collection<int, Member> $hallway_members
 * @property-read int|null $hallway_members_count
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace ArtisanBuild\Adverbs\Models{
/**
 * @property string|null $name
 * @property string|null $description
 * @property string|null $metadata
 * @property int|null $id
 * @property string|null $last_event_id
 * @method static Builder<static>|Dummy newModelQuery()
 * @method static Builder<static>|Dummy newQuery()
 * @method static Builder<static>|Dummy query()
 * @method static Builder<static>|Dummy whereDescription($value)
 * @method static Builder<static>|Dummy whereId($value)
 * @method static Builder<static>|Dummy whereLastEventId($value)
 * @method static Builder<static>|Dummy whereMetadata($value)
 * @method static Builder<static>|Dummy whereName($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDummy {}
}

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

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @property-read UserModel|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAccount {}
}

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @property-read Account|null $account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountProfile query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAccountProfile {}
}

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @property-read StubProfile|null $profile
 * @method static Builder<static>|Stub newModelQuery()
 * @method static Builder<static>|Stub newQuery()
 * @method static Builder<static>|Stub query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStub {}
}

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stub> $stubs
 * @property-read int|null $stubs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubModel query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStubModel {}
}

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StubProfile query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStubProfile {}
}

namespace ArtisanBuild\Turbulence\Models{
/**
 * @internal
 * @property-read int $current_account_id
 * @property-read Collection $accounts
 * @property-read Account $account
 * @property-read int|null $accounts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUserModel {}
}

