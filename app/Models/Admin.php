<?php
// app/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'fname',
        'lname',
        'dob',
        'image',
        'phone',
        'gender',
        'address',
        'user_id',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }

    public function getProfileImageAttribute()
    {
        return $this->image ? asset('storage/admins/' . $this->image) : asset('images/user.png');
    }
}