<?php

namespace App\Domain\Checkers\Enums;

enum ColorType: string
{
    case Light = 'light';
    case Dark = 'dark';

    public function opponent(): self
    {
        return match ($this) {
            self::Light => self::Dark,
            self::Dark => self::Light,
        };
    }
}
