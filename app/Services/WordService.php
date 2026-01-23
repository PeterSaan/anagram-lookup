<?php

namespace App\Services;

use App\Interfaces\IWordService;
use App\Jobs\ImportWords;
use App\Models\Word;

class WordService implements IWordService
{
    public function wordArrayToJobs(array $words): array
    {
        $totalAmountOfWords = count($words);
        $maxJobsInBatch = 10;
        $maxWordsInChunk = ceil($totalAmountOfWords / $maxJobsInBatch);

        $wordChunks = array_filter(
            array_chunk($words, $maxWordsInChunk),
            fn ($w) => ! empty($w),
        );

        $jobs = [];
        foreach ($wordChunks as $wordChunk) {
            array_push($jobs, new ImportWords($wordChunk));
        }

        return $jobs;
    }

    public function findAnagrams(string $searchWord): array
    {
        $searchWordLength = strlen($searchWord);

        $dbWords = Word::all()->where('length', $searchWordLength)->pluck('value');
        if ($dbWords->isEmpty()) {
            return [];
        }

        $matchingWords = $dbWords->reject(function (string $w) use ($searchWord) {
            return (count_chars($searchWord, 1) !== count_chars($w, 1)) || ($w == $searchWord);
        })->sortBy('value')->values()->all();

        return $matchingWords;
    }

    public function importToDb(array $words): void
    {
        foreach ($words as $word) {
            $wordModel = new Word;
            $wordModel->value = $word;
            $wordModel->length = mb_strlen($word);
            $wordModel->save();
        }
    }
}
