<?php

namespace App\Console\Commands;

use App\Jobs\ChunkWeeklyContentJob;
use App\Models\WeeklyContent;
use Illuminate\Console\Command;

class ChunkTranscripts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:chunk-transcripts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chunk and generate vector embeddings for all existing weekly lesson transcripts and summaries.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $contents = WeeklyContent::all();

        if ($contents->isEmpty()) {
            $this->warn('No weekly content found in the database. Create some first!');
            return;
        }

        $this->info("Found {$contents->count()} weekly content records to process.");

        $progressBar = $this->output->createProgressBar($contents->count());
        $progressBar->start();

        foreach ($contents as $content) {
            $this->output->write(" Processing Week {$content->week_number} of Course ID {$content->course_id}: {$content->video_title}... ");
            
            try {
                // Run job synchronously
                ChunkWeeklyContentJob::dispatchSync($content);
                $this->info("Success!");
            } catch (\Exception $e) {
                $this->error("Failed: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info('Completed chunking and vectorizing all transcripts!');
    }
}
