<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\User\Me;
use ArtisanBuild\ForgeClient\Requests\User\UserShow;
use ArtisanBuild\ForgeClient\Resource;
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
