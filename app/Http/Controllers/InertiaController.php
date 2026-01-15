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
        $isImported = Cache::get('imported', false);

        return Inertia::render('anagram/find', ['isImported' => $isImported]);
    }

    public function anagramImport()
    {
        $isImporting = Cache::get('importing', false);
        $isImported = Cache::get('imported', false);

        return Inertia::render('anagram/import', ['isImporting' => $isImporting, 'isImported' => $isImported]);
    }
}
