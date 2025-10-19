<?php

namespace App\Http\Requests;

use App\Enums\SchedulingStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchedulingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:users,id'],
            'educator_id' => ['required', 'exists:users,id'],
            'educator_role_id' => ['required', 'exists:roles,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'subject' => ['required', 'string'],
            'status' => ['required', Rule::in(array_column(SchedulingStatusEnum::cases(), 'value'))],
        ];
    }
}
