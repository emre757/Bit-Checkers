<?php

namespace App\Http\Controllers;

use App\Domain\Services\GameService;
use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;

final class GameController extends Controller
{

    public function __construct(
        private readonly GameService $gameService,
    )
    {
    }

    public function show(Game $game): Response
    {
        return Inertia::render('GamePage', [
            'game' => $game,
        ]);
    }

    // make new game & save to database
    public function store(): \Illuminate\Http\RedirectResponse
    {
        $game = $this->gameService->newGame();

        return to_route('games.show', $game);
    }
}
