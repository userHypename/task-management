<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'assigned_to',
        'created_by',
        'priority',
        'status',
        'due_date',
        'is_completed',
        'kanban_order',
        // 'user_id' => REMOVED - column doesn't exist in database
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    protected $appends = ['assigned_users_count', 'progress'];

    // ========== RELATIONSHIPS ==========
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'user_id')
                    ->withPivot('status', 'completion_notes', 'submission_files', 'started_at', 'completed_at')
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function activities()
    {
        return $this->hasMany(TaskActivity::class)->orderByDesc('created_at');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ========== COMPUTED PROPERTIES ==========
    
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && Carbon::parse($this->due_date)->isPast() && !$this->is_completed;
    }

    public function getDaysUntilDueAttribute(): int
    {
        if (!$this->due_date) return 0;
        return Carbon::now()->diffInDays($this->due_date, false);
    }

    public function getAssignedUsersCountAttribute(): int
    {
        return $this->assignedUsers()->count();
    }

    public function getProgressAttribute(): int
    {
        $total = $this->assignedUsers()->count();
        if ($total === 0) return 0;
        
        $completed = $this->assignedUsers()->wherePivot('status', 'completed')->count();
        return round(($completed / $total) * 100);
    }

    // ========== SCOPES ==========
    
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId)->orWhere('created_by', $userId);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->whereHas('assignedUsers', function($q) use ($employeeId) {
            $q->where('user_id', $employeeId);
        });
    }

    public function scopeForManager($query, $managerId)
    {
        return $query->where('created_by', $managerId);
    }
}