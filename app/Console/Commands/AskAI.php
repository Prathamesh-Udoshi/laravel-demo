<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\AI;

class AskAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:ask {question}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ask a question to the AI using the AI::chat()->prompt() method';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $question = $this->argument('question');

        $this->info("Asking AI: {$question}...");

        try {
            // Using the requested syntax
            $response = AI::chat()->prompt($question);
            
            $this->info("AI Response:");
            $this->line($response->text);

        } catch (\Exception $e) {
            $this->error("Failed to get response: " . $e->getMessage());
        }
    }
}
