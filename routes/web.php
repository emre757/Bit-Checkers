<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/games/{game}', [GameController::class, 'show'])
    ->name('games.show');

Route::post('/games', [GameController::class, 'store'])
    ->name('games.store');

require __DIR__ . '/settings.php';
