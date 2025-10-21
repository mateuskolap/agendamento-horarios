<?php

namespace App\Data\Availability;

use App\Models\User;
use App\Rules\TimeMultipleOf;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class StoreAvailabilityData extends Data
{
    public function __construct(
        #[Exists(User::class, 'id')]
        public int $user_id,

        #[Between(1, 6)]
        public int $weekday,

        #[DateFormat('H:i')]
        #[Rule(new TimeMultipleOf(30))]
        public Carbon $start_time,

        #[DateFormat('H:i')]
        #[Rule(new TimeMultipleOf(30))]
        #[After('start_time')]
        public Carbon $end_time,
    ) {}
}
