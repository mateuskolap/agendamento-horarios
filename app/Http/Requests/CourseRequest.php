<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'coordinator_id' => ['nullable', 'exists:coordinators,id'],
        ];
    }
}
