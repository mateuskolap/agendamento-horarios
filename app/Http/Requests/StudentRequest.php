<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'ra' => ['required', 'string'],
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'ra' => ['required', 'string'],
            ];
        }

        return [];
    }
}
