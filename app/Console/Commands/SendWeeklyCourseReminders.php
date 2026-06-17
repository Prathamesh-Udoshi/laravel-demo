<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Services\EmailReminderAgent;
use App\Mail\CourseCompletionReminder;
use Illuminate\Support\Facades\Mail;

class SendWeeklyCourseReminders extends Command
{
    protected $signature = 'app:send-weekly-course-reminders';
    protected $description = 'Send AI personalized reminders to students who have not completed their courses';

    public function handle(EmailReminderAgent $agent)
    {
        // Fetch all courses and eager load incomplete students who haven't been reminded in the last 7 days
        $courses = Course::with(['students' => function ($query) {
            $query->wherePivot('progress_percent', '<', 100)
                  ->where(function($q) {
                      $q->whereNull('last_reminded_at')
                        ->orWhere('last_reminded_at', '<', now()->subDays(7));
                  });
        }])->get();

        $count = 0;
        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                if ($student->email) {
                    $this->info("Generating AI reminder for {$student->name} in {$course->title}...");
                    
                    // Generate email using AI agent
                    $emailContent = $agent->generateReminder($student, $course);
                    
                    // Send Mailable
                    Mail::to($student->email)->send(
                        new CourseCompletionReminder($emailContent, "Friendly reminder: Complete your course {$course->title}!")
                    );
                    
                    // Update pivot table timestamp
                    $student->courses()->updateExistingPivot($course->id, [
                        'last_reminded_at' => now(),
                    ]);
                    
                    $count++;
                }
            }
        }

        $this->info("Completed sending {$count} reminders.");
    }
}
