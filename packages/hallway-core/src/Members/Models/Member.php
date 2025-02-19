<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use ArtisanBuild\Hallway\Members\Traits\HasChannelMemberships;
use Illuminate\Database\Eloquent\Model;

/**
 * @property MemberRoles $role
 */
class Member extends Model
{
    use GetsRowsFromVerbsStates;
    use HasChannelMemberships;
    use HasVerbsState;

    public $incrementing = false;

    protected string $state_class = MemberState::class;

    protected $guarded = [];

    public function casts(): array
    {
        return [
            'role' => MemberRoles::class,
        ];
    }
}
