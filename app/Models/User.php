<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'manager_id',
        'account_status',
        'position',
        'avatar',
        'phone',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELATIONSHIPS ==========
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function managedEmployees()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function assignedToManyTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments', 'user_id', 'task_id')
                    ->withPivot('status', 'completion_notes', 'submission_files', 'started_at', 'completed_at')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function taskActivities()
    {
        return $this->hasMany(TaskActivity::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    // ========== ROLE HELPERS ==========
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    // ========== ACCESSORS ==========
    
    protected function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('account_status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}