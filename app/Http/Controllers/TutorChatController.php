<?php

namespace App\Http\Controllers;

use App\Ai\Agents\LectureTutor;
use App\Models\Course;
use App\Models\User;
use App\Models\WeeklyContentChunk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TutorChatController extends Controller
{
    /**
     * Display the Tutor Chat Dashboard.
     */
    public function index(): View
    {
        $courses = Course::with('weeklyContents')->get();

        return view('tutor', compact('courses'));
    }

    /**
     * Handle the chat interaction.
     */
    public function chat(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        @set_time_limit(0);

        $request->validate([
            'message' => 'required|string',
            'course_id' => 'nullable|integer',
            'conversation_id' => 'nullable|string',
        ]);

        $question = $request->input('message');
        $courseId = $request->input('course_id');
        $conversationId = $request->input('conversation_id');

        $searchQuery = $question;

        // 0. If this is an ongoing conversation, condense the question based on previous messages to optimize RAG retrieval
        if ($conversationId) {
            try {
                $messages = \Illuminate\Support\Facades\DB::table('agent_conversation_messages')
                    ->where('conversation_id', $conversationId)
                    ->orderBy('id', 'desc')
                    ->limit(6)
                    ->get()
                    ->reverse();

                if ($messages->isNotEmpty()) {
                    $historyText = $messages->map(function ($m) {
                        $role = $m->role === 'user' ? 'User' : 'Assistant';
                        return "{$role}: {$m->content}";
                    })->join("\n");

                    // Use App\Support\AI to optimize the query
                    $condenser = \App\Support\AI::chat(
                        instructions: "You are a search query optimizer. Given a conversation history and a student follow-up question, rewrite it into a standalone search query containing all necessary keywords and topic context. Return ONLY the final optimized query text. Do not write any conversational preamble."
                    );

                    $condensedResult = $condenser->prompt(
                        "CONVERSATION HISTORY:\n{$historyText}\n\nFOLLOW-UP QUESTION: {$question}\n\nSTANDALONE SEARCH QUERY:"
                    );

                    $optimizedText = trim($condensedResult->text);
                    if (!empty($optimizedText)) {
                        $searchQuery = $optimizedText;
                    }
                }
            } catch (\Exception $e) {
                logger()->warning('RAG Query Condenser Error: ' . $e->getMessage());
            }
        }

        // 1. Retrieve a larger candidate pool of matching chunks using semantic search
        $chunks = WeeklyContentChunk::searchSimilar(
            query: $searchQuery,
            minSimilarity: 0.15,
            limit: 15,
            courseId: $courseId
        );

        // 2. Rerank the matching chunks to extract the top 3 most relevant context blocks using Jina
        if ($chunks->isNotEmpty()) {
            $chunks = $chunks->rerank(
                by: 'content',
                query: $searchQuery,
                limit: 3
            );
        }

        // 3. Construct context block for the RAG agent
        $context = '';
        foreach ($chunks as $chunk) {
            $courseTitle = $chunk->weeklyContent->course->title ?? 'Unknown Course';
            $weekNum = $chunk->weeklyContent->week_number ?? 0;
            $videoTitle = $chunk->weeklyContent->video_title ?? 'Lesson';
            
            $context .= "[Course: {$courseTitle} | Week: {$weekNum} | Lesson: {$videoTitle}]\n";
            $context .= "{$chunk->content}\n\n";
        }

        // 4. Resolve a user session (fetch first user or fallback)
        $user = User::first() ?: User::create([
            'name' => 'Guest Student',
            'email' => 'guest_' . time() . '@example.com',
            'password' => bcrypt('password'),
        ]);

        // 5. Invoke the AI Tutor Agent with the contextual transcript notes
        $agent = new LectureTutor();
        $agent->setLectureContext($context);

        $streamable = $conversationId 
            ? $agent->continue($conversationId, as: $user)->stream($question)
            : $agent->forUser($user)->stream($question);

        return response()->stream(function () use ($streamable, $chunks) {
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();

            // Send metadata packet containing RAG context
            echo "data: " . json_encode([
                'type' => 'metadata',
                'conversation_id' => $streamable->conversationId,
                'context_used' => $chunks->map(fn ($c) => [
                    'course' => $c->weeklyContent->course->title ?? 'Unknown',
                    'week' => $c->weeklyContent->week_number ?? 0,
                    'lesson' => $c->weeklyContent->video_title ?? 'Lesson',
                    'snippet' => substr($c->content, 0, 150) . '...',
                    'similarity' => isset($c->similarity) ? round($c->similarity, 3) : 'SQL Search',
                ])->toArray()
            ]) . "\n\n";

            if (ob_get_level() > 0) ob_flush();
            flush();

            // Stream text delta tokens
            foreach ($streamable as $event) {
                if ($event->type() === 'text_delta') {
                    echo "data: " . json_encode([
                        'type' => 'text_delta',
                        'text' => $event->delta
                    ]) . "\n\n";

                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            }

            echo "data: [DONE]\n\n";
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
