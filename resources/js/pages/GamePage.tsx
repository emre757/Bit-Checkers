import { router } from '@inertiajs/react';
import { useState } from 'react';
import CheckerBox from '@/components/checkers/checker-box';
import { handleSquareClick } from '@/lib/board-client';
import type { GameData, LegalMovesData, Position } from '@/types/game-data';

type GamePageProps = {
    game: GameData;
    legalMoves: LegalMovesData;
};

export default function GamePage({ game, legalMoves }: GamePageProps) {
    const [selectedSquare, setSelectedSquare] = useState<Position | null>(null);

    // show potential moves for the selected square
    const legalDestinations: Position[] = selectedSquare
        ? legalMoves
              .filter(
                  (move) =>
                      move.from.row === selectedSquare.row &&
                      move.from.column === selectedSquare.column,
              )
              .map((move) => move.destination)
              .filter(
                  (position): position is Position => position !== undefined,
              )
        : [];

    function handleBoxClick(row: number, column: number) {
        const clickedMove = selectedSquare
            ? legalMoves.find((move) => {
                  const destination = move.destination;

                  const startsAtSelectedSquare =
                      move.from.row === selectedSquare.row &&
                      move.from.column === selectedSquare.column;

                  const endsAtClickedSquare =
                      destination?.row === row && destination.column === column;

                  return startsAtSelectedSquare && endsAtClickedSquare;
              })
            : undefined;

        if (clickedMove) {
            console.log('clicked move:', clickedMove);
            router.post(
                `/games/${game.id}/moves`,
                {
                    from: clickedMove.from,
                    destination: clickedMove.destination,
                },
                {
                    onSuccess: () => setSelectedSquare(null),
                },
            );

            return;
        }

        setSelectedSquare((currentSelection) =>
            handleSquareClick(game, currentSelection, row, column),
        );
    }

    return (
        <div className={'flex flex-col items-center'}>
            <p>Game: {game.id}</p>
            <p>Current player: {game.current_player}</p>
            <p>Status: {game.status}</p>
            <div className="mt-5 grid w-fit grid-cols-10 gap-0.5">
                {game.board.flat().map((square) => {
                    const isSelected =
                        selectedSquare?.row === square.row &&
                        selectedSquare.column === square.column;

                    const isLegalDestination = legalDestinations.some(
                        (position) =>
                            position.row === square.row &&
                            position.column === square.column,
                    );

                    return (
                        <CheckerBox
                            key={`${square.row}-${square.column}`}
                            color={square.color === 'dark' ? 'black' : 'light'}
                            piece={square.piece}
                            selected={isSelected}
                            legal={isLegalDestination}
                            onClick={() =>
                                handleBoxClick(square.row, square.column)
                            }
                        />
                    );
                })}
            </div>
        </div>
    );
}
