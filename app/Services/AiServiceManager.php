<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AiServiceManager
{
    /**
     * Executes an AI prompt using a fallback chain to guarantee reliability.
     */
    public function generateWithFallback(string $prompt, string $featureName = 'general'): string
    {
        // Load fallback chain from config (e.g., ['openai', 'gemini', 'ollama'])
        $providers = config('ai.fallback_chain', ['openai', 'gemini', 'ollama']);

        foreach ($providers as $provider) {
            try {
                // Attempt to call the provider
                $response = $this->callProvider($provider, $prompt);

                // If successful, log the cost and usage for auditing
                $this->logUsage($provider, $featureName, $response['tokens_used']);

                // Log decision for compliance audit
                Log::channel('ai_audit')->info('AI Decision Executed', [
                    'feature' => $featureName,
                    'provider_used' => $provider,
                    'input_hash' => md5($prompt),
                    'timestamp' => now(),
                ]);

                return $response['text'];

            } catch (Exception $e) {
                // If the provider fails (rate limit, network error), log a warning and try the next one
                Log::warning("AI Provider [{$provider}] failed: " . $e->getMessage());
                continue; 
            }
        }

        // If all cloud AND local providers fail, throw a graceful exception
        throw new RuntimeException('All AI providers are currently unavailable. Please try again later.');
    }

    /**
     * Simulates the provider call. 
     * In a real app, this wraps the Laravel AI SDK or HTTP client.
     */
    private function callProvider(string $provider, string $prompt): array
    {
        // Logic to route the prompt to the correct SDK/Agent based on $provider
        // Returns the generated text and token count
        return [
            'text' => "Generated response from {$provider}",
            'tokens_used' => rand(50, 200), // Extracted from actual API response headers
        ];
    }

    /**
     * Records the token usage to the database for cost tracking.
     */
    private function logUsage(string $provider, string $feature, int $tokens): void
    {
        // Simple cost calculation logic (e.g., $0.0002 per 1k tokens)
        $ratePerThousand = match($provider) {
            'openai' => 0.0002,
            'gemini' => 0.0001,
            'ollama' => 0.0000, // Local is free!
            default => 0.0000,
        };

        DB::table('ai_usage_logs')->insert([
            'provider' => $provider,
            'feature' => $feature,
            'tokens_used' => $tokens,
            'cost_estimate' => ($tokens / 1000) * $ratePerThousand,
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}