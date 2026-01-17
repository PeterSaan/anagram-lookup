<?php

namespace App\Jobs;

use App\Services\WordService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class ImportWords implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * Create a new job instance.
     *
     * @param  string[]  $words
     */
    public function __construct(public array $words) {}

    /**
     * Execute the job.
     *
     * @param  string[]  $words
     */
    public function handle(WordService $wordService): void
    {
        if ($this->batch()->cancelled()) {
            Cache::delete('importBatchId');

            return;
        }

        $wordService->importToDb($this->words);
    }
}
