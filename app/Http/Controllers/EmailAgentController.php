<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Services\EmailReminderAgent;
use App\Mail\CourseCompletionReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailAgentController extends Controller
{
    public function index()
    {
        $courses = Course::with('students')->get();
        $students = Student::all();
        return view('email-agent.dashboard', compact('courses', 'students'));
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'progress_percent' => 'required|integer|min:0|max:100',
            'email' => 'nullable|email|max:255',
        ]);

        $student = Student::findOrFail($request->student_id);
        if ($request->filled('email')) {
            $student->update(['email' => $request->email]);
        }

        $course = Course::findOrFail($request->course_id);
        
        $course->students()->syncWithoutDetaching([
            $request->student_id => [
                'progress_percent' => $request->progress_percent,
                'completed_at' => $request->progress_percent >= 100 ? now() : null,
            ]
        ]);

        return back()->with('success', 'Student registered for course successfully!');
    }

    public function sendReminder(Request $request, EmailReminderAgent $agent)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $course = Course::findOrFail($request->course_id);

        if (!$student->email) {
            return back()->with('error', "Student {$student->name} does not have an email address configured.");
        }

        // Generate AI personalized email body
        $emailContent = $agent->generateReminder($student, $course);

        // Dispatch Email
        Mail::to($student->email)->send(new CourseCompletionReminder($emailContent, "Friendly reminder: Complete your course {$course->title}!"));

        // Record last reminded timestamp
        $student->courses()->updateExistingPivot($course->id, [
            'last_reminded_at' => now(),
        ]);

        return back()->with('success', "AI Reminder email has been compiled and sent to {$student->email} successfully!");
    }
}
