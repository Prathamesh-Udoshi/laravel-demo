<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Ai\Agents\TweetGenerator;
use Laravel\Ai\Messages\UserMessage;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ai:tweet {topic}', function
(TweetGenerator $agent) {
    $topic = $this->argument('topic');
    $this->info("Asking TweetGenerator Agent to draft a tweet about: '{$topic}'...");

    $response = $agent->prompt($topic);

    $this->comment("\n" . $response);
})->purpose('Ask AI Agent to draft a tweet about a topic');


// Run the AI email reminder sweeps weekly
Schedule::command('app:send-weekly-course-reminders')->weekly();

