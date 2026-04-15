<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache; // MUST ADD THIS IMPORT

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Cache::remember('featured_homepage_books', 3600, function () {
            return Book::with('category')->orderBy('created_at', 'desc')->take(8)->get();
        });

        $categories = Cache::remember('homepage_categories', 3600, function () {
            return Category::withCount('books')->get();
        });
        
        return view('home', compact('featuredBooks', 'categories'));
    }
}