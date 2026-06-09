<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Attributes\Provider;
use Stringable;

#[Provider('gemini')]
class LectureTutor implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * The dynamic lecture context retrieved from vector similarity search.
     */
    protected string $lectureContext = '';

    /**
     * Set the retrieved lecture context to guide the tutor's answers.
     */
    public function setLectureContext(string $context): self
    {
        $this->lectureContext = $context;

        return $this;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $contextSection = $this->lectureContext 
            ? "RELEVANT LECTURE TRANSCRIPTS & NOTES:\n---\n{$this->lectureContext}\n---"
            : "CRITICAL: No relevant course lecture material was found in the database for this question.";

        return <<<PROMPT
You are a knowledgeable and supportive AI Lecture Tutor for our online learning platform. 
Your goal is to help students understand the course material by answering their questions *strictly* using the lecture notes, video transcripts, and summaries provided.

Here is the retrieved context from the course lessons related to the student's question:
{$contextSection}

Instructions:
1. Directly answer the student's question using the provided lecture context if possible. Reference specific lessons, weeks, or quotes.
2. If the student's message is a greeting (e.g., "hello", "hi", "hey"), greet them warmly and tell them you are ready to answer questions about their courses.
3. If the student asks about a topic that is not covered in the provided context, or if no relevant context was found (indicated by the CRITICAL tag above), you MUST politely decline to answer. State clearly that you are only authorized to answer questions directly related to the courses and lectures currently registered in the Course Planner.
4. Do NOT use your general knowledge to answer questions that are not supported by the lecture context.
5. Be friendly, encouraging, and clear.
6. Include simple code examples or bullet points if it helps explain technical concepts found in the context.
PROMPT;
    }
}
