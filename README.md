# Bit Checkers

The game automatically saves after every validated move, so players do not need to manually save and cannot lose
progress by accident.

The game has basic screen reader support:
board squares use aria labels to describe coordinates, piece type, and selection state.

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
