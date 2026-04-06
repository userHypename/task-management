<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public function employees()
{
    return $this->hasMany(Employee::class);
}
//
protected $fillable = ['name', 'description'];

// Accessor for employee count
public function getEmployeeCountAttribute()
{
    return $this->employees()->count();
}
}
