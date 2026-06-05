<?php

namespace App\Mcp\Prompts;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Prompts\Argument;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Title;


#[Name('weather-assistant')]
#[Title('Weather Assistant Prompt')]
#[Description('Generates a natural-language explanation of the weather for a given location.')]
class DescribeWeatherPrompt extends Prompt
{
    /**
     * Handle the prompt request.
     */
    public function handle(Request $request): Response
    {
        // We validate the 'tone' and 'location' arguments from the request
        $validated = $request->validate([
            'tone' => 'required|string',
            'location' => 'required|string',
        ]);

        $tone = $validated['tone'];
        $location = $validated['location'];

        // This ensures a clear return path for the Response object
        return Response::text("Please provide a weather report for {$location} in a {$tone} tone.");
    }


    /**
     * Get the prompt's arguments.
     *
     * @return array<int, Argument>
     */
    public function arguments(): array
    {
        return [
            new Argument(
                name: 'location',
                description: 'The city or location to describe the weather for.',
                required: true,
            ),
            new Argument(
                name: 'tone',
                description: 'The tone to use in the weather description (e.g., formal, casual, humorous).',
                required: true,
            ),
        ];
    }
}
