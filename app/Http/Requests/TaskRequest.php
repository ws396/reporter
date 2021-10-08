<?php

namespace App\Http\Requests;

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
     * @return array
     */
    public function rules()
    {
        return [
            'task_start' => 'required|max:255',
            'task_end' => 'required|max:255',
            'task_status' => 'required|max:255',
            'task_description' => 'max:10000',
            'task_worktime' => 'max:255|regex:~(\d+) ч. (\d+) мин.~',
        ];
    }
}
