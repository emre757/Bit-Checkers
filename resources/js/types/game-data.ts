export type Color = 'light' | 'dark';

export type BoardStatus = 'active' | 'inactive';

export type Piece = {
    color: Color;
    isKing: boolean;
    pendingRemoval: boolean;
};

export type Square = {
    row: number;
    column: number;
    color: Color;
    piece: Piece | null;
};

export type Board = Square[][];

export type Position = {
    row: number;
    column: number;
};

export type LegalMove = {
    from: Position;
    destination: Position;
    capture: Position | null;
};

export type LegalMovesData = LegalMove[];

export type GameData = {
    id: number;
    current_player: Color;
    status: BoardStatus;
    winner: Color | null;
    board: Board;
    created_at: string;
    updated_at: string;
};
