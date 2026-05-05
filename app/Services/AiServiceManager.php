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
            // Make the POST request to the Gemini 1.5 Flash API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
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