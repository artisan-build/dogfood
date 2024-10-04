<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Member extends Model
{
    use Sushi;

    protected $rows = [
        [
            'id' => 232843629000937472,
            'name' => 'Ed',
        ],
        [
            'id' => 232843694967836672,
            'name' => 'Simon',
        ],
    ];

}
