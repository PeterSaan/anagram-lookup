<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WordController extends Controller
{
    public function import(WordService $wordService)
    {
        if (Cache::get('imported', false)) {
            return response('Words have already been imported', 200);
        }

        $res = Http::get('https://opus.ee/lemmad2013.txt');
        $words = explode("\n", $res->body());

        $isImported = $wordService->importToDb($words);

        if (! $isImported) {
            return response('Problem with importing the dataset', 500);
        }

        Cache::set('imported', true);

        return response('Data has been imported sucessfully', 201);
    }
}
