<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseCompletionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $emailBody;
    public $subjectLine;

    public function __construct(string $emailBody, string $subjectLine)
    {
        $this->emailBody = $emailBody;
        $this->subjectLine = $subjectLine;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.course-reminder',
        );
    }
}
