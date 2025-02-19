<?php

namespace ArtisanBuild\Mirror\Daniel;

use ReflectionClass;
use ReflectionProperty;

class Reflect
{
    public ReflectionClass $reflector;

    public object $object;

    public function reflect(object $object): static
    {
        $this->object = $object;
        $this->reflector = new ReflectionClass($object);

        return $this;
    }

    public function props(): PropertyCollection
    {
        return PropertyCollection::fromReflect($this);
    }

    public function attributes(): AttributeCollection
    {
        return AttributeCollection::fromReflectionClass($this->reflector);
    }

    public function attribute(string $name): Attribute
    {
        return $this->attributes()->ofType($name)->first();
    }

    public static function propInitialized(string $property, object $object): bool
    {
        return (new ReflectionProperty($object, $property))->isInitialized($object);
    }
}
