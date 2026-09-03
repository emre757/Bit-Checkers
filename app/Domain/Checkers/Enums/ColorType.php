<?php

namespace App\Domain\Checkers\Enums;

use App\Domain\Checkers\Board;

enum ColorType: string
{
    case Light = 'light';
    case Dark = 'dark';

    // get opponent color
    public function opponent(): self
    {
        return match ($this) {
            self::Light => self::Dark,
            self::Dark => self::Light,
        };
    }

    // get forward increment for column
    public function forwardRowStep(): int
    {
        return match ($this) {
            self::Light => -1,
            self::Dark => 1,
        };
    }

    public function isPromotionRow(int $row): bool
    {
        return $row === match ($this) {
                self::Light => 0,
                self::Dark => Board::SIZE - 1,
            };
    }
}
