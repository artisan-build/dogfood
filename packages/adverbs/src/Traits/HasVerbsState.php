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
    protected function getStateClass(): string
    {
        throw_if(
            condition: empty($this->state_class),
            exception: 'State class is not set',
        );

        $state = $this->state_class;
        
        throw_if(
            condition: ! class_exists($state),
            exception: 'Unknown class '.$state,
        );

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
