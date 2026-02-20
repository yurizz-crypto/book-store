<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use App\Models\Address; // Important: Import the Address model
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'User',
            'email' => 'admin@pageturner.com',
            'role' => 'admin',
        ]);
        
        $customers = User::factory(10)->create(['role' => 'customer']);

        $customers->each(function ($customer) {
            Address::factory()->create([
                'user_id' => $customer->id,
                'is_default' => true,
            ]);
        });

        $categories = Category::factory(8)->create();

        $categories->each(function ($category) {
            Book::factory(5)->create([
                'category_id' => $category->id
            ]);
        });

        $books = Book::all();

        $customers->each(function ($customer) use ($books) {
            $books->random(rand(3, 5))->each(function ($book) use ($customer) {
                Review::factory()->create([
                    'user_id' => $customer->id,
                    'book_id' => $book->id,
                ]);
            });
        });
    }
}