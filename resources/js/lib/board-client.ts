import type { GameData, Position } from '@/types/game-data';

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
