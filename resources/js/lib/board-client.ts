import type { Color, GameData, Position } from '@/types/game-data';

function validateSelection(
    game: GameData,
    row: number,
    column: number,
): boolean {
    if (game.status !== 'active') {
        return false;
    }

    const piece = game.board[row]?.[column]?.piece;

    return piece?.color === game.current_player;
}

export function handleSquareClick(
    game: GameData,
    selectedSquare: Position | null,
    row: number,
    column: number,
): Position | null {
    const clickedSelectedSquare =
        selectedSquare?.row === row && selectedSquare.column === column;

    if (clickedSelectedSquare) {
        return null;
    }

    if (!validateSelection(game, row, column)) {
        return null;
    }

    return { row, column };
}

function squareCoordinate(row: number, column: number): string {
    const letter = String.fromCharCode(65 + column); // A–J
    const rank = 10 - row; // so that bottom row is 1 and not 0

    return `${letter}${rank}`;
}

// for screen readers
// no piece type as parameter on purpose (in case it needs to be called without it)
export function createAriaLabel(
    row: number,
    column: number,
    selected: boolean,
    pieceColor?: Color,
    isKing?: boolean,
) {
    const coordinate = squareCoordinate(row, column);
    const pieceLabel = pieceColor
        ? `${pieceColor} ${isKing ? 'king' : 'piece'}`
        : 'empty square';

    return [coordinate, pieceLabel, selected ? 'selected' : null]
        .filter((part): part is string => part !== null)
        .join(', ');
}
