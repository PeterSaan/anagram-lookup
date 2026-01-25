<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class InertiaController extends Controller
{
    public function home()
    {
        return Inertia::render('home');
    }

    public function anagramFind()
    {
        return Inertia::render('anagram/find');
    }

    public function anagramImport()
    {
        return Inertia::render('anagram/import');
    }

    public function docsApi()
    {
        return Inertia::render('docs/api');
    }
}
