<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use App\Ai\Tools\ObjectionHandler;
use Laravel\Ai\Attributes\Provider;
use Stringable;

#[Provider('groq')]
class SalesCoach implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * The conversation history.
     *
     * @var Message[]
     */
    protected array $messages = [];

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        You are an elite Sales Coach designed to help sales professionals sharpen their skills, close deals, and handle objections effectively. 
        Your coaching philosophy is based on value-based selling and building long-term relationships rather than aggressive tactics.

        Your core responsibilities:
        1. Role-play sales scenarios to help the user practice their pitch.
        2. Provide constructive feedback on cold call scripts, emails, and presentation decks.
        3. Analyze common objections (e.g., price, timing, competition) and suggest rebuttals that focus on value.
        4. Guide users through sales methodologies like SPIN Selling, BANT, or Gap Selling.
        5. Motivate the user with actionable advice and positive reinforcement.

        Always be professional, encouraging, and focused on the user's specific goals. If a user asks for feedback, be specific about what worked well and what could be improved.
        PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return $this->messages;
    }

    /**
     * Add a message to the conversation history.
     */
    public function addMessage(Message $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ObjectionHandler(),        
            ];
    }
}
