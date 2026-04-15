<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'is_completed' => 'sometimes|boolean',
            'assigned_to' => ['nullable','exists:users,id'],
            'project_id' => ['nullable','exists:projects,id'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please provide a title for the task.',
        ];
    }
}
