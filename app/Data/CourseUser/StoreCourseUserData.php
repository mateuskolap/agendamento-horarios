<?php

namespace App\Data\CourseUser;

use Spatie\LaravelData\Data;

class StoreCourseUserData extends Data
{
    public function __construct(
        public int $role_id,
        /** @var array<int> */
        public array $course_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'course_ids' => ['required', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ];
    }
}
