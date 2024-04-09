<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskLocationRequest extends FormRequest
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
            'long' => ['numeric', 'max:' . INPUT_MAX_LENGTH],
            'lat' => ['numeric', 'max:' . INPUT_MAX_LENGTH],
            'sort' => ['integer'],
            'phone_number' => ['digits:10']
        ];
    }
}
