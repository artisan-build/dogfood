<?php

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
