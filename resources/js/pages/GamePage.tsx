import type { GameData } from '@/types/game-data';

type GamePageProps = {
    game: GameData;
};

export default function GamePage({ game }: GamePageProps) {
    return (
        <div>
            <p>Game: {game.id}</p>
            <p>Current player: {game.current_player}</p>
            <p>Status: {game.status}</p>
        </div>
    );
}
