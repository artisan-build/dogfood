<?php

namespace ArtisanBuild\Till\Listeners;

use ArtisanBuild\Till\Actions\ClearAbilitiesCache;
use ArtisanBuild\Till\Attributes\ImpactsAbilities;
use ReflectionClass;
use Thunk\Verbs\Attributes\Hooks\On;
use Thunk\Verbs\Event;
use Thunk\Verbs\Lifecycle\Phase;

class ResetsAbilities
{
    #[On(Phase::Fired)]
    public function resetAbilities(Event $event)
    {
        $reflection = new ReflectionClass($event);

        if (empty($reflection->getAttributes(ImpactsAbilities::class))) {
            return;
        }

        app(ClearAbilitiesCache::class)();
    }
}
