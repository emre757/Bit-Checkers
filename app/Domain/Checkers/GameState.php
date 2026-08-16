<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\Rules\LegalMoveGenerator;
use App\Domain\Checkers\ValueObjects\Move;

final class GameState
{
    public function __construct(
        public readonly int    $gameId,
        public ColorType       $turn,
        public BoardStatusType $status,
        public Board           $board,
        public ?ColorType      $winner = null,
    )
    {
    }

    /**
     * @return list<Move>
     */
    public function legalMoves(): array
    {
        return LegalMoveGenerator::generate(
            $this->board,
            $this->turn,
        );
    }

    /**
     * @param array{
     *     from: array{
     *         row: int,
     *         column: int
     *     },
     *     path: array{
     *         array<int, array{
     *            row: int,
     *            column: int
     *         }>
     *     }
     * } $data
     */
    public function makeMove(array $data)
    {
        //
    }
}
