<?php

namespace App\Data\User;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rules;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UpdateUserData extends Data
{
    public function __construct(
        public ?string $name,

        #[Email]
        #[Lowercase]
        #[Unique(User::class, 'email', ignore: new RouteParameterReference('user.id'))]
        public ?string $email,

        public ?string $password,

        /** @var int[] */
        public ?array $role_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ];
    }
}
