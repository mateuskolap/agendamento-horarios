<?php

namespace App\Data\Availability;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class StoreAvailabilityData extends Data
{
    public function __construct(
        public int $user_id,
        public int $weekday,
        public Carbon $start_time,
        public Carbon $end_time,
    ) {}
}
