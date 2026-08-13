<?php

namespace App\Domain\Checkers\ValueObjects;

final readonly class Move
{
    /**
     * @param non-empty-list<Position> $path
     */
    public function __construct(
        public Position  $from,
        public array     $path, // array of positions due to multi conquer
        public ?Position $capture, // array of pieces captured when performing
    )
    {
        if ($path === []) {
            throw new \InvalidArgumentException(
                'A move must have at least one destination.',
            );
        }
    }

    // last pos of $path
    public function destination(): Position
    {
        // cannot use end() because it modifies the readonly path array variable
        return $this->path[count($this->path) - 1];
    }

    // meant to validate client input; if it matches a valid move

    /**
     * @param list<Position> $path
     */
    public function matches(Position $from, array $path): bool
    {
        if (!$this->from->equals($from) || count($this->path) !== count($path)) {
            return false;
        }

        foreach ($path as $index => $position) {
            if (!$this->path[$index]->equals($position)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array{
     *     from: array{row: int, column: int},
     *     path: list<array{row: int, column: int}>,
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
            'path' => array_map(
                fn(Position $position): array => [
                    'row' => $position->row,
                    'column' => $position->column,
                ],
                $this->path,
            ),
            'capture' => $this->capture === null ? null : [
                'row' => $this->capture->row,
                'column' => $this->capture->column,
            ],
        ];
    }
}
