<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'course_id',
        'evaluation_type', // 'midterm' or 'final'
        'assignment_title',
        'instructions',
    ];

    /**
     * Get the course that owns this assignment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
