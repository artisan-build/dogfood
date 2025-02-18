<?php

namespace ArtisanBuild\Packagist\Requests;

use InvalidArgumentException;
use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListPackagesRequest extends Request
{
    protected Method $method = Method::GET;

    protected ?string $type;

    protected array $fields;

    protected const array SUPPORTED_TYPES = [
        'library',
        'composer-plugin',
        'metapackage',
        'project',
        'bundle',
        'symfony-bundle',
        // Add more supported types as needed
    ];

    protected const array SUPPORTED_FIELDS = [
        'repository',
        'type',
        'abandoned',
    ];

    /** @throws InvalidArgumentException */
    public function __construct(protected ?string $vendor = null, ?string $type = null, array $fields = [])
    {
        $unsupportedFields = array_diff($fields, self::SUPPORTED_FIELDS);
        if (! empty($unsupportedFields)) {
            throw new InvalidArgumentException('Unsupported fields: '.implode(', ', $unsupportedFields).'. Supported fields are: '.implode(', ', self::SUPPORTED_FIELDS));
        }

        if (! is_null($type) && ! in_array($type, self::SUPPORTED_TYPES, true)) {
            throw new InvalidArgumentException("Unsupported package type: {$type}. Supported types are: ".implode(', ', self::SUPPORTED_TYPES));
        }
        $this->type = $type;
        $this->fields = $fields;
    }

    #[Override]
    public function resolveEndpoint(): string
    {
        return 'packages/list.json';
    }

    #[Override]
    public function defaultQuery(): array
    {
        return array_filter([
            'type' => $this->type,
            'vendor' => $this->vendor,
            'fields' => $this->fields,
        ]);
    }
}
