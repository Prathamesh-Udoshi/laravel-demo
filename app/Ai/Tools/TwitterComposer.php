<?php

namespace App\Ai\Tools;

use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Stringable;

class TwitterComposer implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Generates engaging tweets on any given topic using AI.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $topic = (string) $request['topic'];
        $tone = (string) $request['tone'] ?? 'casual';

        // Call Groq API to generate tweet
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are an expert tweet writer. Generate a single tweet about the given topic. Keep it under 280 characters. The tone should be {$tone}. Include relevant hashtags and emojis if appropriate. Return ONLY the tweet text, nothing else.",
                ],
                [
                    'role' => 'user',
                    'content' => "Generate a tweet about: {$topic}",
                ],
            ],
            'max_tokens' => 150,
        ]);

        if ($response->successful()) {
            $content = $response->json('choices.0.message.content');
            return trim($content);
        }

        return "Unable to generate tweet. Please check your Groq API key configuration.";
    }

    /**
     * Get the tool's schema definition.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'topic' => $schema->string()
                ->description('The topic to generate a tweet about')
                ->required(),
            'tone' => $schema->string()
                ->description('The tone of the tweet (casual, professional, humorous, inspirational)')
                ->default('casual'),
        ];
    }
}
