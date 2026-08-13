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
         * @var list<Move> $normalMoves
         * @var list<Move> $captureMoves
         */
        $normalMoves = [];
        $captureMoves = [];

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

                    /** @var Position|null $capturePosition */
                    $capturePosition = null;

                    // check for any possible captures
                    if ($nextSquare->isOccupied()) {
                        $nextPiece = $nextSquare->getPiece();
                        if ($nextPiece === null || $nextPiece->getColor() === $currentPlayer) {
                            continue;
                        }

                        $landingRow = $nextRow + $rowStep;
                        $landingColumn = $nextColumn + $columnStep;

                        if (!Position::isWithinBounds($landingRow, $landingColumn)) {
                            continue;
                        }

                        $landingPosition = new Position(
                            row: $landingRow,
                            column: $landingColumn,
                        );

                        $landingSquare = $board->getSquare($landingPosition);

                        if ($landingSquare->isOccupied()) {
                            continue;
                        }

                        // first put next position to capture position as this is where enemy piece is
                        // then put landing/destination position as nextPosition so it can be used in path when creating move class
                        $capturePosition = $nextPosition;
                        $nextPosition = $landingPosition;
                    }

                    // if theres a capture available, put in capturemoves
                    if ($capturePosition !== null) {
                        $captureMoves[] = new Move(
                            from: $squarePosition,
                            path: [$nextPosition], // $nextPosition changes to landingPosition if there is a piece
                            capture: $capturePosition,
                        );

                        continue;
                    }

                    $normalMoves[] = new Move(
                        from: $squarePosition,
                        path: [$nextPosition], // $nextPosition changes to landingPosition if there is a piece
                        capture: $capturePosition,
                    );
                }
            }
        }

        // only return moves with capture if one exists
        return $captureMoves !== [] ? $captureMoves : $normalMoves;
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
