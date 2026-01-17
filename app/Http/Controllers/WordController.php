<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WordController extends Controller
{
    public function find(WordService $wordService, string $word)
    {
        if (! $word) {
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

    public function import(Request $req, WordService $wordService)
    {
        $batchId = Cache::get('importBatchId');
        if (! $batchId) {
            $importUrl = $req->input('url');
            if (! filter_var($importUrl, FILTER_VALIDATE_URL)) {
                return response('Empty or invalid URL', 400);
            }

            if (Cache::get('imported')) {
                return response('Words have already been imported');
            }

            try {
                $res = Http::get($importUrl);
            } catch (Throwable $th) {
                return response($th->getMessage(), 400);
            }

            $words = explode("\n", $res->body());

            $maxJobsInBatch = env('QUEUE_WORKERS', 2);
            $jobs = $wordService->arrayToJobs($words, $maxJobsInBatch);

            try {
                $id = Bus::batch($jobs)->name('Import words')->onQueue('import')->dispatch()->id;
            } catch (Throwable $th) {
                return response($th->getMessage(), 500);
            }

            Cache::set('importBatchId', $id);

            return response('Importing...', 202);
        }

        $batch = Bus::findBatch($batchId);

        if ($batch->finished()) {
            Cache::set('imported', true);

            return response('Words have been imported!', 201);
        }

        return response('Importing progress: '.$batch->progress().'%');
    }
}
