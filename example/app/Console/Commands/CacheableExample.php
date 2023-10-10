<?php

namespace App\Console\Commands;

use App\Services\ExampleService;
use Illuminate\Console\Command;

class CacheableExample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:cacheable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example of Cacheable';

    private ExampleService $service;

    public function __construct(ExampleService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carbon = $this->service->heavyProcess(1);
        echo $carbon->getTimestampMs();
    }
}
