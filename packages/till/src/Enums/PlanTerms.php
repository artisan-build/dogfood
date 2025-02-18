<?php

namespace ArtisanBuild\Till\Enums;

enum PlanTerms: string
{
    case Default = 'default';

    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
    case Life = 'life';

    case SeatPerWeek = 'seat-per-week';
    case SeatPerMonth = 'seat-per-month';
    case SeatPerYear = 'seat-per-year';
    case SeatForever = 'seat-forever';

}
