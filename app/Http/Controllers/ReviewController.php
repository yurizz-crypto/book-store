<?php

namespace App\Http\Controllers;

use App\Events\ReviewSubmitted;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Book $book)
    {
        // Ideally, extract this validation to a StoreReviewRequest
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // CLEAN CODE: Logic is now perfectly readable and encapsulated
        if (!$user->isAdmin() && !$user->hasPurchasedBook($book)) {
            return back()->with('error', 'You must have a completed order for this book to leave a review.');
        }

        $review = Review::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['rating' => $validated['rating'], 'comment' => $validated['comment']]
        );

        event(new ReviewSubmitted($review));

        return redirect()->route('books.show', $book)
            ->with('success', 'Review processed successfully!');
    }

    public function destroy(Review $review)
    {
        // CLEAN CODE: Defer to the ReviewPolicy for consistency
        $this->authorize('delete', $review);

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }
}