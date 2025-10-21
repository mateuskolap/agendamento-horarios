<?php

namespace App\Data\User;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class StoreUserData extends Data
{
    public function __construct(
        public string $name,
        #[Email]
        #[Lowercase]
        #[Unique(User::class)]
        public string $email,
        public string $password,
        /** @var int[] */
        public array $role_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ];
    }
}
