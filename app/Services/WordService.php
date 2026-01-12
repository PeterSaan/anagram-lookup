<?php

namespace App\Services;

use App\Models\Word;

class WordService
{
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
