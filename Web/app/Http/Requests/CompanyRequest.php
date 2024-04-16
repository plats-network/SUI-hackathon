<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:' . INPUT_MAX_LENGTH],
            'address' => ['required', 'max:' . INPUT_MAX_LENGTH],
            'phone' => ['string', 'min:10', 'max:15'],
            'logo_path' => ['required']
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'logo_path' => 'Logo image',
        ];
    }
}
