<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Traits;

use Laravel\Sanctum\HasApiTokens;

trait HasTokens
{
    use HasApiTokens;

    public function tokenCan(string $ability): bool
    {
        $token = $this->currentAccessToken();

        return $token ? $token->can($ability) : false;
    }
}
