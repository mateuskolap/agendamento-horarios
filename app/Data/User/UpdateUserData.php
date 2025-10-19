<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

class UpdateUserData extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $password,
        /** @var int|array<int>|null */
        public array|int|null $role_ids = null,
    ) {}
}
