<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiServiceManager
{
    /**
     * Generate a response strictly using Google Gemini.
     */
    public function generate(string $prompt): string 
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            Log::error('Gemini API key is missing from .env');
            return "Error: AI Service is not configured properly. Missing API Key.";
        }

        try {
            // Dynamically grab the model from .env, defaulting to 2.5-flash if missing
            $model = env('GEMINI_MODEL', 'gemini-2.5-flash');
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // Make the POST request to the Gemini API
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

            // Check if the request was successful
            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            // Log the error if the API rejected the request
            Log::error('Gemini API Error: ' . $response->body());
            return "Sorry, the AI is currently unavailable. Please try again later.";

        } catch (\Exception $e) {
            // Log any connection timeouts or fatal errors
            Log::error("AI Connection failed: " . $e->getMessage());
            return "Sorry, there was a problem connecting to the AI service.";
        }
    }

    /**
     * Alias for legacy calls that expected a fallback chain.
     * Routes directly to Gemini.
     */
    public function generateWithFallback(string $prompt): string
    {
        return $this->generate($prompt);
    }
}