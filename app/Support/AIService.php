<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Get the active LLM provider.
     */
    protected static function getProvider()
    {
        return env('AI_DEFAULT_PROVIDER', 'groq');
    }

    /**
     * Summarize a single weekly transcript or generate an NPTEL-style curriculum summary if rate-limited.
     */
    public function summarizeWeek($weekNumber, $lectureTitle, $transcript)
    {
        if (empty($transcript)) {
            $prompt = "You are a senior university professor and expert curriculum designer for Visvesvaraya Technological University (VTU) and NPTEL.
            I need a highly detailed, academically rigorous summary of this specific lecture: 'Week {$weekNumber} - {$lectureTitle}'.
            
            Based on your extensive knowledge of standard university curricula for this subject, write a dense summary containing 3-4 professional bullet points detailing the core concepts, algorithms, frameworks, or models that are standardly taught in this specific lecture.
            
            Do NOT mention that a transcript was missing or that this is a placeholder. Write it as a definitive, high-fidelity lecture syllabus summary. Keep it highly educational and under 150 words.";
            
            return $this->callLLM($prompt, "You are a professional university professor designing syllabus summaries.");
        }

        // Limit transcript length to avoid blowing up tokens
        $trimmedTranscript = substr($transcript, 0, 15000);

        $prompt = "You are an expert academic curriculum summarizer. Summarize the following lecture transcript into 3-4 dense, highly educational bullet points detailing the key concepts, algorithms, or theories taught. 
        Lecture Title: Week {$weekNumber} - {$lectureTitle}
        
        Transcript:
        {$trimmedTranscript}
        
        Return ONLY the summary bullet points. Keep it under 200 words.";

        return $this->callLLM($prompt, "You are a concise academic assistant.");
    }

    /**
     * Generate 10 comprehensive MCQs for either Mid-Term or End-Term.
     */
    public function generateQuiz($courseTitle, $evaluationType, $aggregatedContent)
    {
        $termLabel = $evaluationType === 'midterm' ? 'Mid-Term Evaluation (First 50% of Course)' : 'End-Term Evaluation (Remaining Course)';
        
        $prompt = "You are a senior university professor for Visvesvaraya Technological University (VTU). 
        Your task is to generate a comprehensive, highly rigorous Quiz containing exactly 10 Multiple-Choice Questions (MCQs) for the {$termLabel} of the course: '{$courseTitle}'.
        
        Base the questions STRICTLY on the following weekly course curriculum summaries:
        {$aggregatedContent}
        
        Academic requirements:
        1. Distribute questions evenly across the provided weekly topics.
        2. Ensure a mix of conceptual, analytical, and practical questions.
        3. Options must be clear, distinct, and unambiguous.
        4. Include a detailed, professional explanation for each answer.

        You MUST respond with a valid JSON object ONLY. Do not write any preambles, intros, or post-explanations.
        The JSON format must match this structure exactly:
        {
          \"questions\": [
            {
              \"question\": \"Question text here\",
              \"A\": \"Option A text\",
              \"B\": \"Option B text\",
              \"C\": \"Option C text\",
              \"D\": \"Option D text\",
              \"correct\": \"A\", 
              \"explanation\": \"Explain why the correct answer is correct based on academic theory.\"
            }
          ]
        }
        Ensure \"correct\" is strictly one character: 'A', 'B', 'C', or 'D'. Make sure to output exactly 10 questions.";

        $system = "You are a professional university professor generating structured JSON exam papers.";
        $response = $this->callLLM($prompt, $system, true);

        // Sanitize LLM response (in case it returns markdown wrappers like ```json ... ```)
        $cleanResponse = $this->cleanJsonResponse($response);
        $decoded = json_decode($cleanResponse, true);

        if (!$decoded || !isset($decoded['questions']) || !is_array($decoded['questions'])) {
            Log::error("Failed to parse AI Quiz JSON.", ['response' => $response]);
            return null;
        }

        return $decoded['questions'];
    }

    /**
     * Generate 2 comprehensive descriptive assignment tasks.
     */
    public function generateAssignment($courseTitle, $evaluationType, $aggregatedContent)
    {
        $termLabel = $evaluationType === 'midterm' ? 'Mid-Term Assessment' : 'End-Term Assessment';

        $prompt = "You are a senior university professor designing subjective/descriptive assignments for the course: '{$courseTitle}'.
        Generate a comprehensive, challenging homework assignment representing the '{$termLabel}' of this course.
        
        Base the assignment on this weekly curriculum:
        {$aggregatedContent}
        
        Provide:
        1. A compelling Title (e.g. 'Project: Implementation of Balanced Binary Search Trees' or 'Case Study: Network Architecture Analysis').
        2. A structured set of 3-4 descriptive assignment tasks, showing instructions, guidelines, and what students must submit.

        You MUST respond with a valid JSON object ONLY. Do not write any preambles or chat conversational filler.
        The JSON format must match this structure exactly:
        {
          \"title\": \"Assignment Title here\",
          \"instructions\": \"Write 3-4 subjective essay or programming tasks here. Use bullet points or numbers. Explain what they need to do, the guidelines, and grading rubrics.\"
        }";

        $system = "You are a university professor designing structured descriptive assignments in JSON format.";
        $response = $this->callLLM($prompt, $system, true);

        $cleanResponse = $this->cleanJsonResponse($response);
        $decoded = json_decode($cleanResponse, true);

        if (!$decoded || !isset($decoded['title']) || !isset($decoded['instructions'])) {
            Log::error("Failed to parse AI Assignment JSON.", ['response' => $response]);
            return null;
        }

        return $decoded;
    }

    /**
     * Helper to clean up Markdown-wrapped JSON response from LLMs.
     */
    private function cleanJsonResponse($string)
    {
        $string = trim($string);
        if (str_starts_with($string, '```json')) {
            $string = substr($string, 7);
        } elseif (str_starts_with($string, '```')) {
            $string = substr($string, 3);
        }
        if (str_ends_with($string, '```')) {
            $string = substr($string, 0, -3);
        }
        return trim($string);
    }

    /**
     * General function to invoke either Groq or Gemini API with fallback.
     */
    protected function callLLM($prompt, $system = "You are a helpful assistant.", $isJson = false)
    {
        // Try Groq First
        try {
            $response = $this->callGroq($prompt, $system, $isJson);
            if ($response) {
                return $response;
            }
        } catch (\Exception $e) {
            Log::warning("Groq API failed. Falling back to Gemini.", ['error' => $e->getMessage()]);
        }

        // Fallback to Gemini
        try {
            $response = $this->callGemini($prompt, $system, $isJson);
            if ($response) {
                return $response;
            }
        } catch (\Exception $e) {
            Log::error("Both Groq and Gemini APIs failed.", ['error' => $e->getMessage()]);
        }

        throw new \Exception("AI service unavailable. Please check your API keys in the .env file.");
    }

    /**
     * Call Groq API with multi-model fallback.
     */
    private function callGroq($prompt, $system, $isJson)
    {
        $apiKey = config('services.groq.api_key') ?? env('GROQ_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        $models = [
            'llama-3.3-70b-versatile',
            'llama-3.1-8b-instant',
            'llama3-8b-8192'
        ];

        $lastException = null;

        foreach ($models as $modelName) {
            try {
                $body = [
                    'model' => $modelName,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 1500,
                ];

                if ($isJson) {
                    $body['response_format'] = ['type' => 'json_object'];
                }

                $res = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(12)->post('https://api.groq.com/openai/v1/chat/completions', $body);

                if ($res->successful()) {
                    $content = $res->json('choices.0.message.content');
                    if ($content) {
                        return $content;
                    }
                }
                
                Log::warning("Groq failed for model {$modelName}: " . $res->body());
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Groq exception for model {$modelName}: " . $e->getMessage());
            }
        }

        throw new \Exception("Groq HTTP Error: " . ($lastException ? $lastException->getMessage() : "Unknown error"));
    }

    /**
     * Call Gemini API with multiple endpoints and model fallback.
     */
    private function callGemini($prompt, $system, $isJson)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return null;
        }

        $models = [
            'v1/models/gemini-1.5-flash',
            'v1beta/models/gemini-1.5-flash-latest',
            'v1/models/gemini-pro',
        ];

        $lastException = null;

        foreach ($models as $modelPath) {
            try {
                $url = "https://generativelanguage.googleapis.com/{$modelPath}:generateContent?key=" . $apiKey;

                $body = [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $system . "\n\n" . $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 2000,
                        'temperature' => 0.2,
                    ]
                ];

                if ($isJson) {
                    $body['generationConfig']['responseMimeType'] = 'application/json';
                }

                $res = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->timeout(12)->post($url, $body);

                if ($res->successful()) {
                    $text = $res->json('candidates.0.content.parts.0.text');
                    if ($text) {
                        return $text;
                    }
                }
                
                Log::warning("Gemini failed for model {$modelPath}: " . $res->body());
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Gemini exception for model {$modelPath}: " . $e->getMessage());
            }
        }

        throw new \Exception("Gemini HTTP Error: " . ($lastException ? $lastException->getMessage() : "Unknown error"));
    }
}
