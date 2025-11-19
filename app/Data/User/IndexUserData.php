<?php

namespace App\Data\User;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;

class IndexUserData extends Data
{
    public function __construct(
        public ?string $name,

        #[Exists(Role::class, 'id')]
        public ?int $role_id,
    ) {}
}
