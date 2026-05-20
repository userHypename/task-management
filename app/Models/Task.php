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
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
        'start_date' => 'date',
    ];

    // Relationships
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

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function activities()
    {
        return $this->hasMany(TaskActivity::class)->orderByDesc('created_at');
    }

    // Keep old relationship for backward compatibility
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Computed Properties
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < Carbon::today() && !$this->is_completed;
    }

    public function getDaysUntilDueAttribute(): int
    {
        return Carbon::now()->diffInDays($this->due_date, false);
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
}
