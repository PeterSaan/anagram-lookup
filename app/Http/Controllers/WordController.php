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
                description: 'URL is already imported',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
                    example: 'That source is already imported'
                ),
            ),
            new OA\Response(
                response: 202,
                description: 'Import jobs passed to the server, batch id returned',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
                    example: 'b690bd00-819d-4d18-9151-25ed11b96b83'
                ),
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid URL or HTTP client had problems fetching the requested URL',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
                    example: 'Empty or invalid URL'
                ),
            ),
            new OA\Response(
                response: 403,
                description: 'Return forbidden if requested URL response does not have `Content-Type: text/plain` header',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
                    example: 'Only plain text files'
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Array of strings parsed from URL is empty',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
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
        $importUrl = $req->input('url');
        if (! filter_var($importUrl, FILTER_VALIDATE_URL)) {
            return response('Empty or invalid URL', 400)->header('Content-Type', 'text/plain');
        }

        $importPaths = Cache::get('importPaths', []);
        echo in_array($importUrl, $importPaths);
        if (in_array($importUrl, $importPaths)) {
            return response('That source is already imported', 200)->header('Content-Type', 'text/plain');
        }

        try {
            $res = Http::get($importUrl);
        } catch (Throwable $th) {
            return response($th->getMessage(), 400)->header('Content-Type', 'text/plain');
        }

        if (! str_contains($res->header('Content-Type'), 'text/plain')) {
            return response('You can only import plain text files', 403)->header('Content-Type', 'text/plain');
        }

        $words = explode("\n", $res->body());
        if (! isset($words)) {
            return response('No words', 404)->header('Content-Type', 'text/plain');
        }

        array_push($importPaths, $importUrl);

        $jobs = $this->wordService->wordArrayToJobs($words);

        try {
            $batchId = Bus::batch($jobs)
                ->name('Import words')
                ->onQueue('import')
                ->dispatch()
                ->id;
        } catch (Throwable $th) {
            return response($th->getMessage(), 500);
        }

        Cache::flush();
        Cache::set('importPaths', $importPaths);

        return response($batchId, 202)->header('Content-Type', 'text/plain');
    }
}
