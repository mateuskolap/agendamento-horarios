<?php

namespace App\Data\Scheduling;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class StoreSchedulingData extends Data
{
    public function __construct(
        public int $student_id,
        public int $educator_id,
        public int $educator_role_id,
        public int $course_id,
        public Carbon $date,
        public Carbon $time,
        public string $subject,
        public string $status,
    ) {}
}
