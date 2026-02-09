<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'user_id',
        'employee_id',
        'email',
        'password',
        'role',
        'phone',
        'department',
        'status',
        'theme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function profile()
    {
        return match($this->role) {
            'admin' => $this->admin,
            'teacher' => $this->teacher,
            'student' => $this->student,
            default => null,
        };
    }

    /**
     * Get 2-letter initials from the user's name (first letter of first two words, or first two chars).
     * Used for avatar fallback when no profile image is set.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->name ? trim($this->name) : '';

        if ($name === '') {
            return $this->role === 'admin' ? 'SA' : strtoupper(substr($this->role ?: 'U', 0, 1));
        }

        $words = preg_split('/\s+/', $name, 2);

        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }

        $first = mb_substr($name, 0, 2);
        return mb_strlen($first) >= 2 ? strtoupper($first) : strtoupper($first);
    }
}