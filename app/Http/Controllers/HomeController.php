<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {        
        $featuredBooks = Cache::remember('featured_homepage_books', 1, fn() => 
            Book::with('category')->latest()->take(8)->get()
        );

        $categories = Cache::remember('homepage_categories', 10, fn() => 
            Category::withCount('books')->get()
        );
        
        return view('home', compact('featuredBooks', 'categories'));
    }
}