import { useState } from 'react';
import CheckerBox from '@/components/checkers/checker-box';
import { handleSquareClick } from '@/lib/board-client';
import type { BoardPosition } from '@/lib/board-client';
import type { GameData } from '@/types/game-data';

type GamePageProps = {
    game: GameData;
};

export default function GamePage({ game }: GamePageProps) {
    const [selectedSquare, setSelectedSquare] = useState<BoardPosition | null>(
        null,
    );

    return (
        <div>
            <p>Game: {game.id}</p>
            <p>Current player: {game.current_player}</p>
            <p>Status: {game.status}</p>
            <div className="grid w-fit grid-cols-10 gap-0.5">
                {game.board.flat().map((square) => {
                    const isSelected =
                        selectedSquare?.row === square.row &&
                        selectedSquare.column === square.column;

                    return (
                        <CheckerBox
                            key={`${square.row}-${square.column}`}
                            color={square.color === 'dark' ? 'black' : 'light'}
                            piece={square.piece}
                            selected={isSelected}
                            onClick={() =>
                                setSelectedSquare((currentSelection) =>
                                    handleSquareClick(
                                        game,
                                        currentSelection,
                                        square.row,
                                        square.column,
                                    ),
                                )
                            }
                        />
                    );
                })}
            </div>
        </div>
    );
}
