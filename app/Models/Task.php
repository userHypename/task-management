<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
    'is_completed'
];

}
