<?php

namespace ArtisanBuild\Mirror\Tests\Unit\Daniel;

use ArtisanBuild\Mirror\Daniel\Property;
use ArtisanBuild\Mirror\Facades\Mirror;
use Attribute;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReflectionHelperTest extends TestCase
{
    #[Test]
    public function it_can_get_an_objects_props(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $props = Mirror::driver('daniel')->reflect($obj)->props();

        $props->ensure(Property::class);

        $this->assertEquals(
            ['foo', 'bar', 'is_baz', 'bing', 'bong'],
            $props->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_with_an_attribute(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $props = Mirror::driver('daniel')->reflect($obj)->props()->hasAttribute(AttributeWithoutArgs::class);

        $this->assertEquals(
            ['foo', 'bar'],
            $props->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_with_an_attribute_with_args(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $props = Mirror::driver('daniel')->reflect($obj)->props()
            ->hasAttribute(
                AttributeWithtArgs::class,
                ['carter' => 1]
            );

        $this->assertEquals(
            ['bar'],
            $props->keys()->toArray()
        );

        $props = Mirror::driver('daniel')->reflect($obj)->props()
            ->hasAttribute(
                AttributeWithtArgs::class,
                ['carter' => 2]
            );

        $this->assertEquals(
            ['is_baz'],
            $props->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_of_a_specific_type(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $props = Mirror::driver('daniel')->reflect($obj)->props()
            ->ofType('string');

        $this->assertEquals(
            ['bar', 'bong'],
            $props->keys()->toArray()
        );

        $props_with_nullable = Mirror::driver('daniel')->reflect($obj)->props()
            ->ofType('string', include_nullable: true);

        $this->assertEquals(
            ['bar', 'bing', 'bong'],
            $props_with_nullable->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_with_their_values(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $kv = Mirror::driver('daniel')->reflect($obj)->props()->withValues();

        $this->assertEquals(
            [
                'foo' => 123,
                'bar' => 'hello',
                'is_baz' => true,
                'bing' => 'bing',
                'bong' => 'bong',
            ],
            $kv->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_by_initialization(): void
    {
        $obj = new ExampleObject(
            bar: 'hello',
            bing: 'bing',
        );

        $initialized = Mirror::driver('daniel')->reflect($obj)->props()->initialized();

        $this->assertEquals(
            ['bar', 'bing'],
            $initialized->keys()->toArray()
        );

        $uninitialized = Mirror::driver('daniel')->reflect($obj)->props()->uninitialized();

        $this->assertEquals(
            ['foo', 'is_baz', 'bong'],
            $uninitialized->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_by_visibility(): void
    {
        $obj = new ExampleObject(
            foo: 123,
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        $public = Mirror::driver('daniel')->reflect($obj)->props()->public();
        $this->assertEquals(
            ['foo', 'bar', 'is_baz', 'bing'],
            $public->keys()->toArray()
        );

        $protected = Mirror::driver('daniel')->reflect($obj)->props()->protected();
        $this->assertEquals(
            ['bong'],
            $protected->keys()->toArray()
        );

        $private = Mirror::driver('daniel')->reflect($obj)->props()->private();
        $this->assertEquals(
            [],
            $private->keys()->toArray()
        );
    }

    #[Test]
    public function it_can_get_an_objects_props_in_a_chained_fluent_monstrosity(): void
    {
        $obj = new ExampleObject(
            bar: 'hello',
            is_baz: true,
            bing: 'bing',
            bong: 'bong',
        );

        // Get all public, initialized string properties
        $result = Mirror::driver('daniel')->reflect($obj)
            ->props()
            ->public()
            ->initialized()
            ->ofType('string', include_nullable: true);

        $this->assertEquals(
            ['bar', 'bing'],
            $result->keys()->toArray()
        );

        // Get all properties with AttributeWithoutArgs that are uninitialized
        $result = Mirror::driver('daniel')->reflect($obj)
            ->props()
            ->hasAttribute(AttributeWithoutArgs::class)
            ->uninitialized();

        $this->assertEquals(
            ['foo'],
            $result->keys()->toArray()
        );

        // Get all properties with AttributeWithtArgs where carter=2 that are public and boolean
        $result = Mirror::driver('daniel')->reflect($obj)
            ->props()
            ->hasAttribute(AttributeWithtArgs::class, ['carter' => 2])
            ->public()
            ->ofType('bool');

        $this->assertEquals(
            ['is_baz'],
            $result->keys()->toArray()
        );

        // Get all string properties and their values
        $result = Mirror::driver('daniel')->reflect($obj)
            ->props()
            ->ofType('string', include_nullable: false)
            ->withValues();

        $this->assertEquals(
            ['bar' => 'hello', 'bong' => 'bong'],
            $result->toArray()
        );
    }
}

class ExampleObject
{
    #[AttributeWithoutArgs]
    public int $foo;

    #[AttributeWithtArgs(carter: 1)]
    #[AttributeWithoutArgs]
    public string $bar;

    #[AttributeWithtArgs(carter: 2)]
    public bool $is_baz;

    public ?string $bing = null;

    protected string $bong;

    public function __construct(...$args)
    {
        // setting values this way instead of constructor
        // property promition so that I can keep some props
        // un-initialized for tests.

        foreach ($args as $arg => $value) {
            $this->{$arg} = $value;
        }
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class AttributeWithoutArgs {}

#[Attribute(Attribute::TARGET_PROPERTY)]
class AttributeWithtArgs
{
    public function __construct(
        public int $carter
    ) {}
}
