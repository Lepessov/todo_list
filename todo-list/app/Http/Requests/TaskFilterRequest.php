<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:todo,in_progress,done',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_field' => 'nullable|in:id,title,description,status,created_at,updated_at',
            'sort_direction' => 'nullable|in:asc,desc',
        ];
    }
}
