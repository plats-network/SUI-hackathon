<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'max:100',
                'email',
                Rule::unique('users')->ignore($this->id),
            ],
            'password' => [
                'required',
                'confirmed',
                'min: 8',
            ],
            'name' => [
                'required',
                'max: 50',
                'min: 4',
            ],
            'term' => ['required'],
        ];
    }
}
