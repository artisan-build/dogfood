<?php

namespace ArtisanBuild\Packagist\Requests;

use Override;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdatePackageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $repository,
        public ?string $username = null,
        public ?string $token = null,
    ) {
        $this->username ??= config('packagist.username');
        $this->token ??= config('packagist.safe_token', config('packagist.main_token'));
    }

    #[Override]
    public function resolveEndpoint(): string
    {
        return '/api/update-package';
    }

    #[Override]
    public function defaultQuery(): array
    {
        return [
            'username' => $this->username,
            'apiToken' => $this->token,
        ];
    }

    public function defaultBody(): array
    {
        return [
            'repository' => $this->repository,
        ];
    }
}
