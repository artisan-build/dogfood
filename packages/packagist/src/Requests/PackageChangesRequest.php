<?php

namespace ArtisanBuild\Packagist\Requests;

use Carbon\Carbon;
use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class PackageChangesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int|string|Carbon|null $since = null
    ) {
        $this->since = match (true) {
            $this->since === null => Carbon::now()->subMinutes(10)->timestamp,
            is_int($this->since) => $this->since,
            $this->since instanceof Carbon => $this->since->timestamp,
            default => Carbon::parse($this->since)->timestamp,
        };
    }

    #[Override]
    public function resolveEndpoint(): string
    {
        return 'metadata/changes.json';
    }

    public function defaultQuery(): array
    {
        return [
            'since' => $this->since * 10000,
        ];
    }
}
