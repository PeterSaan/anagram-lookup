<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WordController extends Controller
{
    public function find(WordService $wordService, string $word)
    {
        $word = trim($word);
        if (empty($word)) {
            return response([], 400);
        }

        $anagramsFromCache = Cache::get($word, '');
        if ($anagramsFromCache != '') {
            return response($anagramsFromCache, 200);
        }

        $anagrams = $wordService->findAnagrams($word);

        $resCode = 204;
        if (! empty($anagrams)) {
            $resCode = 201;
            Cache::set($word, $anagrams);
        }

        return response($anagrams, $resCode);
    }

    public function import(WordService $wordService)
    {
        if (Cache::get('imported', false)) {
            return response('Words have already been imported', 200);
        }

        Cache::set('importing', true);

        $res = Http::get('https://opus.ee/lemmad2013.txt');
        $words = explode("\n", $res->body());

        $isImported = $wordService->importToDb($words);
        if (! $isImported) {
            Cache::delete('importing');

            return response('Problem with importing the words', 500);
        }

        Cache::delete('importing');
        Cache::set('imported', true);

        return response('Words have been imported sucessfully', 201);
    }
}
