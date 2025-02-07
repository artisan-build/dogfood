<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Traits;

use Throwable;
use Thunk\Verbs\State;

trait HasVerbsState
{
    /**
     * Get the state class for this model.
     */
    abstract protected function getStateClass(): string;

    /**
     * @throws Throwable
     */
    public function verbs_state(): State
    {
        $state = $this->getStateClass();

        return $state::loadOrFail($this->id);
    }
}
