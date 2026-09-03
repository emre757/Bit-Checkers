<?php

// turn database into game state (new game, load game, etc.)

namespace App\Mappers;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\GameState;
use App\Domain\Checkers\ValueObjects\Position;
use App\Models\Game;

final readonly class GameStateMapper
{
    public static function fromGame(Game $game): GameState
    {
        return new GameState(
            gameId: $game->id,
            turn: $game->current_player,
            status: $game->status,
            board: Board::fromArray($game->board),
            winner: $game->winner,
            forcedCaptureFrom: $game->forced_capture_from !== null
                ? Position::fromArray($game->forced_capture_from)
                : null,
        );
    }
}
