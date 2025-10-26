<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * file.read
 *
 * Read a file
 */
class FileRead extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory,
        protected string $path,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/file/content';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'path' => $this->path]);
    }
}
