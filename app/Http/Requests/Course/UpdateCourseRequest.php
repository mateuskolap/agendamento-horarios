<?php

namespace App\Http\Requests\Course;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'coordinator_id' => ['required', 'exists:users,id'],
        ];
    }
}
