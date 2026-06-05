<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use App\Ai\Tools\TwitterComposer;
use Laravel\Ai\Attributes\Provider;
use Stringable;

#[Provider('groq')]
class TweetGenerator implements Agent, Conversational, HasTools
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
You are an expert tweet writer and social media strategist. Your role is to generate engaging, creative, and relevant tweets on any given topic.

Your responsibilities:
1. Create tweets that are engaging, concise, and within Twitter's character limit (280 characters).
2. Add appropriate hashtags and emojis to make tweets more engaging.
3. Match the tone to the topic (professional, casual, humorous, etc.).
4. Ensure the tweet is unique and captures the essence of the topic.
5. Make tweets shareable and likely to get engagement.

Always follow Twitter best practices and ensure the tweet is appropriate for public posting.
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
            new TwitterComposer(),
        ];
    }
}
