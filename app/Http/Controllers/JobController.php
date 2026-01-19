<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Bus;
use OpenApi\Attributes as OA;

class JobController extends Controller
{
    #[OA\Get(
        path: '/api/job/batch-progress/{id}',
        summary: 'Get the progress of a batch by its uuid',
        tags: ['job'],
        parameters: [
            new OA\PathParameter(
                name: 'id',
                description: 'UUID of a batch of jobs',
                schema: new OA\Schema(type: 'string'),
                example: 'b690bd00-819d-4d18-9151-25ed11b96b83'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Batch progress successfully retrieved',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: '50%'
                )
            ),
            new OA\Response(
                response: 201,
                description: 'Batch has finished',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'Batch b690bd00-819d-4d18-9151-25ed11b96b83 finished'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Batch with the given uuid was not found or the given paramater was not of type UUID',
                content: new OA\MediaType(
                    mediaType: 'text/html',
                    example: 'Batch b690bd00-819d-4d18-9151-25ed11b96b83 not found'
                )
            ),
        ]
    )]
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
