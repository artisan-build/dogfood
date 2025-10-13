<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Team|null $team
 *
 * @method static Builder<static>|TeamInvitation newModelQuery()
 * @method static Builder<static>|TeamInvitation newQuery()
 * @method static Builder<static>|TeamInvitation query()
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static>|TeamInvitation whereCreatedAt($value)
 * @method static Builder<static>|TeamInvitation whereEmail($value)
 * @method static Builder<static>|TeamInvitation whereId($value)
 * @method static Builder<static>|TeamInvitation whereRole($value)
 * @method static Builder<static>|TeamInvitation whereTeamId($value)
 * @method static Builder<static>|TeamInvitation whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin IdeHelperTeamInvitation
 */
class TeamInvitation extends Model
{
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
