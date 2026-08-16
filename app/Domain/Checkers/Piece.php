<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;
use InvalidArgumentException;

final class Piece
{
    public function __construct(
        private readonly ColorType $color,
        private bool               $isKing,
    )
    {
        if (!in_array($color, [
            ColorType::Light,
            ColorType::Dark,
        ], true)) {
            throw new InvalidArgumentException('Invalid piece color.');
        }
    }

    public function getColor(): string
    {
        return $this->color->value;
    }

    public function pieceIsKing(): bool
    {
        return $this->isKing;
    }

    // make piece king
    public function crown(): void
    {
        $this->isKing = true;
    }

    /**
     * @return array<string, bool>
     */
    public function toArray(): array
    {
        return [
            'color' => $this->color,
            'isKing' => $this->isKing,
        ];
    }
}
