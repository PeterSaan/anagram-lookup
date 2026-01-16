<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

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

    public function import(WordService $wordService, Request $req)
    {
        $importUrl = $req->input('url');
        if (! filter_var($importUrl, FILTER_VALIDATE_URL)) {
            return response('Empty or invalid URL', 400);
        }

        if (Cache::get('imported')) {
            return response('Words have already been imported', 200);
        }

        try {
            $res = Http::get($importUrl);
        } catch (Throwable $th) {
            return response($th->getMessage(), 400);
        }

        $words = explode("\n", $res->body());

        Cache::set('importing', true);
        $wordService->importToDb($words);
        Cache::delete('importing');

        Cache::set('imported', true);

        return response('Words have been imported sucessfully!', 201);
    }
}
