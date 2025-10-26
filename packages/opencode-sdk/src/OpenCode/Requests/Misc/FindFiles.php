<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * find.files
 *
 * Find files
 */
class FindFiles extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory,
        protected string $query,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/find/file';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'query' => $this->query]);
    }
}
