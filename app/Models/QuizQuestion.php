<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $fillable = [
        'course_id',
        'evaluation_type', // 'midterm' or 'final'
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option', // 'A', 'B', 'C', 'D'
        'explanation',
    ];

    /**
     * Get the course that owns this quiz question.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
