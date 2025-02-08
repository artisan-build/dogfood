<?php

namespace ArtisanBuild\Till\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Sushi\Sushi;

/**
 * 
 *
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
class TillMembership extends Pivot
{
    use Sushi;

    public function getRows(): array
    {
        return [
            ['id' => 1, 'user_id' => 1, 'team_id' => 1],
        ];
    }
}
