import { Head, Link } from '@inertiajs/react';

export default function Welcome() {
  return (
    <>
      <Head title="Home" />
      <div className="flex min-h-screen bg-slate-800">
        <div className="inline-flex w-full items-center justify-center gap-20 text-4xl font-semibold text-gray-100">
          <Link
            href={'/anagram/import'}
            className="cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
            viewTransition
          >
            Import words
          </Link>
          <Link
            href={'/anagram/find'}
            className="cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
            viewTransition
          >
            Find anagram
          </Link>
        </div>
      </div>
    </>
  );
}
