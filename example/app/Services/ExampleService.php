<?php

namespace App\Services;

use Laravel\Cache\Attribute\Cacheable;
use Carbon\Carbon;

class ExampleService
{
    private Carbon $carbon;

    public function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }

    #[Cacheable(name: 'ExampleService#heavyProcess', ttl_seconds: 60)]
    public function heavyProcess(int $sleep, MyType $type)
    {
        echo $type->name;
        sleep($sleep);
        return Carbon::now();
    }
}