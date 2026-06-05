<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TweetGeneratorController extends Controller
{
    /**
     * Show the tweet generator form.
     */
    public function showForm()
    {
        return view('tweet-generator.form');
    }

    /**
     * Generate a tweet based on the given topic.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'tone' => 'required|in:casual,professional,humorous,inspirational',
        ]);

        try {
            // Call Groq API directly to generate tweet
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert tweet writer. Generate a single tweet about the given topic. Keep it under 280 characters. The tone should be {$validated['tone']}. Include relevant hashtags and emojis if appropriate. Return ONLY the tweet text, nothing else.",
                    ],
                    [
                        'role' => 'user',
                        'content' => "Generate a tweet about: {$validated['topic']}",
                    ],
                ],
                'max_tokens' => 150,
            ]);

            if ($response->successful()) {
                $tweet = trim($response->json('choices.0.message.content'));

                return response()->json([
                    'success' => true,
                    'tweet' => $tweet,
                    'topic' => $validated['topic'],
                    'tone' => $validated['tone'],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Groq API error: ' . $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating tweet: ' . $e->getMessage(),
            ], 500);
        }
    }
}
