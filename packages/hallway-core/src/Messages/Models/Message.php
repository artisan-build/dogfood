<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $state_class = MessageState::class;
}
