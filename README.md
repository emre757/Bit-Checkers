# Bit Checkers

The game automatically saves after every validated move, so players do not need to manually save and cannot lose
progress by accident.

The game has basic screen reader support:
board squares use aria labels to describe coordinates, piece type, and selection state.

# Docker

Use laravel sail for local development.

1. **Start the Docker containers:**

```bash
sail up -d
```

**Start the Vite development server:**

```bash
sail npm run dev
```

**The application runs at:**

http://localhost (port 80)

**To stop the containers:**

sail down
