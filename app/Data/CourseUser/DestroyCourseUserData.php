<?php

namespace App\Data\CourseUser;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;

class DestroyCourseUserData extends Data
{
    public function __construct(
        #[Exists(Role::class, 'id')]
        public int $role_id,
    ) {}
}
