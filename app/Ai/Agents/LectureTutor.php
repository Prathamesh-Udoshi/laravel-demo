<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Attributes\Provider;
use Stringable;

#[Provider('openai')]
class LectureTutor implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * The dynamic lecture context retrieved from vector similarity search.
     */
    protected string $lectureContext = '';

    /**
     * Whether the agent is running on a local, small model.
     */
    protected bool $isLocal = false;

    /**
     * Set whether the agent is running on a local, small model.
     */
    public function setIsLocal(bool $isLocal): self
    {
        $this->isLocal = $isLocal;

        return $this;
    }

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

        if ($this->isLocal) {
            return <<<PROMPT
You are an AI Lecture Tutor. Answer the student's question using ONLY the retrieved context below.

RELEVANT CONTEXT:
{$contextSection}

Instructions:
1. If the student's question is covered in the context, answer it using only facts from the context.
2. If the student's question is NOT covered in the context, or if the context is empty (indicated by the CRITICAL tag above), you MUST politely decline to answer. Say exactly: "I am only authorized to answer questions directly related to your courses."
3. Do NOT make up any information, do NOT guess, and do NOT use general knowledge.
PROMPT;
        }

        return <<<PROMPT
You are a knowledgeable and supportive AI Lecture Tutor for our online learning platform. 
Your goal is to help students understand the course material by answering their questions *strictly* using the lecture notes, video transcripts, and summaries provided.

Here is the retrieved context from the course lessons related to the student's question:
{$contextSection}

Instructions:
1. Directly answer the student's question using the provided lecture context if possible.
2. CRITICAL: You MUST explicitly state at the beginning or end of your answer which Week and Lecture/Lesson name the answer was sourced from (e.g., "This was covered in Week X, Lecture: Y...").
3. If the student's message is a greeting (e.g., "hello", "hi", "hey"), greet them warmly and tell them you are ready to answer questions about their courses.
4. If the student asks about a topic that is not covered in the provided context, or if no relevant context was found (indicated by the CRITICAL tag above), you MUST politely decline to answer. State clearly that you are only authorized to answer questions directly related to the courses and lectures currently registered in the Course Planner.
5. Do NOT use your general knowledge to answer questions that are not supported by the lecture context.
6. Be friendly, encouraging, and clear.
7. Include simple code examples or bullet points if it helps explain technical concepts found in the context.
8. Do NOT use markdown headers (e.g., #, ##, ###) in your response. To structure your answer, use bold text (e.g., **Heading**) or simple bullet points instead.

Remember to ALWAYS explicitly cite the Week and Lecture/Lesson name in your response (e.g. "This topic is covered in Week X, Lecture: Y"). This is mandatory!
PROMPT;
    }
}
