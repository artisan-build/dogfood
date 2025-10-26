<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * find.text
 *
 * Find text in files
 */
class FindText extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory,
        protected string $pattern,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/find';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'pattern' => $this->pattern]);
    }
}
