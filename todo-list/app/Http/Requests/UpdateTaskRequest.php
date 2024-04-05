<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:todo,in_progress,done',
            'file_path' => 'nullable|file|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than :max characters.',
            'description.string' => 'The description must be a string.',
            'status.in' => 'The status must be one of: todo, in_progress, done.',
            'file_path.file' => 'The file must be a valid file.',
            'file_path.max' => 'The file may not be greater than 10MB.',
        ];
    }
}
