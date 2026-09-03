# Bit Checkers

The game automatically saves after every validated move, so players do not need to manually save and cannot lose
progress by accident.

The game has basic screen reader support:
board squares use aria labels to describe coordinates, piece type, and selection state.

### Known limitations

The following are decisions made on purpose to deliver the project in a short
time.

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

### Requirements

- Docker desktop (WSL2 integration enabled)
- PHP 8.3+
- Composer

**Node.js and mySQL run inside laravel sail.**

# Installation

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

Website should now be active at: http://localhost (port 80)

#### Starting project in future after installation

```bash
./vendor/bin/sail up

./vendor/bin/sail npm run dev
```

### Stopping project

```bash
./vendor/bin/sail down
```
