<?php

namespace Tests\Unit;

use App\Jobs\ImportWords;
use App\Services\WordService;
use Tests\TestCase;

class WordServiceTest extends TestCase
{
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
}
