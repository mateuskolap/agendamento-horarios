<?php

namespace App\Http\Requests\Availability;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexAvailabilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'weekday' => ['nullable', 'integer', 'between:1,6'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'weekday' => $this->input('weekday', 1),
        ]);
    }
}
