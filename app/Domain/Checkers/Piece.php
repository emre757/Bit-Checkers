<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;
use InvalidArgumentException;

final class Piece
{
    // private props because it shouldnt be changed by outside codes
    public function __construct(
        private readonly ColorType $color,
        private bool $isKing,
    ) {
        if (! in_array($color, [
            ColorType::Light,
            ColorType::Dark,
        ], true)) {
            throw new InvalidArgumentException('Invalid piece color.');
        }
    }

    public function getColor(): ColorType
    {
        return $this->color;
    }

    public function isKing(): bool
    {
        return $this->isKing;
    }

    // make piece king
    public function crown(): void
    {
        $this->isKing = true;
    }

    /**
     * @return array{
     *     color: string,
     *     isKing: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'color' => $this->color->value,
            'isKing' => $this->isKing,
        ];
    }
}
