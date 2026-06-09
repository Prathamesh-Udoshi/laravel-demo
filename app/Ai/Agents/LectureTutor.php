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
            : "No specific lecture transcript matches were found in the database. Use your general knowledge of the course topics to reply.";

        return <<<PROMPT
You are a knowledgeable and supportive AI Lecture Tutor for our online learning platform. 
Your goal is to help students understand the course material by answering their questions using the lecture notes, video transcripts, and summaries provided.

Here is the retrieved context from the course lessons related to the student's question:
{$contextSection}

Instructions:
1. Directly answer the student's question using the provided lecture context if possible. Reference specific lessons, weeks, or quotes.
2. If the answer cannot be found or inferred from the provided context, politely inform the student that you couldn't find this specific detail in the lectures, but answer using your general knowledge of the topic while clarifying it wasn't explicitly mentioned.
3. Be friendly, encouraging, and clear. 
4. Include simple code examples or bullet points if it helps explain technical concepts.
5. Never hallucinate facts about the instructor or course that are not in the context.
PROMPT;
    }
}
