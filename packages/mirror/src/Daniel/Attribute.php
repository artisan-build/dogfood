<?php

namespace ArtisanBuild\Mirror\Daniel;

use Illuminate\Support\Collection;
use ReflectionAttribute;

class Attribute
{
    final public function __construct(
        public string $type,
        public Collection $args,
    ) {}

    public static function fromReflectionAttribute(ReflectionAttribute $attr): static
    {
        return new static(
            type: $attr->getName(),
            args: static::prepareArgs($attr),
        );
    }

    protected static function prepareArgs(ReflectionAttribute $attr): Collection
    {
        $args = (new Reflect)->reflect($attr->newInstance())->props()->public()->withValues();

        // if arguments are passed in with a splat, they end up packed in an array called 'arguments'
        // we will unpack them so they can be accessed like any other argument.

        if (
            $args->has('arguments') &&
            is_array($args->get('arguments'))
        ) {
            foreach ($args->get('arguments') as $packed_arg => $value) {
                $args->put($packed_arg, $value);
            }

            $args = $args->except('arguments');
        }

        return $args;
    }
}
