<?php

namespace App\Jobs;

use App\Models\WeeklyContent;
use App\Models\WeeklyContentChunk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ChunkWeeklyContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected WeeklyContent $weeklyContent
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Delete existing chunks
        $this->weeklyContent->chunks()->delete();

        // 2. Determine text source to chunk (summary)
        $textToChunk = $this->weeklyContent->summary;

        if (empty(trim($textToChunk))) {
            return;
        }

        // 3. Perform chunking
        $chunks = $this->chunkText($textToChunk);

        // 4. Vectorize and save each chunk
        foreach ($chunks as $index => $chunkText) {
            try {
                // Generate embeddings array (size 3072 for default Gemini)
                $embedding = Str::of($chunkText)->toEmbeddings();
                
                if (is_array($embedding) && ! empty($embedding)) {
                    WeeklyContentChunk::create([
                        'weekly_content_id' => $this->weeklyContent->id,
                        'chunk_index' => $index,
                        'content' => $chunkText,
                        'embedding' => $embedding,
                    ]);
                }
            } catch (\Exception $e) {
                // Log embedding failures to avoid failing the whole queue
                logger()->error("Failed to generate embedding for weekly content {$this->weeklyContent->id} chunk {$index}: " . $e->getMessage());
            }
        }
    }

    /**
     * Semantically chunks text by sentence boundaries.
     */
    protected function chunkText(string $text, int $chunkSize = 1000, int $overlap = 200): array
    {
        // Split text by sentence endings (.!? followed by whitespace)
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $chunks = [];
        $currentChunk = '';
        
        foreach ($sentences as $sentence) {
            if (strlen($currentChunk . ' ' . $sentence) <= $chunkSize) {
                $currentChunk = empty($currentChunk) ? $sentence : $currentChunk . ' ' . $sentence;
            } else {
                if (! empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                }
                
                // Get approximate overlap words
                $words = explode(' ', $currentChunk);
                $overlapWords = array_slice($words, -15); // ~15 words overlap
                $currentChunk = implode(' ', $overlapWords) . ' ' . $sentence;
            }
        }
        
        if (! empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }
        
        return $chunks;
    }
}
