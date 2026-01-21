<?php

namespace App\Interfaces;

use App\Jobs\ImportWords;

interface IWordService
{
    /**
     * @param  string[]  $words
     * @return ImportWords[]
     */
    public function wordArrayToJobs(array $words): array;

    /**
     * @return string[]
     */
    public function findAnagrams(string $searchWord): array;

    /**
     * @param  string[]  $words
     */
    public function importToDb(array $words): void;
}
