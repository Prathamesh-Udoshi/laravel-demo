<?php

use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Mail;
use App\Mail\CourseCompletionReminder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('email agent dashboard is accessible', function () {
    $response = $this->get('/email-agent');

    $response->assertStatus(200);
    $response->assertSee('AI Email Reminder Agent');
});

test('can enroll student and update email address', function () {
    $student = Student::create(['name' => 'Alice Test', 'class' => 'CSE']);
    $course = Course::create(['title' => 'Laravel AI Course', 'description' => 'Learn AI', 'duration_weeks' => 4]);

    $response = $this->post('/email-agent/enroll', [
        'student_id' => $student->id,
        'course_id' => $course->id,
        'progress_percent' => 50,
        'email' => 'alice@test.com',
    ]);

    $response->assertRedirect();
    
    // Check that email updated on student
    $student->refresh();
    expect($student->email)->toBe('alice@test.com');

    // Check that pivot table was populated
    $enrollment = $student->courses()->first();
    expect($enrollment->id)->toBe($course->id);
    expect($enrollment->pivot->progress_percent)->toBe(50);
});

test('triggering email agent sends email if student has email', function () {
    Mail::fake();

    $student = Student::create(['name' => 'Bob Test', 'class' => 'CSE', 'email' => 'bob@test.com']);
    $course = Course::create(['title' => 'Laravel AI Course', 'description' => 'Learn AI', 'duration_weeks' => 4]);

    // Enroll Bob
    $course->students()->attach($student->id, ['progress_percent' => 30]);

    // Mock AI agent response to avoid real API calls during test
    $mockAgent = Mockery::mock(\App\Services\EmailReminderAgent::class);
    $mockAgent->shouldReceive('generateReminder')->andReturn('This is a test email body.');
    $this->app->instance(\App\Services\EmailReminderAgent::class, $mockAgent);

    $response = $this->post('/email-agent/send-reminder', [
        'student_id' => $student->id,
        'course_id' => $course->id,
    ]);

    $response->assertRedirect();
    
    // Assert email was sent
    Mail::assertSent(CourseCompletionReminder::class, function ($mail) use ($student) {
        return $mail->hasTo('bob@test.com') && $mail->emailBody === 'This is a test email body.';
    });
});
