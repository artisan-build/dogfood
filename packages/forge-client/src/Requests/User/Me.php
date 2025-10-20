<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\User;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * me
 *
 * Show the authenticated user.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class Me extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/me';
    }
}
