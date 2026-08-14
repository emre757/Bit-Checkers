<?php

namespace App\Domain\Checkers\ValueObjects;

final readonly class Move
{
    public function __construct(
        public Position $from,
        public Position $destination, // array of positions due to multi conquer
        public ?Position $capture, // array of pieces captured when performing
    ) {}

    // meant to validate client input; if it matches a valid move
    public function matches(Position $from, Position $destination): bool
    {
        if (! $this->from->equals($from) || ! $this->destination->equals($destination)) {
            return false;
        }

        return true;
    }

    /**
     * @return array{
     *     from: array{row: int, column: int},
     *     destination: array{row: int, column: int},
     *     capture: array{row: int, column: int}|null,
     * }
     */
    public function toArray(): array
    {
        return [
            'from' => [
                'row' => $this->from->row,
                'column' => $this->from->column,
            ],
            'destination' => ['row' => $this->destination->row,
                'column' => $this->destination->column, ],
            'capture' => $this->capture === null ? null : [
                'row' => $this->capture->row,
                'column' => $this->capture->column,
            ],
        ];
    }
}
