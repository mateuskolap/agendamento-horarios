<?php

namespace App\Data\Availability;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Data;

class AvailableTimesForUserData extends Data
{
    public function __construct(
        #[DateFormat('Y-m-d')]
        public Carbon $date,
    )
    {
    }
}
