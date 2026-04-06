<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'is_completed',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

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
     * Scope: Get tasks created by a user
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}