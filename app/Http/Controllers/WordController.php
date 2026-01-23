<?php

namespace App\Http\Controllers;

use App\Interfaces\IWordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;
use Throwable;

class WordController extends Controller
{
    public function __construct(public IWordService $wordService) {}

    #[OA\Get(
        path: '/api/find-anagrams/{word}',
        summary: 'Get array of anagrams for the given word',
        tags: ['word'],
        parameters: [
            new OA\PathParameter(
                name: 'word',
                description: 'Word to search anagrams for',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Found anagrams from the cache',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    example: ['anagram', 'nagaram', 'managra']
                ),
            ),
            new OA\Response(
                response: 201,
                description: 'Found anagrams and cached them',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    example: ['anagram', 'nagaram', 'managra']
                ),
            ),
            new OA\Response(
                response: 204,
                description: 'Did not find anagrams for given word',
            ),
            new OA\Response(
                response: 400,
                description: '`word` is empty',
            ),
        ]
    )]
    public function find(string $word)
    {
        if (! trim($word)) {
            return response(status: 400);
        }

        $anagramsFromCache = Cache::get($word, '');
        if ($anagramsFromCache != '') {
            return response($anagramsFromCache, 200);
        }

        $anagrams = $this->wordService->findAnagrams($word);

        $resCode = 204;
        if (! empty($anagrams)) {
            $resCode = 201;
            Cache::set($word, $anagrams);
        }

        return response($anagrams, $resCode);
    }

    #[OA\Post(
        path: '/api/import-words',
        summary: 'Import words separated by newlines from a given URL',
        tags: ['word'],
        parameters: [
            new OA\RequestBody(
                request: 'url',
                description: 'Object with a single key `url` that holds the full URL. A GET request is made to the provided path',
                required: true,
                content: new OA\JsonContent(example: ['url' => 'https://opus.ee/lemmad2013.txt']),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Words have already imported from some URL',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'Words have already been imported'
                ),
            ),
            new OA\Response(
                response: 202,
                description: 'Import jobs passed to the server, batch id returned',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'b690bd00-819d-4d18-9151-25ed11b96b83'
                ),
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid URL or HTTP client had problems fetching the requested URL',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'Empty or invalid URL'
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Array of strings parsed from URL is empty',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'No words'
                ),
            ),
            new OA\Response(
                response: 500,
                description: 'Error creating a batch of jobs',
                content: new OA\MediaType(mediaType: 'text/html'),
            ),
        ]
    )]
    public function import(Request $req)
    {
        if (Cache::get('imported')) {
            return response('Words have already been imported');
        }

        $importUrl = $req->input('url');
        if (! filter_var($importUrl, FILTER_VALIDATE_URL)) {
            return response('Empty or invalid URL', 400);
        }

        try {
            $res = Http::get($importUrl);
        } catch (Throwable $th) {
            return response($th->getMessage(), 400);
        }

        $words = explode("\n", $res->body());
        if (! isset($words)) {
            return response('No words', 404);
        }

        $jobs = $this->wordService->wordArrayToJobs($words);

        try {
            $batchId = Bus::batch($jobs)
                ->then(function () {
                    Cache::set('imported', true);
                })
                ->name('Import words')
                ->onQueue('import')
                ->dispatch()
                ->id;
        } catch (Throwable $th) {
            return response($th->getMessage(), 500);
        }

        return response($batchId, 202);
    }
}
