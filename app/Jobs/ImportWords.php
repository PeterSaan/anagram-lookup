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
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1000;
    // The 1000 seconds is based on a single test I did with a
    // 4-core 8th gen Intel i5 laptop on power-saving mode.
    // Feel free to change it.

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
