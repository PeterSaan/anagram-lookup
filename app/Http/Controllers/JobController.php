<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Bus;

class JobController extends Controller
{
    public function batchProgress(string $batchId)
    {
        $batch = Bus::findBatch($batchId);
        if (! $batch) {
            return response('Batch '.$batchId.' not found', 404);
        }

        if ($batch->finished()) {
            return response('Batch '.$batchId.' finished', 201);
        }

        return response($batch->progress().'%');
    }
}
