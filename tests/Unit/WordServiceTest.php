<?php

namespace Tests\Unit;

use App\Jobs\ImportWords;
use App\Models\Word;
use App\Services\WordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WordServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_array_of_one_word_equals_array_of_one_import_job()
    {
        $wordService = new WordService;
        $exampleWords = ['php'];
        $returnedJob = [new ImportWords($exampleWords)];

        $jobs = $wordService->wordArrayToJobs($exampleWords);

        $this->assertIsArray($jobs);
        $this->assertEquals($jobs, $returnedJob);
    }

    public function test_array_of_ten_words_equals_array_of_ten_import_jobs()
    {
        $wordService = new WordService;
        $exampleWords =
            ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
        $returnedJob = [];
        foreach ($exampleWords as $word) {
            array_push($returnedJob, new ImportWords([$word]));
        }

        $jobs = $wordService->wordArrayToJobs($exampleWords);

        $this->assertIsArray($jobs);
        $this->assertEquals($jobs, $returnedJob);
    }

    public function test_anagram_search_returns_array_of_anagrams()
    {
        $wordService = new WordService;
        $word1 = 'garmin';
        $word2 = 'arming';
        $word3 = 'fake';
        $searchWord = 'margin';

        $wordModel1 = Word::create(['value' => $word1, 'length' => strlen($word1)]);
        $wordModel2 = Word::create(['value' => $word2, 'length' => strlen($word2)]);
        $wordModel3 = Word::create(['value' => $word3, 'length' => strlen($word3)]);

        $this->assertModelExists([$wordModel1, $wordModel2, $wordModel3]);

        $anagrams = $wordService->findAnagrams($searchWord);

        $this->assertNotContains($searchWord, $anagrams);
        $this->assertEquals($anagrams, [$word1, $word2]);
    }

    public function test_anagram_search_returns_empty_array_when_no_anagram_found()
    {
        $wordService = new WordService;
        $word1 = 'garmin';
        $word2 = 'arming';
        $word3 = 'fake';
        $searchWord = 'chikibriki';

        $wordModel1 = Word::create(['value' => $word1, 'length' => strlen($word1)]);
        $wordModel2 = Word::create(['value' => $word2, 'length' => strlen($word2)]);
        $wordModel3 = Word::create(['value' => $word3, 'length' => strlen($word3)]);

        $this->assertModelExists([$wordModel1, $wordModel2, $wordModel3]);

        $anagrams = $wordService->findAnagrams($searchWord);

        $this->assertEmpty($anagrams);
    }

    public function test_import_saves_three_words_when_array_of_three_strings_is_passed()
    {
        $wordService = new WordService;
        $wordService->importToDb(['abc', 'def', 'ghi']);

        $this->assertDatabaseHas('words', [
            'value' => 'abc',
        ]);
        $this->assertDatabaseHas('words', [
            'value' => 'def',
        ]);
        $this->assertDatabaseHas('words', [
            'value' => 'ghi',
        ]);
    }
}
