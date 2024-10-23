<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Pages\States;

use Thunk\Verbs\State;

class PageState extends State
{
    public string $title;
    public string $slug;

    public bool $is_lobby = false;
    public ?string $free_content = null;

    public ?string $premium_content = null;

}
