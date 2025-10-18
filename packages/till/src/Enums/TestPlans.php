<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Enums;

enum TestPlans: string
{
    case Unsubscribed = 'unsubscribed-plan';
    case CityTrollPlan = 'city-troll-plan';
    case ExtraDefault = 'extra-default-plan';
}
