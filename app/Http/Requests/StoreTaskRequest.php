<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && (auth()->user()->isManager() || auth()->user()->isAdmin());
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => ['nullable','exists:users,id'],
            'project_id' => ['nullable','exists:projects,id'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please provide a title for the task.',
            'priority.required' => 'Select a priority for the task.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
        ];
    }
}
