<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Attributes\Uri;
use Laravel\Mcp\Server\Attributes\MimeType;

#[Name('weather-api-docs')]
#[Title('Weather API Documentation')]
#[Description('Comprehensive guidelines for using the Weather API.')]
#[MimeType('text/markdown')]
#[Uri('mcp://weather/guidelines')]
class WeatherGuidelinesResource extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $filePath = base_path("resources/weather_api_documentation.md");

        if (!file_exists($filePath)) {
            return Response::text("Resource not found.");
        }

        $content = file_get_contents($filePath);

        return Response::text($content);
    }
}
