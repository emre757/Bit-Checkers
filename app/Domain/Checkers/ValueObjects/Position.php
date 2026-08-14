<?php

namespace App\Domain\Checkers\ValueObjects;

use App\Domain\Checkers\Board;

final readonly class Position
{
    public static function isWithinBounds(int $row, int $column): bool
    {
        return $row >= 0
            && $row < Board::SIZE
            && $column >= 0
            && $column < Board::SIZE;
    }

    public function __construct(
        public int $row,
        public int $column,
    ) {
        if (! self::isWithinBounds($row, $column)) {
            throw new \InvalidArgumentException(
                'Position is outside the board.'
            );
        }
    }

    // if the position is the same
    public function equals(self $other): bool
    {
        return $this->row === $other->row
            && $this->column === $other->column;
    }

    /**
     * @return array{row: int, column: int}
     */
    public function toArray(): array
    {
        return [
            'row' => $this->row,
            'column' => $this->column,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['row'], $data['column']);
    }
}
