<?php

namespace App\Http\Controllers;

use App\Events\ReviewSubmitted;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->hasPurchasedBook($book)) {
            return back()->with('error', 'You must have a completed order for this book to leave a review.');
        }

        $review = Review::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['rating' => $validated['rating'], 'comment' => $validated['comment']]
        );

        // - Clear the specific book's cache so the new review appears instantly
        Cache::tags(["book:{$book->id}"])->flush();

        event(new ReviewSubmitted($review));

        return redirect()->route('books.show', $book)
            ->with('success', 'Review processed successfully!');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $book = $review->book;

        // - Clear the cache before the review is gone to refresh the view
        Cache::tags(["book:{$book->id}"])->flush();

        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }

    /**
     * Trigger a background AI analysis of all reviews for a specific book.
     */
    public function analyzeBookReviews(Book $book)
    {
        // 1. Gather all reviews, including the star ratings for context
        $reviews = $book->reviews()->with('user')->get();
        
        if ($reviews->isEmpty()) {
            return back()->with('error', 'There are no reviews to analyze yet.');
        }

        // 2. Build a rich text block for the AI to read
        $reviewsText = $reviews->map(function($r) {
            return "Rating: {$r->rating}/5 stars - Review: \"{$r->comment}\"";
        })->implode("\n\n");

        // 3. Create a high-intensity prompt
        $prompt = "Analyze these customer reviews for the book '{$book->title}' by '{$book->author}':\n\n" . 
                  $reviewsText . "\n\n" .
                  "Task: Provide a detailed one-paragraph summary of what people are saying. " .
                  "If there are conflicting opinions, mention both. End with a recommendation on who would enjoy this book.";

        // 4. Dispatch the job (Lab 8)
        \App\Jobs\ProcessBulkAiTask::dispatch(
            $prompt, 
            "review_sentiment_analysis",
            $book->id
        )->onQueue('ai-tasks');

        // Set the UI flag so the placeholder shows up (from our previous step)
        \Illuminate\Support\Facades\Cache::put("ai_processing_{$book->id}", true, 300);

        return back()->with('success', 'PageTurner AI is now synthesizing a professional summary.');
    }

    public function checkAiStatus(Book $book)
    {
        // We return a JSON response so the JavaScript 'fetch' can read it
        return response()->json([
            'ready' => !is_null($book->ai_summary)
        ]);
    }
}