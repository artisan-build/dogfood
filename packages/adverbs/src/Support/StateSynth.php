<?php

namespace ArtisanBuild\Adverbs\Support;

use Exception;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Thunk\Verbs\State;

class StateSynth extends Synth
{
    public static $key = 'VrbSt';

    public static function match($target)
    {
        return $target instanceof State;
    }

    public function dehydrate($target)
    {
        return [null, [
            'id' => $target->id,
            'type' => $target::class,
        ]];
    }

    public function hydrate($data, $meta)
    {
        return $meta['type']::load($meta['id']);
    }

    #[\Override]
    public function get(&$target, $key): void
    {
        throw new Exception('Cannot get state properties directly.');
    }

    public function set(&$target, $key, $value): void
    {
        throw new Exception('Cannot set state properties directly.');
    }

    public function call(&$target, $method, $params, $addEffect): void
    {
        throw new Exception('Cannot call state methods directly.');
    }
}
