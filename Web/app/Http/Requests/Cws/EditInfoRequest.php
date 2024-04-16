<?php

namespace App\Http\Requests\Cws;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class EditInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        session()->put('type', 'edit-info');
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
            'name' => ['required', 'min:5', 'max: 50'],
            'avatar_path' => ['nullable', 'mimes:jpeg,png,jpg,gif'],
            'birth' => ['required']
        ];
    }
}
