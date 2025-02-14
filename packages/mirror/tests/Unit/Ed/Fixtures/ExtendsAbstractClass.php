<?php

declare(strict_types=1);

namespace ArtisanBuild\Mirror\Tests\Unit\Ed\Fixtures;

class ExtendsAbstractClass extends AbstractClass
{
    public string $public_string;

    protected string $protected_string;

    private string $private_string = '';

    public function public_method(): string
    {
        return $this->private_string;
    }
}
