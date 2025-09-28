<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'firstname',
        'lastname',
        'employee_id',
        'email',
        'gender',
        'phone',
        'contact_number',
        'address',
        'age',
        'date_of_birth',
        'position',
        'department',
        'image',
        'profile_picture',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function files()
    {
        return $this->hasMany(TeacherFile::class);
    }
}