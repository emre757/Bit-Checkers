<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;

final class Piece
{
    // private props because it shouldnt be changed by outside codes
    public function __construct(
        private readonly ColorType $color,
        private bool               $isKing,
        private bool               $pendingRemoval = false,
    )
    {
    }

    public function markCaptured(): void
    {
        $this->pendingRemoval = true;
    }

    public function isCaptured(): bool
    {
        return $this->pendingRemoval;
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
     *     isKing: bool,
     *     pendingRemoval: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'color' => $this->color->value,
            'isKing' => $this->isKing,
            'pendingRemoval' => $this->pendingRemoval,
        ];
    }
}
