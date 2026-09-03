<?php

namespace App\Http\Controllers;

use App\Actions\SaveGameAction;
use App\Domain\Checkers\Rules\LegalMoveGenerator;
use App\Domain\Services\GameService;
use App\Http\Requests\MovePieceRequest;
use App\Mappers\GameStateMapper;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class GameController extends Controller
{
    public function __construct(
        private readonly GameService    $gameService,
        private readonly SaveGameAction $saveGameAction,
    )
    {
    }

    public function show(Game $game): Response
    {
        $gameState = GameStateMapper::fromGame($game);

        return Inertia::render('game', [
            'game' => $game,
            'legalMoves' => LegalMoveGenerator::toArray($gameState->legalMoves()),
        ]);
    }

    // make new game & save to database
    public function store(): RedirectResponse
    {
        $game = $this->gameService->newGame();

        return to_route('games.show', $game);
    }

    public function move(MovePieceRequest $request, Game $game): RedirectResponse
    {
        $gameState = GameStateMapper::fromGame($game);

        try {
            $gameState->makeMove($request->fromPosition(), $request->destinationPosition());
        } catch (\DomainException $exception) {
            return back()->withErrors(['move' => $exception->getMessage()]);
        }

        $this->saveGameAction->execute($game, $gameState);

        return to_route('games.show', $game);
    }

    public function find(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'gameId' => ['required', 'integer', 'exists:games,id'],
            ],
            [
                'gameId.exists' => 'No game was found with that ID.',
            ],
        );

        return to_route('games.show', $validated['gameId']);
    }
}
