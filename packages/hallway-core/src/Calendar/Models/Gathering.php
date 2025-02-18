<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Calendar\States\GatheringState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $start
 * @property Carbon $end
 */
class Gathering extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected $appends = ['day', 'month'];

    protected string $stateClass = GatheringState::class;

    public function day(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start->format('Y-m-d'),
        );
    }

    public function month(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start->format('Y-m'),
        );
    }

    public function casts()
    {
        return [
            'start' => 'immutable_datetime',
            'end' => 'immutable_datetime',
        ];
    }

}
