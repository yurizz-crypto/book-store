<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category') // Eager load to prevent N+1 queries
            ->search($request->search)  // Utilize our clean model scope
            ->when($request->filled('category'), fn($q) => $q->where('category_id', $request->category))
            ->when($request->sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc'))
            ->when($request->sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
            ->when(!in_array($request->sort, ['price_asc', 'price_desc']), fn($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        return view('books.index', [
            'books' => $books,
            'categories' => $this->getCachedCategories()
        ]);
    }

    public function create()
    {
        return view('books.create', [
            'categories' => $this->getCachedCategories()
        ]);
    }

    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'reviews.user']);
        
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', [
            'book' => $book,
            'categories' => $this->getCachedCategories()
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        
        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }

    /**
     * Retrieve categories from cache, or cache them forever if missing.
     * Note: Remember to clear this cache ('categories_all') when a category is created/updated/deleted.
     */
    private function getCachedCategories()
    {
        return Cache::rememberForever('categories_all', fn() => Category::all());
    }
}