<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SetNewPasswordRequest extends FormRequest
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
            'password' => ['required', Password::min(8)],
            'code' => ['required', 'digits:6'],
        ];
    }
}
