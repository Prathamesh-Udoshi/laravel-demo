<?php

namespace App\Mcp\Tools;

use Laravel\Mcp\Server\Attributes\Name;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



#[Name('get_weather')]
#[Description('Get the current weather and forecast for any location worldwide, including temperature, conditions, and humidity.')]

class WeatherTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'location' => 'required|string|max:100',
            'units' => 'in:celsius,fahrenheit',
        ]);

        $location = $validated['location'];
        $units = $validated['units'] ?? 'celsius';

        try {
            // 1. Geocode location to get coordinates
            $geoResponse = Http::withoutVerifying()->timeout(10)->get("https://geocoding-api.open-meteo.com/v1/search", [

                'name' => $location,
                'count' => 1,
                'language' => 'en',
                'format' => 'json',
            ]);

            if (!$geoResponse->successful() || empty($geoResponse->json('results'))) {
                return Response::json([
                    'temperature' => 0,
                    'conditions' => 'Location not found',
                    'humidity' => 0
                ]);
            }

            $geo = $geoResponse->json('results.0');
            $lat = $geo['latitude'];
            $lon = $geo['longitude'];

            // 2. Fetch current weather data
            $weatherResponse = Http::withoutVerifying()->timeout(10)->get("https://api.open-meteo.com/v1/forecast", [

                'latitude' => $lat,
                'longitude' => $lon,
                'current' => 'temperature_2m,relative_humidity_2m,weather_code',
                'temperature_unit' => $units,
            ]);

            if (!$weatherResponse->successful()) {
                return Response::json([
                    'temperature' => 0,
                    'conditions' => 'Weather service unavailable',
                    'humidity' => 0
                ]);
            }

            $current = $weatherResponse->json('current');

            return Response::json([
                'temperature' => $current['temperature_2m'],
                'conditions' => $this->mapWeatherCode($current['weather_code']),
                'humidity' => $current['relative_humidity_2m'],
            ]);
        } catch (\Exception $e) {
            Log::error("WeatherTool Error: " . $e->getMessage(), [
                'location' => $location,
                'exception' => $e
            ]);

            return Response::json([

                'temperature' => 0,
                'conditions' => 'Error: ' . $e->getMessage(),
                'humidity' => 0
            ]);
        }
    }


    /**
     * Map WMO Weather interpretation codes to human readable strings.
     */
    private function mapWeatherCode(int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1, 2, 3 => 'Mainly clear, partly cloudy, and overcast',
            45, 48 => 'Fog and depositing rime fog',
            51, 53, 55 => 'Drizzle: Light, moderate, and dense intensity',
            56, 57 => 'Freezing Drizzle: Light and dense intensity',
            61, 63, 65 => 'Rain: Slight, moderate and heavy intensity',
            66, 67 => 'Freezing Rain: Light and heavy intensity',
            71, 73, 75 => 'Snow fall: Slight, moderate, and heavy intensity',
            77 => 'Snow grains',
            80, 81, 82 => 'Rain showers: Slight, moderate, and violent',
            85, 86 => 'Snow showers slight and heavy',
            95 => 'Thunderstorm: Slight or moderate',
            96, 99 => 'Thunderstorm with slight and heavy hail',
            default => 'Unknown',
        };
    }


    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'location' => $schema->string()
                ->description('The location to get the weather forecast for.')
                ->required(),

            'units' => $schema->string()
                ->enum(['celsius', 'fahrenheit'])
                ->description('The temparature units to use.')
                ->default('celsius'),
        ];
    }

    public function outputSchema(JsonSchema $schema): array
    {
        return [
            'temperature' => $schema->number()
                ->description('Temperature in the requested unit')
                ->required(),

            'conditions' => $schema->string()
                ->description('Weather conditions')
                ->required(),

            'humidity' => $schema->integer()
                ->description('Humidity percentage')
                ->required(),
        ];
    }

}
