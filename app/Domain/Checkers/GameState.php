<?php

namespace App\Domain\Checkers;

use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;

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
}
