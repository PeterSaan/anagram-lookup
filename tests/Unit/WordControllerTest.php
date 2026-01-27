<?php

namespace Tests\Feature;

use App\Models\Word;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_anagrams_found_from_cache_returns_ok_status()
    {
        $searchWord = 'margin';
        $anagrams = ['garmin', 'arming'];

        Cache::expects('get')->with($searchWord, '')->andReturn($anagrams);

        $res = $this->get('/api/find-anagrams/'.$searchWord);

        $res->assertOk();
        $res->assertExactJson($anagrams);
    }

    public function test_anagrams_found_from_db_returns_created_status()
    {
        $word1 = 'garmin';
        $word2 = 'arming';
        $searchWord = 'margin';

        $wordModel1 = Word::create(['value' => $word1, 'length' => mb_strlen($word1)]);
        $wordModel2 = Word::create(['value' => $word2, 'length' => mb_strlen($word2)]);

        $this->assertModelExists([$wordModel1, $wordModel2]);

        $res = $this->get('/api/find-anagrams/'.$searchWord);

        $res->assertCreated();
        $res->assertExactJson([$word1, $word2]);
    }

    public function test_no_anagrams_found_returns_nothing_and_no_content_status()
    {
        $word1 = 'garmin';
        $word2 = 'arming';
        $searchWord = 'chikibriki';

        $wordModel1 = Word::create(['value' => $word1, 'length' => mb_strlen($word1)]);
        $wordModel2 = Word::create(['value' => $word2, 'length' => mb_strlen($word2)]);

        $this->assertModelExists([$wordModel1, $wordModel2]);

        $res = $this->get('/api/find-anagrams/'.$searchWord);

        $res->assertNoContent();
    }

    public function test_missing_param_return_not_found_status()
    {
        $res = $this->get('/api/find-anagrams/');

        $res->assertNotFound();
    }

    public function test_non_plain_text_url_returns_forbidden_status()
    {
        $res = $this->post('/api/import-words', ['url' => 'https://random.com']);

        $res->assertForbidden();
    }

    public function test_correct_request_returns_accepted_status()
    {
        Bus::fake();
        $res = $this->post('/api/import-words', ['url' => 'https://opus.ee/lemmad2013.txt']);

        Bus::assertBatched(function (PendingBatch $batch) {
            return $batch->name === 'Import words';
        });
        $res->assertAccepted();
    }

    public function test_empty_request_returns_bad_request_status()
    {
        $res = $this->post('/api/import-words');

        $res->assertBadRequest();
        $res->assertSeeText('Empty or invalid URL');
    }

    public function test_invalid_url_returns_bad_request_status()
    {
        $res = $this->post('/api/import-words', ['url' => 'chikibriki']);

        $res->assertBadRequest();
        $res->assertSeeText('Empty or invalid URL');
    }
}
