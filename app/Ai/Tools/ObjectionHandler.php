<?php

namespace App\Ai\Tools;

use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Stringable;

class ObjectionHandler implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Provides best-practice rebuttals for common sales objections.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $objection = (string) $request['objection'];

        $rebuttals = [
            'price' => 'Focus on ROI. Ask: "If we could prove this pays for itself in 6 months, would price still be the main concern?"',
            'timing' => 'Ask about the cost of inaction. "What happens if this problem isn\'t solved by next quarter?"',
            'competition' => 'Don\'t badmouth. Highlight your unique differentiators and ask: "What specifically are you looking for in a partner that you feel is missing?"',
            'authority' => 'Help them sell internally. "Who else besides yourself would be involved in making this decision, and what do they care about most?"',
        ];

        return $rebuttals[strtolower($objection)] ?? "I don't have a specific rebuttal for '{$objection}', but generally, try to ask an open-ended question to understand the root cause.";
    }

    /**
     * Get the tool's schema definition.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'objection' => $schema->string()
                ->description('The type of objection (e.g., price, timing, competition, authority)')
                ->required(),
        ];
    }
}
