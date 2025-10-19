<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\User\Me;
use ArtisanBuild\ForgeSdk\Requests\User\UserShow;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class User extends Resource
{
    public function userShow(): Response
    {
        return $this->connector->send(new UserShow);
    }

    public function me(): Response
    {
        return $this->connector->send(new Me);
    }
}
