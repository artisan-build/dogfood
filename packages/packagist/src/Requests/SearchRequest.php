<?php

namespace ArtisanBuild\Packagist\Requests;

use InvalidArgumentException;
use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Throwable;

class SearchRequest extends Request
{
    protected Method $method = Method::GET;

    protected const array SUPPORTED_TYPES = [
        'library',
        'composer-plugin',
        'metapackage',
        'project',
        'bundle',
        'symfony-bundle',
        // Add more supported types as needed
    ];

    /**
     * @throws Throwable
     */
    public function __construct(
        protected string $q,
        protected int $page = 1,
        protected int $per_page = 25,
        protected ?array $tags = null,
        protected ?string $type = null,
    ) {
        throw_if($type !== null && ! in_array($type, self::SUPPORTED_TYPES, true),
            new InvalidArgumentException("Unsupported package type: {$type}. Supported types are: ".implode(', ', self::SUPPORTED_TYPES)));
    }

    #[Override]
    public function resolveEndpoint(): string
    {
        return '/search.json';
    }

    #[Override]
    public function defaultQuery(): array
    {
        return array_filter([
            'q' => $this->q,
            'per_page' => $this->per_page,
            'tags' => $this->tags,
            'type' => $this->type,
        ]);
    }
}
