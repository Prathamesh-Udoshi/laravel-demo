<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_weeks',
        'youtube_playlist_url',
    ];

    /**
     * Get the weekly contents for the course.
     */
    public function weeklyContents()
    {
        return $this->hasMany(WeeklyContent::class)->orderBy('week_number', 'asc');
    }

    /**
     * Get the quiz questions for the course.
     */
    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the assignments for the course.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
