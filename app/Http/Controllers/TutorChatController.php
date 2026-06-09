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
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'course_id' => 'nullable|integer',
            'conversation_id' => 'nullable|string',
        ]);

        $question = $request->input('message');
        $courseId = $request->input('course_id');

        // 1. Retrieve matching chunks using the custom semantic search (with pgvector/PHP fallback)
        $chunks = WeeklyContentChunk::searchSimilar(
            query: $question,
            minSimilarity: 0.22,
            limit: 3,
            courseId: $courseId
        );

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

        $conversationId = $request->input('conversation_id');

        try {
            if ($conversationId) {
                $response = $agent->continue($conversationId, as: $user)->prompt($question);
            } else {
                $response = $agent->forUser($user)->prompt($question);
                $conversationId = $response->conversationId;
            }

            $reply = $response->text;
        } catch (\Exception $e) {
            logger()->error('Tutor Chat Agent Error: ' . $e->getMessage());
            $reply = "I'm sorry, I encountered an error when communicating with the AI service. Details: " . $e->getMessage();
        }

        // 6. Return response + JSON details of what context was referenced
        return response()->json([
            'reply' => $reply,
            'conversation_id' => $conversationId,
            'context_used' => $chunks->map(fn ($c) => [
                'course' => $c->weeklyContent->course->title ?? 'Unknown',
                'week' => $c->weeklyContent->week_number ?? 0,
                'lesson' => $c->weeklyContent->video_title ?? 'Lesson',
                'snippet' => substr($c->content, 0, 150) . '...',
                'similarity' => isset($c->similarity) ? round($c->similarity, 3) : 'SQL Search',
            ]),
        ]);
    }
}
