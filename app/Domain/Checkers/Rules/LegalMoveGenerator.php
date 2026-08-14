<?php

namespace App\Domain\Checkers\Rules;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\Square;
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
         */
        $normalMoves = [];

        /** @var list<Move> $captureMoves */
        $captureMoves = [];

        foreach ($squares as $row) {
            foreach ($row as $square) {
                $moves = self::generateSquareMoves($board, $square, $currentPlayer);

                foreach ($moves as $move) {
                    if ($move->capture !== null) {
                        $captureMoves[] = $move;

                        continue;
                    }

                    $normalMoves[] = $move;
                }
            }
        }

        // only return moves with capture if one exists
        return $captureMoves !== [] ? $captureMoves : $normalMoves;
    }

    /**
     * @return list<Move>
     */
    private static function generateSquareMoves(Board $board, Square $square, ColorType $currentPlayer): array
    {
        $squarePiece = $square->getPiece();

        if (! $squarePiece || $squarePiece->getColor() !== $currentPlayer) {
            return [];
        }

        return $squarePiece->isKing() === true ?
            self::generateKingMoves($board, $square, $currentPlayer) :
            self::generateManMoves($board, $square, $currentPlayer);
    }

    /**
     * @return list<Move>
     */
    private static function generateKingMoves(Board $board, Square $square, ColorType $currentPlayer): array
    {
        $squarePosition = $square->getPosition();

        $moves = [];

        // 4 possible diagonal directions
        foreach ([[-1, 1], [1, -1], [1, 1], [-1, -1]] as [$rowStep, $columnStep]) {
            $nextRow = $squarePosition->row + $rowStep;
            $nextColumn = $squarePosition->column + $columnStep;

            // must be outside loop otherwise generate will see all moves beyond capture as normal
            /** @var Position|null $capturePosition */
            $capturePosition = null;

            while (Position::isWithinBounds($nextRow, $nextColumn)) {
                $nextPosition = new Position($nextRow, $nextColumn);
                $nextSquare = $board->getSquare($nextPosition);

                // check for any possible captures
                if ($nextSquare->isOccupied()) {
                    $nextPiece = $nextSquare->getPiece();

                    // if captureposition already exists then stop so player cannot eat twice in one request cycle
                    if ($capturePosition !== null || $nextPiece === null || $nextPiece->getColor() === $currentPlayer) {
                        break;
                    }

                    $landingRow = $nextRow + $rowStep;
                    $landingColumn = $nextColumn + $columnStep;

                    if (! Position::isWithinBounds($landingRow, $landingColumn)) {
                        break;
                    }

                    $landingPosition = new Position(
                        row: $landingRow,
                        column: $landingColumn,
                    );

                    $landingSquare = $board->getSquare($landingPosition);

                    if ($landingSquare->isOccupied()) {
                        break;
                    }

                    // first put next position to capture position as this is where enemy piece is
                    $capturePosition = $nextPosition;
                } else {
                    // if theres a capture available, put in capturemoves
                    $moves[] = new Move(
                        from: $squarePosition,
                        destination: $nextPosition, // $nextPosition changes to landingPosition if there is a piece
                        capture: $capturePosition,
                    );
                }

                $nextRow += $rowStep;
                $nextColumn += $columnStep;
            }
        }

        return $moves;
    }

    // Function should not be used freely, use gamestate legalmoves() instead

    /**
     * @return list<Move>
     */
    private static function generateManMoves(Board $board, Square $square, ColorType $currentPlayer): array
    {
        $squarePosition = $square->getPosition();
        $rowForwardStep = $currentPlayer->forwardRowStep();

        $moves = [];

        // loop because it will check row behind piece to check if capturable, if not then it won't be a legal move
        foreach ([-1, 1] as $rowStep) {
            // foreach -1, 1 because these are the 2 directions (left & right), so create & loop 2 possibilities
            foreach ([-1, 1] as $columnStep) {
                $nextRow = $squarePosition->row + $rowStep;
                $nextColumn = $squarePosition->column + $columnStep;

                // validate position
                if (! Position::isWithinBounds($nextRow, $nextColumn)) {
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

                    if (! Position::isWithinBounds($landingRow, $landingColumn)) {
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

                if ($capturePosition === null && $rowStep != $rowForwardStep) {
                    continue;
                }

                // if theres a capture available, put in capturemoves
                $moves[] = new Move(
                    from: $squarePosition,
                    destination: $nextPosition, // $nextPosition changes to landingPosition if there is a piece
                    capture: $capturePosition,
                );
            }
        }

        return $moves;
    }

    /** @return list<Move> */
    public static function captureMovesForSquare(Board $board, ColorType $currentPlayer, Square $square): array
    {
        $moves = self::generateSquareMoves($board, $square, $currentPlayer);
        $captureMoves = [];

        foreach ($moves as $move) {
            if ($move->capture !== null) {
                $captureMoves[] = $move;
            }
        }

        return $captureMoves;
    }

    /**
     * @param  list<Move>  $moves
     * @return array<int, array{
     * from: array{row: int, column: int},
     * destination: array{row: int, column: int},
     * capture: array{row: int, column: int}|null,
     * }>
     */
    public static function toArray(array $moves): array
    {
        return collect($moves)->map(fn ($move) => $move->toArray())->all();
    }
}
