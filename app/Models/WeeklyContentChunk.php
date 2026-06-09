<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WeeklyContentChunk extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'weekly_content_id',
        'chunk_index',
        'content',
        'embedding',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'embedding' => 'array',
    ];

    /**
     * Get the weekly content that owns the chunk.
     */
    public function weeklyContent(): BelongsTo
    {
        return $this->belongsTo(WeeklyContent::class);
    }

    /**
     * Perform a similarity search on text chunks.
     * Tries native SQL vector search first, then falls back to PHP cosine similarity.
     *
     * @param  string  $query
     * @param  float  $minSimilarity
     * @param  int  $limit
     * @return Collection<int, WeeklyContentChunk>
     */
    public static function searchSimilar(string $query, float $minSimilarity = 0.3, int $limit = 5, ?int $courseId = null): Collection
    {
        try {
            $queryBuilder = self::query();
            if ($courseId) {
                $queryBuilder->whereHas('weeklyContent', fn ($q) => $q->where('course_id', $courseId));
            }
            // Attempt native SQL vector search (Laravel 13 whereVectorSimilarTo)
            return $queryBuilder
                ->whereVectorSimilarTo('embedding', $query, $minSimilarity)
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            // Fallback to PHP-based cosine similarity computation
            // Generate query embedding first
            $queryEmbedding = Str::of($query)->toEmbeddings();
            if (empty($queryEmbedding)) {
                return collect();
            }

            // Retrieve filtered chunks from database
            $queryBuilder = self::with('weeklyContent.course');
            if ($courseId) {
                $queryBuilder->whereHas('weeklyContent', fn ($q) => $q->where('course_id', $courseId));
            }
            $chunks = $queryBuilder->get();

            return $chunks->map(function ($chunk) use ($queryEmbedding) {
                $chunkEmbedding = $chunk->embedding;
                if (is_string($chunkEmbedding)) {
                    $chunkEmbedding = json_decode($chunkEmbedding, true);
                }

                if (! is_array($chunkEmbedding) || empty($chunkEmbedding)) {
                    $chunk->similarity = 0.0;
                    return $chunk;
                }

                $chunk->similarity = self::cosineSimilarity($queryEmbedding, $chunkEmbedding);
                return $chunk;
            })
            ->filter(fn ($chunk) => $chunk->similarity >= $minSimilarity)
            ->sortByDesc('similarity')
            ->take($limit)
            ->values();
        }
    }

    /**
     * Compute cosine similarity between two vector arrays.
     */
    public static function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        
        $count = count($vec1);
        for ($i = 0; $i < $count; $i++) {
            $val1 = $vec1[$i] ?? 0.0;
            $val2 = $vec2[$i] ?? 0.0;
            $dotProduct += $val1 * $val2;
            $normA += $val1 * $val1;
            $normB += $val2 * $val2;
        }
        
        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }
        
        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
}
