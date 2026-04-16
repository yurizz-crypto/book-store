<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\UserActionNotification;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Book $book)
    {
        $this->authorize('create', [Review::class, $book]);
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $hasCompletedOrder = Auth::user()->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', fn($q) => $q->where('book_id', $book->id))
            ->exists();

        if (!$hasCompletedOrder && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You must have a completed order for this book to leave a review.');
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewReviewAlert($review));

        return redirect()->route('books.show', $book)
            ->with('success', 'Review processed successfully!');
    }

    public function destroy(Review $review)
    {
        if (Auth::id() !== $review->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }
}