<?php

declare(strict_types=1);

namespace ArtisanBuild\Mirror\Ed;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;

class MirrorService
{
    public ReflectionClass|ReflectionProperty|ReflectionAttribute|ReflectionMethod $reflection;

    public ReflectionClass $reflection_class;

    public ReflectionProperty $reflection_property;

    public ReflectionAttribute $reflection_attribute;

    public ReflectionMethod $reflection_method;

    public ReflectionClassConstant $reflection_constant;

    public function __call($name, $arguments): mixed
    {
        return $this->reflection->{$name}(...$arguments);
    }

    public function assert(): true
    {
        return true;
    }

    public function reflect(object|string $class): static
    {
        $this->reflection = $this->reflection_class = new ReflectionClass($class);

        return $this;
    }

    public function property(string $property): static
    {
        $this->reflection = $this->reflection_property = $this->reflection->getProperty($property);

        return $this;
    }

    public function attribute(string $attribute): static
    {
        throw_if(
            condition: empty($this->reflection->getAttributes($attribute)),
            message: "The Attribute {$attribute} does not exist here",
        );

        $this->reflection = $this->reflection_attribute = collect($this->reflection->getAttributes($attribute))->first();

        return $this;
    }

    public function constant(string $constant): static
    {
        $this->reflection = $this->reflection_constant = $this->reflection->getConstant($constant);

        return $this;
    }

    public function method(string $method): static
    {
        throw_unless(
            condition: $this->reflection instanceof ReflectionClass,
            exception: new Exception('Only classes can have methods'),
        );

        throw_unless(
            condition: $this->reflection->hasMethod($method),
            exception: new Exception("Method {$method} does not exist."),
        );

        $this->reflection = $this->reflection_method = $this->reflection->getMethod($method);

        return $this;
    }

    public function extends(string $abstract): static
    {
        throw_unless(
            condition: $this->reflection instanceof ReflectionClass,
            exception: new Exception('Only classes can extend things'),
        );

        throw_unless(
            condition: $this->reflection->isSubclassOf($abstract),
            exception: new Exception("{$this->reflection_class->getName()} does not extend {$abstract}"),
        );

        return $this;
    }
}
