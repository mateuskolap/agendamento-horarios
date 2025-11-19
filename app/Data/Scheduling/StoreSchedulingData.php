<?php

namespace App\Data\Scheduling;

use App\Enums\SchedulingStatusEnum;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;

class StoreSchedulingData extends Data
{
    public function __construct(
        #[Exists(User::class, 'id')]
        public int $requested_id,

        #[Exists(Course::class, 'id')]
        public int $course_id,

        #[AfterOrEqual('today')]
        public Carbon $date,

        #[DateFormat('H:i')]
        public Carbon $start_time,

        #[DateFormat('H:i')]
        public Carbon $end_time,

        public string $subject,
    ) {}
}
