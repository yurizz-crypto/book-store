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
        // Lab 7: Example of caching a category-specific query using tags
        if ($request->filled('category') && empty($request->search) && empty($request->sort)) {
            $cacheKey = "category:{$request->category}:page:" . $request->query('cursor', '1');
            
            $books = Cache::tags(["category:{$request->category}", 'catalog'])->remember($cacheKey, 7200, function () use ($request) {
                return $this->buildBaseQuery($request)->cursorPaginate(12)->withQueryString();
            });
        } else {
            // Dynamic queries with search/sort bypass the cache to avoid cache pollution
            $books = $this->buildBaseQuery($request)->cursorPaginate(12)->withQueryString();
        }

        return view('books.index', [
            'books' => $books,
            'categories' => $this->getCachedCategories()
        ]);
    }

    private function buildBaseQuery(Request $request)
    {
        return Book::select(['id', 'isbn', 'title', 'author', 'price', 'cover_image', 'category_id', 'created_at'])
            ->with(['category:id,name'])
            ->search($request->search)
            ->when($request->filled('category'), fn($q) => $q->where('category_id', $request->category))
            ->when($request->sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc')->orderBy('id', 'asc'))
            ->when($request->sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc')->orderBy('id', 'desc'))
            ->when(!in_array($request->sort, ['price_asc', 'price_desc']), fn($q) => $q->latest()->orderBy('id', 'desc'));
    }

    public function create()
    {
        return view('books.create', ['categories' => $this->getCachedCategories()]);
    }

    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);
        // Notice: No manual cache clearing here anymore! The Observer handles it.

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        // Cache the individual book details using tags
        $book = Cache::tags(["book:{$book->id}", "category:{$book->category_id}"])->remember("book:details:{$book->id}", 7200, function () use ($book) {
            return $book->load(['category:id,name,description', 'reviews.user:id,first_name,last_name']);
        });
        
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', ['book' => $book, 'categories' => $this->getCachedCategories()]);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.show', $book)->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Book deleted successfully!');
    }

    private function getCachedCategories()
    {
        // Tagged cache for categories
        return Cache::tags(['categories'])->rememberForever('categories_all', fn() => Category::all());
    }
}