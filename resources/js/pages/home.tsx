import { Head, Link } from '@inertiajs/react';
import { SearchGameForm } from '@/components/checkers/search-game-form';

export default function Home() {
    return (
        <>
            <Head title={'Home'} />
            <main className="flex min-h-screen flex-col items-center justify-center gap-5">
                <h1 className={'fixed top-5 left-5 text-2xl font-bold'}>
                    Bit Checkers
                </h1>

                <div className="w-full max-w-xs">
                    <SearchGameForm />
                </div>

                <Link
                    href="/games"
                    method="post"
                    as="button"
                    type="button"
                    className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Create new game
                </Link>
            </main>
        </>
    );
}
