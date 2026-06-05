<?php

namespace App\Support;

use function Laravel\Ai\agent;

class AI
{
    /**
     * Create a new conversational AI agent.
     *
     * @param string $instructions
     * @return \Laravel\Ai\Contracts\Agent
     */
    public static function chat(string $instructions = 'You are a helpful assistant.')
    {
        // We use the anonymous agent feature from Laravel AI SDK
        return agent(instructions: $instructions);
    }
}
