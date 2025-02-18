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
    protected function getVerbsState(): string
    {
        $state = $this->state_class;
        throw_if(! class_exists($state), 'Unknown class '.$this->verbs_state);

        return $state;
    }

    /**
     * @throws Throwable
     */
    public function verbs_state(): State
    {
        $state = $this->getStateClass();

        return $state::loadOrFail($this->id);
    }
}
