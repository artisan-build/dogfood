<?php

namespace ArtisanBuild\Till\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Sushi\Sushi;

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
