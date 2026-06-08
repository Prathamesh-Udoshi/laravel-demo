<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Http\Client\Events\ConnectionFailed;
use App\Support\AI;

class AIContextController extends Controller
{
    /**
     * Render the main inspector/playground page.
     */
    public function index()
    {
        return view('ai-context.inspector');
    }

    /**
     * Send a prompt to the AI agent, tracking custom Laravel Context and API payloads.
     */
    public function send(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'system_context' => 'nullable|string',
            'provider' => 'required|string|in:groq,gemini',
            'custom_context' => 'nullable|array',
            'custom_context.*.key' => 'required_with:custom_context.*.value|string|nullable',
            'custom_context.*.value' => 'required_with:custom_context.*.key|string|nullable',
        ]);

        // 1. Register custom key-value pairs in Laravel's Context
        $customContext = [];
        if ($request->has('custom_context')) {
            foreach ($request->input('custom_context') as $pair) {
                if (!empty($pair['key'])) {
                    Context::add($pair['key'], $pair['value']);
                    $customContext[$pair['key']] = $pair['value'];
                }
            }
        }
        
        // Add request parameters to Context for tracking
        Context::add('_prompt', $request->input('prompt'));
        Context::add('_system_context', $request->input('system_context') ?? 'You are a helpful assistant.');
        Context::add('_provider', $request->input('provider'));

        // 2. Set up HTTP Client Event listeners to trace outbound LLM API payloads during this request
        Context::add('_http_calls', []);
        $requestsMapping = [];

        // Catch outbound requests before they are sent
        Event::listen(RequestSending::class, function (RequestSending $event) use (&$requestsMapping) {
            $req = $event->request;
            $headers = $req->headers();
            foreach (['Authorization', 'authorization', 'x-goog-api-key', 'X-Goog-Api-Key'] as $authKey) {
                if (isset($headers[$authKey])) {
                    $headers[$authKey] = ['[MASKED]'];
                }
            }

            // Mask Gemini API Key parameter in URL
            $url = $req->url();
            $url = preg_replace('/([?&]key=)[^&]+/', '$1[MASKED]', $url);

            $reqBody = $req->body();
            $decodedReq = json_decode($reqBody, true);

            $callData = [
                'url' => $url,
                'method' => $req->method(),
                'request_headers' => $headers,
                'request_body' => $decodedReq ?? $reqBody,
                'response_status' => null,
                'response_headers' => [],
                'response_body' => null,
            ];

            $hash = spl_object_hash($req);
            $requestsMapping[$hash] = $callData;
        });

        // Catch response when it returns successfully
        Event::listen(ResponseReceived::class, function (ResponseReceived $event) use (&$requestsMapping) {
            $hash = spl_object_hash($event->request);
            if (isset($requestsMapping[$hash])) {
                $res = $event->response;
                $resBody = $res->body();
                $decodedRes = json_decode($resBody, true);

                // Hide thoughtSignature and signature values from the parsed JSON response
                if (is_array($decodedRes)) {
                    array_walk_recursive($decodedRes, function (&$val, $key) {
                        if (in_array($key, ['thoughtSignature', 'signature', 'thought_signature'])) {
                            $val = '[HIDDEN]';
                        }
                    });
                }

                $requestsMapping[$hash]['response_status'] = $res->status();
                $requestsMapping[$hash]['response_headers'] = $res->headers();
                $requestsMapping[$hash]['response_body'] = $decodedRes ?? $resBody;

                $calls = Context::get('_http_calls') ?? [];
                $calls[] = $requestsMapping[$hash];
                Context::add('_http_calls', $calls);
            }
        });

        // Catch connection failures (e.g. timeout)
        Event::listen(ConnectionFailed::class, function (ConnectionFailed $event) use (&$requestsMapping) {
            $hash = spl_object_hash($event->request);
            if (isset($requestsMapping[$hash])) {
                $requestsMapping[$hash]['response_status'] = 'Connection Failed';

                $calls = Context::get('_http_calls') ?? [];
                $calls[] = $requestsMapping[$hash];
                Context::add('_http_calls', $calls);
            }
        });

        // 3. Invoke the AI Agent using Laravel AI SDK
        $aiResponseText = '';
        $error = null;

        try {
            $systemInstructions = Context::get('_system_context');
            $promptText = Context::get('_prompt');
            $provider = Context::get('_provider');

            $agent = AI::chat(instructions: $systemInstructions);
            
            // Map default models for providers if needed
            $model = null;
            if ($provider === 'groq') {
                $model = 'llama-3.1-8b-instant';
            } elseif ($provider === 'gemini') {
                $model = null;
            }

            $response = $agent->prompt($promptText, provider: $provider, model: $model);
            $aiResponseText = $response->text;

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        // 4. Retrieve tracing info from Context
        $allContext = Context::all();
        $httpCalls = Context::get('_http_calls') ?? [];

        // Clean internal helper keys from response display of custom context
        $cleanContext = array_filter($allContext, function ($key) {
            return !str_starts_with($key, '_');
        }, ARRAY_FILTER_USE_KEY);

        return response()->json([
            'success' => $error === null,
            'response' => $aiResponseText,
            'error' => $error,
            'laravel_context' => $cleanContext,
            'http_calls' => $httpCalls,
        ]);
    }
}
