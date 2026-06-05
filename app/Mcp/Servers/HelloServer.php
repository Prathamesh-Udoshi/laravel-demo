<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use Laravel\Mcp\Server\Attributes\Instructions;
use App\Mcp\Tools\HelloTool;
use App\Mcp\Tools\WeatherTool;
use App\Mcp\Prompts\DescribeWeatherPrompt;
use App\Mcp\Resources\WeatherGuidelinesResource;


#[Name('hello-server')]
#[Version('0.0.1')]
#[Instructions('Simple hello world MCP server and a weather tool server.')]
class HelloServer extends Server
{
    protected array $tools = [
        HelloTool::class,
        WeatherTool::class,
    ];

    protected array $resources = [
        WeatherGuidelinesResource::class
    ];

    protected array $prompts = [
        DescribeWeatherPrompt::class,
    ];
}