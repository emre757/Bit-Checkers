<?php

namespace App\Domain\Services;

use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;
use App\Models\Game;

final class GameService
{
    public function __construct(
        private readonly BoardService $boardService,
    ) {}

    public function newGame(): Game
    {
        $board = $this->boardService->createBoard();

        return Game::create([
            'current_player' => ColorType::Light->value,
            'status' => BoardStatusType::Active->value,
            'winner' => null,
            'board' => $board->toArray(),
        ]);
    }
}
