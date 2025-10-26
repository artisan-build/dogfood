<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode;

use ArtisanBuild\OpencodeSdk\OpenCode\Resource\Misc;
use Saloon\Http\Connector;

/**
 * opencode
 *
 * opencode api
 */
class OpenCode extends Connector
{
    public function __construct() {}

    public function resolveBaseUrl(): string
    {
        return '/';
    }

    public function misc(): Misc
    {
        return new Misc($this);
    }
}
