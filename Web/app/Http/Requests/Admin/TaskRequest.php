<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        $rules = [];
        switch ($this->method()) {
            case 'GET':
            case "DELETE":
            case 'PUT':
                break;
            case 'POST':
                $rules = [
                    'description' => ['required'],
                    'order' => ['required','integer'],
                    'status' => ['nullable'],
                    'end_at' => ['required'],
                    'start_at' => ['required'],
                    'group_id.*' => ['required'],
                    'task_socials' => ['nullable'],
                    'task_socials.*.type' => ['required'],
                    'task_socials.*.url' => ['required'],
                    'task_socials.*.amount' => ['required','numeric', 'min:0'],
                    'task_socials.*.platform' => ['required','integer'],
                    'task_socials.*.name' => ['required'],
                    'task_socials.*.reward_id' => ['required'],
                    'task_locations' => ['nullable'],
                    'task_locations.*.name' => ['required'],
                    'task_locations.*.reward_id' => ['required'],
                    'task_locations.*.description' => ['required'],
                    'task_locations.*.amount' => ['required','numeric', 'min:0'],
                    'sessions' => ['nullable'],
                    'sessions*.name' => ['required'],
                    'sessions*.max_job' => ['required','numeric'],
                    'sessions*.description' => ['required'],
                    'sessions*.type' => ['required'],
                    'sessions*.detail' => ['required'],
                    'sessions*.detail.*.name' => ['required'],
                    'sessions*.detail.*.description' => ['required'],
                    'booths' => ['nullable'],
                    'booths*.name' => ['required'],
                    'booths*.max_job' => ['required','numeric'],
                    'booths*.description' => ['required'],
                    'booths*.type' => ['required'],
                    'booths*.detail' => ['required'],
                    'booths*.detail.*.name' => ['required'],
                    'booths*.detail.*.description' => ['required'],
//                    'locations.*.job_num' => ['required','integer'],
//                    'locations.*.order' => ['required','integer'],
//                    'locations.*.status' => ['required'],
                    'task_locations.*.detail' => ['nullable'],
                    'task_locations.*.detail.*.name' => ['required'],
//                    'locations.*.detail.*.description' => ['required'],
                    'task_locations.*.detail.*.address' => ['required'],
                    'task_locations.*.detail.*.lng' => ['numeric' ],
                    'task_locations.*.detail.*.lat' => ['numeric'],
                    'quiz.*.name' => ['required',],
                    'quiz.*.time_quiz' => ['required',],
                    'quiz.*.order' => ['required',],
                    'quiz.*.detail' => ['required',],
                    'quiz.*.detail.*.name' => ['required',],


                ];
                if (!$this->id) {
                    $rules = array_merge($rules,  [
                        'banner_url' => ['nullable'],
                        'task_galleries.*' => ['nullable'],
                        'name' => ['required','unique:tasks', 'max: 255'],
                    ]);
                } else {
                    $rules = array_merge($rules,  [
                        'banner_url' => ['nullable',],
                        'task_galleries.*' => ['nullable',],
                        'name' => ['required','max: 255'],
                    ]);
                }
                break;
            default:
                break;
        }

        return $rules;
    }
}
