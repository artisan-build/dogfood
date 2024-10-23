<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use Illuminate\Database\Eloquent\Model;

class Gathering extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $stateClass = GatheringState::class;

}
