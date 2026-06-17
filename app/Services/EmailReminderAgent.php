<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Course;
use App\Support\AI;

class EmailReminderAgent
{
    /**
     * Generate a personalized, highly motivational reminder email using AI.
     */
    public function generateReminder(Student $student, Course $course): string
    {
        // Get progress for this student-course combination
        $enrollment = $student->courses()->where('course_id', $course->id)->first();
        $progress = $enrollment ? $enrollment->pivot->progress_percent : 0;

        $prompt = "Write a warm, motivating, and professional email to a student named '{$student->name}' " .
                  "who is currently enrolled in the course '{$course->title}' (Description: {$course->description}). " .
                  "The student has completed {$progress}% of the course. Urge them to log back in and finish their remaining lectures, " .
                  "quizzes, and assignments to get certified. " .
                  "Ensure the response includes a friendly subject line, warm greeting, helpful overview of progress, and a signature. " .
                  "Return ONLY the final email text ready to send. Do not wrap it in quotes or markdown code blocks.";

        // Use the application's AI support system
        $agent = AI::chat(instructions: "You are an automated academic tutor agent. You write engaging, supportive, and action-oriented reminder emails to help students finish their classes.");
        
        $response = $agent->prompt($prompt);
        return $response->text;
    }
}
