<?php

namespace App\Ai\Agents;

use App\Services\AiServiceManager;
use App\Models\Book;

class ReviewAnalystAgent
{
    public function __construct(protected AiServiceManager $aiManager) {}

    public function summarizeReviews(Book $book): string
    {
        $reviews = $book->reviews()->latest()->limit(15)->pluck('comment');

        if ($reviews->isEmpty()) {
            return "Not enough reviews to generate an analysis.";
        }

        $reviewsText = $reviews->map(fn($review, $index) => ($index + 1) . ". {$review}")->join("\n");

        $prompt = "You are a data analyst for a bookstore. Analyze the following user reviews for the book '{$book->title}'.\n\n" .
                  "Provide a short, 3-sentence summary of the overall sentiment, highlighting any recurring praises or complaints.\n\n" .
                  "Reviews:\n{$reviewsText}";

        // Call Gemini
        return $this->aiManager->generate($prompt);
    }
}