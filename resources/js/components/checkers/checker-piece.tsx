import type { Piece } from '@/types/game-data';

type CheckerPieceProps = {
    piece: Piece;
};

export default function CheckerPiece({ piece }: CheckerPieceProps) {
    const colorClasses =
        piece.color === 'light'
            ? 'border-amber-300 bg-amber-50 text-amber-700'
            : 'border-red-950 bg-red-700 text-yellow-300';

    return (
        <span
            aria-hidden="true"
            className={`flex size-2/3 items-center justify-center rounded-full border-2 shadow-md sm:border-4 ${colorClasses} ${piece.pendingRemoval ? 'opacity-50' : ''}`}
        >
            {piece.isKing && (
                <span className="text-xs font-bold sm:text-xl">♛</span>
            )}
        </span>
    );
}
