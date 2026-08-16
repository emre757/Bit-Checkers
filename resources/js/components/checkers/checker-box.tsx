import CheckerPiece from '@/components/checkers/checker-piece';
import type { Piece } from '@/types/game-data';

type CheckerBoxProps = {
    color: 'light' | 'black';
    piece: Piece | null;
    selected?: boolean;
    onClick?: () => void;
};

export default function CheckerBox({
    color,
    piece,
    selected = false,
    onClick,
}: CheckerBoxProps) {
    const backgroundColor = selected
        ? 'bg-blue-500'
        : color === 'light'
          ? 'bg-amber-100'
          : 'bg-black';

    return (
        <button
            type="button"
            onClick={onClick}
            aria-pressed={selected}
            className={`flex size-16 items-center justify-center ${backgroundColor}`}
        >
            {piece && <CheckerPiece piece={piece} />}
        </button>
    );
}
