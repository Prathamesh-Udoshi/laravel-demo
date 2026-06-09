<?php

use App\Models\Course;
use App\Models\WeeklyContent;
use App\Models\WeeklyContentChunk;
use App\Ai\Agents\LectureTutor;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Gateway\FakeEmbeddingGateway;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('weekly content transcript chunking works on save', function () {
    // Fake embeddings API
    Embeddings::fake();

    $course = Course::create([
        'title' => 'Test PHP Course',
        'description' => 'Learn PHP testing',
        'duration_weeks' => 1,
    ]);

    // Create weekly content with long notes to trigger chunking
    $longText = str_repeat('This is a test sentence that is repeated to verify chunking. ', 30); // ~1800 chars
    
    $content = WeeklyContent::create([
        'course_id' => $course->id,
        'week_number' => 1,
        'video_title' => 'Introduction to PHPUnit',
        'summary' => 'Short summary',
        'transcript_or_notes' => $longText,
    ]);

    // Wait, the observer dispatches a job. Under sync connection, it executes immediately.
    // Let's assert that chunks were created in the database
    $chunks = WeeklyContentChunk::where('weekly_content_id', $content->id)->get();
    
    expect($chunks)->not->toBeEmpty();
    expect($chunks->count())->toBeGreaterThan(1); // Should be split into at least 2 chunks
    
    foreach ($chunks as $chunk) {
        expect($chunk->content)->not->toBeEmpty();
        expect($chunk->embedding)->toBeArray();
        expect(count($chunk->embedding))->toBeGreaterThan(0);
    }
});

test('php cosine similarity calculation is mathematically correct', function () {
    $vecA = [1.0, 2.0, 3.0];
    $vecB = [2.0, 4.0, 6.0]; // Exactly same direction, cosine similarity should be 1.0
    $vecC = [-1.0, -2.0, -3.0]; // Opposite direction, similarity should be -1.0
    
    $simAB = WeeklyContentChunk::cosineSimilarity($vecA, $vecB);
    $simAC = WeeklyContentChunk::cosineSimilarity($vecA, $vecC);
    
    expect(round($simAB, 2))->toBe(1.00);
    expect(round($simAC, 2))->toBe(-1.00);
});

test('fallback similarity search returns closest chunks on unsupported databases', function () {
    // Mock the Embeddings generation to return a specific vector
    Embeddings::fake([
        [[0.9, 0.1, 0.0]],
        [[0.9, 0.1, 0.0]]
    ]);

    $course = Course::create([
        'title' => 'Database Course',
        'description' => 'SQL',
        'duration_weeks' => 1,
    ]);

    $content1 = WeeklyContent::withoutEvents(function () use ($course) {
        return WeeklyContent::create([
            'course_id' => $course->id,
            'week_number' => 1,
            'video_title' => 'Eloquent Model',
            'transcript_or_notes' => 'Eloquent is an ActiveRecord ORM in Laravel.',
        ]);
    });

    // Create chunks manually with manual embeddings to verify ranking
    $chunk1 = WeeklyContentChunk::create([
        'weekly_content_id' => $content1->id,
        'chunk_index' => 0,
        'content' => 'Chunk about Eloquent models and ORM',
        'embedding' => [0.9, 0.1, 0.0], // High similarity to query
    ]);

    $chunk2 = WeeklyContentChunk::create([
        'weekly_content_id' => $content1->id,
        'chunk_index' => 1,
        'content' => 'Chunk about Python pandas dataframes',
        'embedding' => [0.0, 0.1, 0.9], // Low similarity to query
    ]);

    $results = WeeklyContentChunk::searchSimilar('Eloquent models', minSimilarity: 0.1, limit: 1);
    
    expect($results)->not->toBeEmpty();
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($chunk1->id);
});

test('searchSimilar correctly filters by course ID', function () {
    Embeddings::fake([
        [[0.9, 0.1, 0.0]],
        [[0.9, 0.1, 0.0]]
    ]);

    $courseA = Course::create(['title' => 'Course A', 'description' => 'A', 'duration_weeks' => 1]);
    $courseB = Course::create(['title' => 'Course B', 'description' => 'B', 'duration_weeks' => 1]);

    $contentA = WeeklyContent::withoutEvents(fn() => WeeklyContent::create(['course_id' => $courseA->id, 'week_number' => 1, 'video_title' => 'A']));
    $contentB = WeeklyContent::withoutEvents(fn() => WeeklyContent::create(['course_id' => $courseB->id, 'week_number' => 1, 'video_title' => 'B']));

    $chunkA = WeeklyContentChunk::create([
        'weekly_content_id' => $contentA->id,
        'chunk_index' => 0,
        'content' => 'Chunk from Course A',
        'embedding' => [0.9, 0.1, 0.0],
    ]);

    $chunkB = WeeklyContentChunk::create([
        'weekly_content_id' => $contentB->id,
        'chunk_index' => 0,
        'content' => 'Chunk from Course B',
        'embedding' => [0.9, 0.1, 0.0],
    ]);

    // Query Course A specifically
    $results = WeeklyContentChunk::searchSimilar('query', minSimilarity: 0.1, limit: 5, courseId: $courseA->id);
    
    expect($results)->not->toBeEmpty();
    expect($results->count())->toBe(1);
    expect($results->first()->weeklyContent->course_id)->toBe($courseA->id);
});
