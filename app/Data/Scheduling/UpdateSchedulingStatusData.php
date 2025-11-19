<?php

namespace App\Data\Scheduling;

use App\Enums\SchedulingStatusEnum;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Data;

class UpdateSchedulingStatusData extends Data
{
    public function __construct(
        public SchedulingStatusEnum $status,
        #[MinDigits(10)]
        public ?string $rejection_reason = null,
    ) {}
}
