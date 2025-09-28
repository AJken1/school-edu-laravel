<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'school_year',
        'lrn_number',
        'firstname',
        'lastname',
        'mi',
        'email',
        'phone',
        'sex',
        'gender',
        'date_of_birth',
        'religion',
        'grade',
        'grade_level',
        'current_address',
        'address',
        'previous_school',
        'pwd',
        'pwd_details',
        'parent_name',
        'parent_phone',
        'parent_email',
        'relationship',
        'father_firstname',
        'father_lastname',
        'father_mi',
        'mother_firstname',
        'mother_lastname',
        'mother_mi',
        'guardian_firstname',
        'guardian_lastname',
        'guardian_mi',
        'contact_number',
        'medical_conditions',
        'additional_notes',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'pwd' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(StudentFile::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->mi} {$this->lastname}";
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }

    public function isGraduated()
    {
        return $this->status === 'graduated';
    }

    public function isActive()
    {
        return in_array($this->status, ['Active', 'enrolled']);
    }
}