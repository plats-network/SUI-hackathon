<?php

namespace App\Http\Requests\Cws;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{Session};
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class EditPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        session()->put('type', 'edit-pass');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old' => ['required'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ]
        ];
    }
}
