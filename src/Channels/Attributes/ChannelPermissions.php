<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class ChannelPermissions
{
    public function __construct(
        public array $read = [],
        public array $write = [],
        public array $comment = [],
        public array $invite = [],
    ) {}
}
