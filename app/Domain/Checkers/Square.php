<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;
use LogicException;

final class Square
{
    public function __construct(
        private int       $row,
        private int       $column,
        private ColorType $color,
        private ?Piece    $piece = null,
    )
    {
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getColor(): string
    {
        return $this->color->value;
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
        if (!$this->isPlayable()) {
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
            'row' => $this->row,
            'column' => $this->column,
            'color' => $this->color,
            'piece' => $this->piece?->toArray(),
        ];
    }
}
