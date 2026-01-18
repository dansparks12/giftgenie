<?php

namespace App\Http\Controllers;

use OpenAI;
use OpenAI\Exceptions\RateLimitException;
use Throwable;

class AIHealthController extends Controller
{
    public function test()
    {
        if (!config('services.openai.key')) {
            return response()->json([
                'status' => 'error',
                'message' => 'OpenAI key missing'
            ], 500);
        }

        try {
            $client = OpenAI::client(config('services.openai.key'));

            $response = $client->responses()->create([
                'model' => 'gpt-4.1-mini',
                'input' => 'Reply with the words: AI connected'
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => trim($response->output_text)
            ]);

        } catch (RateLimitException $e) {
            return response()->json([
                'status' => 'ok',
                'message' => 'AI connected (rate limited)'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}