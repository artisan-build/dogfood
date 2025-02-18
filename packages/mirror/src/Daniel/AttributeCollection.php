<?php

namespace ArtisanBuild\Mirror\Daniel;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;

class AttributeCollection extends Collection
{
    final public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }

    public static function fromReflectionProp(ReflectionProperty $prop): static
    {
        return (new static($prop->getAttributes()))->map(
            fn ($attr) => Attribute::fromReflectionAttribute($attr)
        );
    }

    public static function fromReflectionClass(ReflectionClass $class): static
    {
        return (new static($class->getAttributes()))->map(
            fn ($attr) => Attribute::fromReflectionAttribute($attr)
        );
    }

    public function ofType(string $type): static
    {
        return $this->filter(
            fn ($a) => $a->type === $type
        );
    }

    public function withArguments(array $args, string $and_or = 'and'): static
    {
        $args = collect($args);

        return match ($and_or) {
            'and' => $this->filter(
                fn ($a) => $args->diffAssoc($a->args)->isEmpty()
            ),
            'or' => $this->filter(
                fn ($a) => $args->diffAssoc($a->args)->count() < $args->count()
            ),
            default => throw new \Exception('Invalid and_or value'),
        };
    }
}
