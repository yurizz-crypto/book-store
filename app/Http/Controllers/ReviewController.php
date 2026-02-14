<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;


class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => $request->auth()->id(),
                'book_id' => $book->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return redirect()->route('books.show', $book)
            ->with('success', 'Review processed successfully!');
    }

    public function destroy(Request $request, Review $review)
    {
        $authenticatedUser = $request->auth();

        if ($authenticatedUser->id() !== $review->user_id && $authenticatedUser->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }
}