<?php

namespace ArtisanBuild\Till\Enums;

enum Currencies: string
{
    case USD = '$';
    case EUR = '€';
    case GBP = '£';
    case JPY = '¥';
    case AUD = 'A$';
    case CAD = 'C$';
    case CHF = 'CHF';
    case CNY = 'CNY';
    case SEK = 'kr';
    case NZD = 'NZ$';

    public function format(int|float|null $amount): string
    {
        if ((int) $amount === 0) {
            return 'Free';
        }

        return match ($this) {
            self::JPY, self::CNY => $this->value.number_format($amount, 0),
            self::SEK => number_format($amount, is_float($amount) ? 2 : 0).' '.$this->value,
            default => $this->value.number_format($amount, is_float($amount) ? 2 : 0),
        };
    }
}
