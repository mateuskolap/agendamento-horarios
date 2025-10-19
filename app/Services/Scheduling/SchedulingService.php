<?php

namespace App\Services\Scheduling;

use App\Data\Scheduling\StoreSchedulingData;
use App\Models\Scheduling;

class SchedulingService
{
    public function create(StoreSchedulingData $data): Scheduling
    {
        return Scheduling::create($data->toArray());
    }
}
