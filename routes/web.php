<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/games', [GameController::class, 'find'])
    ->name('games.find');

Route::get('/games/{game}', [GameController::class, 'show'])
    ->name('games.show');

Route::post('/games', [GameController::class, 'store'])
    ->name('games.store');

// Normally would use a controller like GameMoveController
// Due to project being small, will be using game controller as it would otherwise make it more complicated than needed
Route::post('/games/{game}/moves', [GameController::class, 'move'])
    ->name('games.moves.store');
