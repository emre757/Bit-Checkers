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
        public ?Position       $forcedCaptureFrom = null,
    )
    {
    }

    private function declareWinner(ColorType $winningPlayer): void
    {
        $this->winner = $winningPlayer;
        $this->status = BoardStatusType::Inactive;
    }

    /**
     * @return list<Move>
     */
    public function legalMoves(): array
    {
        if ($this->status === BoardStatusType::Inactive) {
            return [];
        }

        $forcedSquare = null;

        if ($this->forcedCaptureFrom !== null) {
            $forcedSquare = $this->board->getSquare($this->forcedCaptureFrom);
        }

        return $forcedSquare === null ? LegalMoveGenerator::generate(
            $this->board,
            $this->turn,
        ) : LegalMoveGenerator::captureMovesForSquare($this->board, $this->turn, $forcedSquare);
    }

    public function makeMove(Position $from, Position $destination): void
    {
        if ($this->status !== BoardStatusType::Active) {
            throw new \DomainException('Game status is not active');
        }

        /** @var Move|null $legalMove */
        $legalMove = null;

        foreach ($this->legalMoves() as $movePossibility) {
            if (!$movePossibility->matches($from, $destination)) {
                continue;
            }

            $legalMove = $movePossibility;
            break;
        }

        if ($legalMove === null) {
            throw new \DomainException('Invalid move');
        }

        // instead of removing, mark them for removal
        if ($legalMove->capture !== null) {
            $capturedPiece = $this->board
                ->getSquare($legalMove->capture)
                ->getPiece();

            // in case it fails somehow: prevent corruption
            if ($capturedPiece === null) {
                throw new \LogicException('Captured piece does not exist.');
            }

            $capturedPiece->markCaptured();
        }

        $endSquare = $this->board->getSquare($legalMove->destination);

        // removePiece returns piece so it can be used to place it on the new pos
        $piece = $this->board->getSquare($legalMove->from)->removePiece();
        $endSquare->placePiece($piece);

        if ($legalMove->capture !== null) {
            $forcedCaptures = LegalMoveGenerator::captureMovesForSquare($this->board, $this->turn, $endSquare);

            // skip switching turns
            if (!empty($forcedCaptures)) {
                $this->forcedCaptureFrom = $endSquare->getPosition();

                return;
            }
        }

        // only crown at end of turn
        if (!$piece->isKing() && $piece->getColor()->isPromotionRow($endSquare->getPosition()->row)) {
            $piece->crown();
        }

        // remove all captured pieces from board
        $this->board->removeCapturedPieces();

        // change turn if no capture available & reset forcedcapture
        $this->forcedCaptureFrom = null;
        $this->turn = $this->turn->opponent();

        if ($this->board->countPlayerPieces($this->turn) <= 0) {
            $this->declareWinner($this->turn->opponent());
        }

        // if opponent has any moves left, if not then player wins
        if ($this->legalMoves() === []) {
            $this->declareWinner($this->turn->opponent());
        }
    }
}
