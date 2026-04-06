<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'position',
        'department_id',
        'hire_date'
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}