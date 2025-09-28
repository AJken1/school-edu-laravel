<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'status',
        'notes',
        'uploaded_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Define the required file types for enrollment
    const REQUIRED_FILE_TYPES = [
        'birth_certificate' => 'Birth Certificate',
        'report_card' => 'Report Card/Form 138',
        'good_moral' => 'Certificate of Good Moral Character',
        'medical_certificate' => 'Medical Certificate',
        'passport_photo' => 'Passport Size Photo',
        'parent_id' => 'Parent/Guardian ID',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Accessors
    public function getFileTypeNameAttribute()
    {
        return self::REQUIRED_FILE_TYPES[$this->file_type] ?? $this->file_type;
    }

    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) return 'Unknown';
        
        $size = (int) $this->file_size;
        if ($size < 1024) return $size . ' B';
        if ($size < 1048576) return round($size / 1024, 2) . ' KB';
        return round($size / 1048576, 2) . ' MB';
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeMissing($query)
    {
        return $query->where('status', 'missing');
    }
}
