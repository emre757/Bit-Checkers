<?php

namespace App\Domain\Services;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\Piece;
use App\Domain\Checkers\Square;
use App\Domain\Checkers\ValueObjects\Position;

final class BoardService
{
    public function createBoard(): Board
    {
        $squares = [];

        for ($row = 0; $row < Board::SIZE; $row++) {
            for ($column = 0; $column < Board::SIZE; $column++) {
                $squareColor = $this->determineSquareColor($row, $column);

                $piece = $this->createStartingPiece($row, $squareColor);

                $squares[$row][$column] = new Square(
                    position: new Position($row, $column),
                    color: $squareColor,
                    piece: $piece,
                );
            }
        }

        return new Board($squares);
    }

    private function determineSquareColor(
        int $row,
        int $column,
    ): ColorType {
        return ($row + $column) % 2 === 1
            ? ColorType::Dark
            : ColorType::Light;
    }

    private function createStartingPiece(
        int $row,
        ColorType $squareColor,
    ): ?Piece {
        if ($squareColor !== ColorType::Dark) {
            return null;
        }

        if ($row <= 3) {
            return new Piece(ColorType::Dark, false);
        }

        if ($row >= 6) {
            return new Piece(ColorType::Light, false);
        }

        return null;
    }
}
