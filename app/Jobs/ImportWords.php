<?php

namespace App\Jobs;

use App\Services\WordService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        $wordService->importToDb($this->words);
    }
}
