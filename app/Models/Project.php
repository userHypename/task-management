<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'status',
        'priority',
        'start_date',
        'due_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'project_employees', 'project_id', 'user_id')
                    ->withTimestamps()
                    ->wherePivot('is_active', true);
    }

    // Computed Properties
    public function getCompletionPercentageAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        $completedTasks = $this->tasks()->where('is_completed', true)->count();
        return (int) (($completedTasks / $totalTasks) * 100);
    }

    public function getTaskStatusSummaryAttribute(): array
    {
        return [
            'total' => $this->tasks()->count(),
            'completed' => $this->tasks()->where('is_completed', true)->count(),
            'pending' => $this->tasks()->where('is_completed', false)->count(),
        ];
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }
}

