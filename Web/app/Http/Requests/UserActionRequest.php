<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Uuid;
use App\Enums\UserActionEnum;
use Illuminate\Validation\Rules\Enum;

class UserActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'task_id' => ['required', new Uuid()],
            'type' => ['required', new Enum(UserActionEnum::class)]
        ];
    }
}
