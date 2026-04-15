<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * Relationship: User who created the task
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: User the task is assigned to
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Keep old relationship for backward compatibility
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Project this task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship: Comments for the task
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Relationship: Activity log entries for the task
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class);
    }

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'due_date',
        'priority',
        'is_completed',
        'assigned_to',
        'created_by',
        'kanban_order'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    /**
     * Order tasks by kanban order when present
     */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw('COALESCE(kanban_order, 1000000) ASC');
    }

    // Scopes for filtering
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Scope: Get tasks assigned to a user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope: overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('is_completed', false);
    }

    /**
     * Scope: tasks due within given days (default 3 days)
     */
    public function scopeDueSoon($query, $days = 3)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)])->where('is_completed', false);
    }

    /**
     * Scope: Get tasks created by a user
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}