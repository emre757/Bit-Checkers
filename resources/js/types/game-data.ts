export type GameData = {
    id: number;
    current_player: string;
    status: string;
    winner: string | null;
    board: unknown[];
    created_at: string;
    updated_at: string;
};
