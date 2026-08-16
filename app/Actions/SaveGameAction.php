<?php

namespace App\Actions;

use App\Domain\Checkers\GameState;
use App\Models\Game;

use LogicException;

final class SaveGameAction
{
    public function execute(Game $game, GameState $gameState): Game
    {
        if ($game->id !== $gameState->gameId) {
            throw new LogicException('Game and game state do not match.');
        }

        $game->update([
            'current_player' => $gameState->turn->value,
            'status' => $gameState->status->value,
            'board' => $gameState->board->toArray(),
            'winner' => $gameState->winner?->value,
        ]);

        return $game;
    }
}
