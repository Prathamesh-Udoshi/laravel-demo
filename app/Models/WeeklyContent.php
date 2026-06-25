<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyContent extends Model
{
    use HasFactory;

    protected $table = 'weekly_contents';

    protected $fillable = [
        'course_id',
        'week_number',
        'video_title',
        'youtube_url',
        'summary',
    ];

    /**
     * Get the course that owns this week's content.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the semantic chunks for this week's content.
     */
    public function chunks()
    {
        return $this->hasMany(WeeklyContentChunk::class, 'weekly_content_id');
    }
}
