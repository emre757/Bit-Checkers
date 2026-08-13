<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\Rules\LegalMoveGenerator;
use App\Domain\Checkers\ValueObjects\Move;
use App\Domain\Checkers\ValueObjects\Position;

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
     *     path: list<array{
     *         row: int,
     *         column: int
     *     }>
     * } $data
     */
    public function makeMove(array $data): void
    {
        if ($this->status != BoardStatusType::Active) {
            throw new \DomainException('Game status is not active');
        }

        $from = new Position(
            row: $data['from']['row'],
            column: $data['from']['column'],
        );

        $path = array_map(
            static fn(array $position) => new Position(
                row: $position['row'],
                column: $position['column'],
            ),
            $data['path']);

        /** @var Move|null $legalMove */
        $legalMove = null;

        foreach ($this->legalMoves() as $movePossibility) {
            if (!$movePossibility->matches($from, $path)) {
                continue;
            }

            $legalMove = $movePossibility;
            break;
        }

        if ($legalMove === null) {
            throw new \DomainException('Invalid move');
        }

        if ($legalMove->capture !== null) {
            $this->board->getSquare($legalMove->capture)->removePiece();
        }

        $endSquare = $this->board->getSquare($legalMove->destination());

        // removePiece returns piece so it can be used to place it on the new pos
        $piece = $this->board->getSquare($legalMove->from)->removePiece();
        $endSquare->placePiece($piece);

        // TODO: check if after legal move there is another capture possible so that player must do it (see legalmovegenerator)
        // TODO: check if player won after capture (no pieces left or all moves are blocked and cannot move)
        // change turn if no capture available
        $this->turn = $this->turn->opponent();
    }
}
