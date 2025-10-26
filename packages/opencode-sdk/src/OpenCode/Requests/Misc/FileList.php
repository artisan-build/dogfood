<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * file.list
 *
 * List files and directories
 */
class FileList extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $directory,
        protected string $path,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/file';
    }

    public function defaultQuery(): array
    {
        return array_filter(['directory' => $this->directory, 'path' => $this->path]);
    }
}
