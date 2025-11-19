<?php

namespace App\Data\Course;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class GetAvailableCoursesByRequestedData extends Data
{
    public function __construct(
        #[Exists(User::class, 'id')]
        public int $requested_id,
    ) {}
}
