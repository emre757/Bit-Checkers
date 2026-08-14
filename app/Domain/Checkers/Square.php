<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\ValueObjects\Position;
use LogicException;

final class Square
{
    public function __construct(
        private readonly Position $position,
        private readonly ColorType $color,
        private ?Piece $piece = null,
    ) {}

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function isOccupied(): bool
    {
        return $this->piece !== null;
    }

    public function isPlayable(): bool
    {
        // because pieces can stay on one square only anyways
        return $this->color == ColorType::Dark;
    }

    public function placePiece(Piece $piece): void
    {
        if (! $this->isPlayable()) {
            throw new LogicException(
                'A piece cannot be placed on a light square.'
            );
        }

        if ($this->isOccupied()) {
            throw new LogicException(
                'This square is already occupied.'
            );
        }

        $this->piece = $piece;
    }

    public function removePiece(): Piece
    {
        if ($this->piece === null) {
            throw new LogicException(
                'Cannot remove a piece from an empty square.'
            );
        }

        $piece = $this->piece;
        $this->piece = null;

        return $piece;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'row' => $this->position->row,
            'column' => $this->position->column,
            'color' => $this->color->value,
            'piece' => $this->piece?->toArray(),
        ];
    }
}
