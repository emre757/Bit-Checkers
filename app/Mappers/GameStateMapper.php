<?php

// turn database into game state (new game, load game etc)

namespace App\Mappers;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;
use App\Domain\Checkers\GameState;
use App\Models\Game;

final readonly class GameStateMapper
{
    public static function fromGame(Game $game): GameState
    {
        return new GameState(
            gameId: $game->id,
            turn: ColorType::from($game->current_player),
            status: BoardStatusType::from($game->status),
            board: Board::fromArray($game->board),
            winner: $game->winner !== null
                ? ColorType::from($game->winner)
                : null,
        );
    }
}
