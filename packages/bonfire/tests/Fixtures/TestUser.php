<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Tests\Fixtures;

use ArtisanBuild\Bonfire\Traits\HasBonfireProfile;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    use HasBonfireProfile;

    protected $table = 'test_users';

    protected $guarded = [];
}
