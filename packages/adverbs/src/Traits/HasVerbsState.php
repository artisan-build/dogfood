<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Traits;

use ReflectionClass;
use Throwable;
use Thunk\Verbs\State;

trait HasVerbsState
{
    /**
     * @throws Throwable
     */
    public function verbs_state(): State
    {
        $reflection = new ReflectionClass($this);
        throw_if(! $reflection->hasProperty('state_class'), 'Please provide a protected string property called state_class on your model to use the HasVerbsState trait on the class.');

        $state = $this->state_class;

        return $state::loadOrFail($this->id);
    }
}
