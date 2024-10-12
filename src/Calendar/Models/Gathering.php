<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Models;

use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Gathering extends Model
{
    use HasVerbsState;
    use Sushi;

    protected string $stateClass = GatheringState::class;

    public function getRows(): array
    {
        return $this->loadStatesIntoSushi();
    }
}
