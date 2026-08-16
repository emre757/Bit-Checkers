import { Link } from '@inertiajs/react';

export default function Home() {
    return (
        <>
            <h1>Bit Checkers Home</h1>
            <Link href="/games" method="post" as="button">
                <button
                    type="button"
                    className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
                >
                    Create new game
                </button>
            </Link>
        </>
    );
}
