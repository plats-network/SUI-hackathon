<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterConfirmRequest extends FormRequest
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
            'confirmation_code' => ['required', 'digits:6'],
        ];
    }
}
