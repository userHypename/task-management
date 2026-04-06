<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isEmployee()
    {
        return !$this->isAdmin() && !$this->isManager();
    }

    /**
     * Ensure plain passwords are hashed before saving (defensive).
     */
    protected function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        // If the value is already a valid hash for the current algorithm,
        // keep it. Otherwise, hash plaintext.
        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

}
