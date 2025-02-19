<?php

namespace ArtisanBuild\Till\Enums;

enum LedgerPeriods: string
{
    case Minute = 'minute';
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
    case Life = 'life';
}
