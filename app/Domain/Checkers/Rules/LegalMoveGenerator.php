<?php

namespace App\Domain\Checkers\Rules;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\ValueObjects\Move;
use App\Domain\Checkers\ValueObjects\Position;

final readonly class LegalMoveGenerator
{
    /**
     * @return list<Move>
     */
    public static function generate(Board $board, ColorType $currentPlayer): array
    {
        $squares = $board->getSquares();

        if (empty($squares)) {
            return [];
        }

        /**
         * @var list<Move> $moves
         */
        $moves = [];

        foreach ($squares as $row) {
            foreach ($row as $square) {
                $squarePiece = $square->getPiece();

                if (!$squarePiece || $squarePiece->getColor() !== $currentPlayer) {
                    continue;
                }

                $squarePosition = $square->getPosition();
                $rowStep = $currentPlayer->forwardRowStep();

                // foreach -1, 1 because these are the 2 directions (left & right), so create & loop 2 possibilities
                foreach ([-1, 1] as $columnStep) {
                    $nextRow = $squarePosition->row + $rowStep;
                    $nextColumn = $squarePosition->column + $columnStep;

                    // validate position
                    if (!Position::isWithinBounds($nextRow, $nextColumn)) {
                        continue;
                    }

                    $nextPosition = new Position($nextRow, $nextColumn);
                    $nextSquare = $board->getSquare($nextPosition);

                    // TODO: check if piece can be captured or not
                    if ($nextSquare->isOccupied()) {
                        continue;
                    }

                    $moves[] = new Move(
                        from: $squarePosition,
                        path: [$nextPosition],
                        captures: [],
                    );
                }
            }
        }

        return $moves;
    }

    /**
     * @param list<Move> $moves
     * @return array
     */
    public static function toArray(array $moves): array
    {
        return collect($moves)->map(fn($move) => $move->toArray())->all();
    }
}
