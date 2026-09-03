import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import CheckerBox from '@/components/checkers/checker-box';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { createAriaLabel, handleSquareClick } from '@/lib/board-client';
import { store } from '@/routes/games/moves';
import type { GameData, LegalMovesData, Position } from '@/types/game-data';

type GamePageProps = {
    game: GameData;
    legalMoves: LegalMovesData;
};

export default function Game({ game, legalMoves }: GamePageProps) {
    const [moveError, setMoveError] = useState<string | null>(null);
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [selectedSquare, setSelectedSquare] = useState<Position | null>(null);
    const active = game.status === 'active';

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
            router.post(
                store(game.id).url,
                {
                    from: clickedMove.from,
                    destination: clickedMove.destination,
                },
                {
                    onStart: () => {
                        setIsSaving(true);
                        setMoveError(null);
                    },

                    onSuccess: () => setSelectedSquare(null),

                    onError: (errors) => {
                        const firstError = Object.values(errors)[0];

                        setMoveError(
                            typeof firstError === 'string'
                                ? firstError
                                : 'The move was rejected.',
                        );
                    },

                    onHttpException: () => {
                        setMoveError(
                            'The server failed to save the move, try again.',
                        );

                        return false;
                    },

                    onNetworkError: () => {
                        setMoveError('Connection is lost: move was not saved.');

                        return false;
                    },

                    onFinish: () => setIsSaving(false),
                },
            );

            return;
        }

        setSelectedSquare((currentSelection) =>
            handleSquareClick(game, currentSelection, row, column),
        );
    }

    return (
        <>
            <Head title={`Game ${game.id}`} />
            <Button asChild className="mt-5 ml-5">
                <Link href="/">Go back home</Link>
            </Button>
            <div className="flex flex-col items-center px-4">
                <div className="flex flex-wrap gap-2">
                    <Badge className="bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                        Game ID: {game.id}
                    </Badge>
                    <Badge className="bg-sky-50 text-sky-700 dark:bg-sky-950 dark:text-sky-300">
                        Player Turn: {game.current_player}
                    </Badge>{' '}
                    <Badge className="bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300">
                        Game Status: {game.status}
                    </Badge>
                </div>
                {moveError && (
                    <Badge
                        className="my-5 w-full max-w-sm px-3 py-2 text-center text-lg leading-snug wrap-break-word whitespace-normal"
                        variant={'destructive'}
                    >
                        {moveError}
                    </Badge>
                )}
                <div className="relative mt-5 grid w-full max-w-164 grid-cols-10 gap-0.5">
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
                                color={
                                    square.color === 'dark' ? 'black' : 'light'
                                }
                                piece={square.piece}
                                ariaLabel={createAriaLabel(
                                    square.row,
                                    square.column,
                                    isSelected,
                                    square.piece?.color,
                                    square.piece?.isKing,
                                )}
                                selected={isSelected}
                                legal={isLegalDestination}
                                disabled={isSaving || !active}
                                onClick={() =>
                                    active &&
                                    handleBoxClick(square.row, square.column)
                                }
                            />
                        );
                    })}
                    {!active && (
                        <div className="absolute inset-x-4 top-1/2 -translate-y-1/2 bg-blue-500 p-5 text-center">
                            <p className={'text-xl font-bold'}>
                                winner: {game.winner}
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
