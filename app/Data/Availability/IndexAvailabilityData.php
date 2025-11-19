<?php

namespace App\Data\Availability;

use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Data;

class IndexAvailabilityData extends Data
{
    public function __construct(
        #[Between(1, 6)]
        public int $weekday = 1,
    ) {}
}
