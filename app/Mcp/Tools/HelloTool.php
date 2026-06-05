<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Description;

#[Name('hello_tool')]
#[Description('Returns a hello message')]
class HelloTool extends Tool
{
    public function handle(Request $request): Response
    {
        return Response::text('Hello from Laravel MCP!');
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

}