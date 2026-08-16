import CheckerLegalBox from '@/components/checkers/checker-legal-box';
import CheckerPiece from '@/components/checkers/checker-piece';
import type { Piece } from '@/types/game-data';

type CheckerBoxProps = {
    color: 'light' | 'black';
    piece: Piece | null;
    ariaLabel: string;
    selected?: boolean;
    legal?: boolean;
    disabled?: boolean;
    onClick?: () => void;
};

export default function CheckerBox({
    color,
    piece,
    ariaLabel,
    selected = false,
    legal = false,
    disabled = false,
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
            aria-label={ariaLabel}
            aria-pressed={selected}
            className={`flex aspect-square w-full min-w-0 items-center justify-center ${backgroundColor}`}
            disabled={disabled}
        >
            {piece && <CheckerPiece piece={piece} />}

            {/*don't show if there is a piece*/}
            {legal && !piece && <CheckerLegalBox />}
        </button>
    );
}
