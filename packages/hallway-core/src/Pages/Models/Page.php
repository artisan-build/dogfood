<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Pages\Models;

use ArtisanBuild\Adverbs\Traits\GetsRowsFromVerbsStates;
use ArtisanBuild\Adverbs\Traits\HasVerbsState;
use ArtisanBuild\Hallway\Pages\States\PageState;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use GetsRowsFromVerbsStates;
    use HasVerbsState;

    protected string $state_class = PageState::class;
}
