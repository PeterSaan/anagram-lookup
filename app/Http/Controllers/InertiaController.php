<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
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
        $isImported = Cache::get('imported', false);

        return Inertia::render('anagram/import', ['isImported' => $isImported]);
    }
}
