<?php

namespace App\Services;

use App\Models\Word;

class WordService
{
    /**
     * @return string[]
     */
    public function findAnagrams(string $searchWord): array
    {
        $searchWordLength = strlen($searchWord);

        $dbWords = Word::where('length', $searchWordLength)->pluck('value');
        if ($dbWords->isEmpty()) {
            return [];
        }

        $matchingWords = $dbWords->reject(function (string $w) use ($searchWord) {
            return (count_chars($searchWord, 1) !== count_chars($w, 1)) || ($w == $searchWord);
        });

        return $matchingWords->values()->all();
    }

    /**
     * @param  string[]  $words
     */
    public function importToDb(array $words): bool
    {
        foreach ($words as $word) {
            $wordModel = new Word;
            $wordModel->value = $word;
            $wordModel->length = strlen($word);
            $wordModel->save();
        }

        return true;
    }
}
