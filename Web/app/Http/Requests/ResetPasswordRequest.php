<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'max:' . INPUT_MAX_LENGTH, 'email', 'exists:users,email'],
            'code' => ['required', 'digits:6'],
        ];
    }
}
