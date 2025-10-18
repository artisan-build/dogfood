<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Enums;

enum SubscriberTypes: string
{
    case Team = 'team';
    case User = 'user';

    public function model()
    {
        return config("till.{$this->value}_model");
    }
}
