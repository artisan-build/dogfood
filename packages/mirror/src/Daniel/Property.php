<?php

namespace ArtisanBuild\Mirror\Daniel;

use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

class Property
{
    final public function __construct(
        public string $name,
        public bool $initialized,
        public PropertyVisibility $visibility,
        public mixed $value,
        public ?AttributeCollection $attributes,
        public Collection $types,
        public bool $nullable,
    ) {}

    public static function fromReflectionProperty(ReflectionProperty $prop, object $instance): static
    {
        return new static(
            name: $prop->getName(),
            initialized: $prop->isInitialized($instance),
            visibility: PropertyVisibility::fromReflectionProperty($prop),
            value: $prop->isInitialized($instance) ? $prop->getValue($instance) : null,
            attributes: AttributeCollection::fromReflectionProp($prop),
            types: static::getTypesCollectionFromProp($prop),
            nullable: $prop->getType()?->allowsNull() ?? true,
        );
    }

    public static function getTypesCollectionFromProp(ReflectionProperty $prop): Collection
    {
        $type = $prop->getType();

        return match (get_debug_type($type)) {
            ReflectionNamedType::class => collect([$type->getName()]),
            ReflectionUnionType::class => collect($type->getTypes())->map(fn ($t) => $t->getName()),
            default => collect(['mixed']),
        };
    }

    public function getAttribute(string $attribute_type): ?Attribute
    {
        return $this->attributes
            ->ofType($attribute_type)
            ->first();
    }

    public function getAttributeArgument(string $attribute_type, string $arg_name): mixed
    {
        return $this->getAttribute($attribute_type)
            ?->args
            ?->get($arg_name);
    }
}
