# Bit Checkers

A local multiplayer game based on international checkers, built with Laravel, React, TypeScript, and Inertia.
The server validates all moves and automatically saves the game after every accepted move.

## Features

- Responsive 10×10 international checkers board
- Required captures, multi-captures, backward captures for pieces
- Server-side move validation, with legal destinations displayed in the front-end
- Automatic game saves and the ability to continue a game by ID
- Basic screen reader support through labels describing square coordinates, pieces, and selection state

## Tech

- PHP 8.3, Laravel 13, and MySQL
- React 19, TypeScript, Inertia, Tailwind CSS, and Vite
- Laravel Sail
- PHPUnit, PHPStan, Pint, ESLint, and Prettier for quality checks

## Architecture

Game rules are organized in domain classes under `app/Domain/Checkers`. The backend
deserializes the saved board into `GameState`, calculates legal moves on the server, validates the requested move, and
saves the state. The React client is responsible for interaction and presentation, but is not trusted to
decide whether a move is valid.

## Known limitations

The following are decisions made on purpose to deliver the project in a short time.

- **The maximum capture rule is not applied.** International rules require you to play the
  capture move that takes the most pieces. This game requires only that you *must* capture when a
  capture is available and lets you choose which capture to play.
- **There is no draw detection.** Draw conditions such as the king vs. king are not implemented, so a game
  between two kings can continue endlessly.
- **The game is local multiplayer only.** Both players share a single screen and take turns on the
  same device. There is no online play and no turn ownership.
- **Games are not protected by authentication.** Any visitor who knows a game ID can open that game
  and play a move in it. Game IDs are simple numbers, so they are guessable.
- **The game logic is not covered by automated tests.** The CI runs only basic tests such as lint checking. There are no
  automated tests for game logic.

## Requirements

- Docker desktop (WSL2 integration enabled)
- PHP 8.3+
- Composer

**Node.js and mySQL run inside laravel sail.**

## Installation

Install PHP dependencies, create `.env`, and generate the application key:

```bash
composer setup
```

Start docker and wait until everything is ready:

```bash
./vendor/bin/sail up
```

Database migration, npm dependencies & npm build with custom composer script:

```bash
composer setup:sail
```

Website should now be available on: http://localhost

### Starting the project after installation

```bash
./vendor/bin/sail up

./vendor/bin/sail npm run dev
```

### Stopping project

```bash
./vendor/bin/sail down
```
