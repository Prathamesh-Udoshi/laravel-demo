<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $table = 'students';

    protected $fillable = [
        'name',
        'class',
        'email'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student')
                    ->withPivot('progress_percent', 'completed_at', 'last_reminded_at')
                    ->withTimestamps();
    }   
}

