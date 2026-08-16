<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\ColorType;
use InvalidArgumentException;

final class Board
{
    public const int SIZE = 10;

    /**
     * @param array<int, array<int, Square>> $squares
     */
    public function __construct(
        private array $squares,
    )
    {
    }

    // create new board class from existing square array
    // static: can be called without creating new object
    public static function fromArray(array $data): self
    {
        if (count($data) !== self::SIZE) {
            throw new InvalidArgumentException(
                'The board must have exactly 10 rows.'
            );
        }

        $squares = [];

        foreach ($data as $rowData) {
            if (count($rowData) !== self::SIZE) {
                throw new InvalidArgumentException(
                    'Every board row must have exactly 10 squares.'
                );
            }

            foreach ($rowData as $squareData) {
                $row = (int)$squareData['row'];
                $column = (int)$squareData['column'];

                $piece = null;
                $pieceData = $squareData['piece'] ?? null;

                if ($pieceData !== null) {
                    $piece = new Piece(
                        color: ColorType::from($pieceData['color']),
                        isKing: (bool)$pieceData['isKing'],
                    );
                }

                $squares[$row][$column] = new Square(
                    row: $row,
                    column: $column,
                    color: ColorType::from($squareData['color']),
                    piece: $piece,
                );
            }
        }

        return new self($squares);
    }

    public function getSquare(int $row, int $column): Square
    {
        if (!isset($this->squares[$row][$column])) {
            throw new InvalidArgumentException(
                "Square {$row}, {$column} does not exist."
            );
        }

        return $this->squares[$row][$column];
    }

    /**
     * @return array<int, array<int, Square>>
     */
    public function getSquares(): array
    {
        return $this->squares;
    }

    /**
     * @return array<int, array<int, array<string, mixed>>>
     */
    // convert square objects to array
    public function toArray(): array
    {
        return array_map(
            static fn(array $row): array => array_map(
                static fn(Square $square): array => $square->toArray(),
                $row,
            ),
            $this->squares,
        );
    }
}
