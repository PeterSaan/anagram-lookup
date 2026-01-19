<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateApiDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-doc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from controllers with swagger-php';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $openapi = (new \OpenApi\Generator)->generate([app_path('Http/Controllers')]);

        $openapi->saveAs(base_path('docs/openapi.json'));
    }
}
