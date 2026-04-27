<?php

namespace App\Jobs;

use App\Services\AiServiceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class ProcessBulkAiTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Lab 8 Resilience: Extended timeout and retries for LLM stability.
     */
    public $timeout = 120; 
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $prompt, 
        public string $featureName,
        public int $targetModelId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AiServiceManager $aiManager): void
    {
        try {
            // 1. Generate the content via the AI Fallback Manager
            $generatedContent = $aiManager->generateWithFallback($this->prompt, $this->featureName);

            // Debugging: Log the length of the response
            Log::info("AI processing complete for Book #{$this->targetModelId}. Content length: " . strlen($generatedContent));

            // 2. Find the book record
            $book = Book::find($this->targetModelId);
            
            if ($book && !empty($generatedContent)) {
                // 3. Save to Database
                $book->update(['ai_summary' => $generatedContent]);

                /* | LABORATORY 7 & 8 INTEGRATION:
                | 1. Forget the polling flag so the pulsing UI placeholder vanishes instantly.
                | 2. Flush the book tags so the high-speed Redis cache serves fresh data.
                */
                Cache::forget("ai_processing_{$book->id}");
                Cache::tags(["book:{$book->id}"])->flush();
                
                Log::info("AI Summary updated and cache cleared for Book #{$book->id}");
            } else {
                Log::warning("AI Task skipped: Book not found or AI returned empty content for ID {$this->targetModelId}.");
                Cache::forget("ai_processing_{$this->targetModelId}");
            }
            
        } catch (\Exception $e) {
            Log::error("Bulk AI Task Failed: " . $e->getMessage());
            
            // Clean up the UI flag so the user doesn't see a "loading" state forever
            Cache::forget("ai_processing_{$this->targetModelId}");
            
            throw $e; 
        }
    }
}