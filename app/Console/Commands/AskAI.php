<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\AI;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('ai:ask {question}')]
#[Description('Ask a question to the AI using the AI::chat()->prompt() method')]
class AskAI extends Command
{
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
