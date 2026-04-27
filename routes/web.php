<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\DataPortabilityController;
use App\Http\Controllers\AiController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
// web.php (Authenticated Section)
Route::middleware('auth')->group(function () {
    Route::post('/ai/matchmake', [AiController::class, 'matchmake'])->name('ai.matchmake');

    Route::post('/books/{book}/analyze-reviews', [ReviewController::class, 'analyzeBookReviews'])
        ->name('books.analyze-reviews');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-data', [DashboardController::class, 'exportMyData'])->name('dashboard.export-data');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications/{id}/click', [App\Http\Controllers\NotificationController::class, 'click'])->name('notifications.click');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});
/*
|--------------------------------------------------------------------------
| Authenticated Routes (Verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::patch('/orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    Route::get('/orders/{order}/invoice', function (\App\Models\Order $order) {
            if (auth()->id() !== $order->user_id) abort(403);
            
            $order->load('orderItems.book'); 
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.invoice', compact('order'));
            return $pdf->download('Invoice_#' . $order->id . '.pdf');
        })->name('orders.invoice');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Category Management
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Book Management
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/books/{book}/ai-status', [ReviewController::class, 'checkAiStatus'])->name('books.ai-status');
    
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    Route::get('/audits/export', [AuditController::class, 'export'])->name('audits.export');

    Route::get('/export/users', [DataPortabilityController::class, 'exportUsers'])->name('export.users');
    Route::post('/import/users', [DataPortabilityController::class, 'importUsers'])->name('import.users');
    Route::get('/export/orders', [DataPortabilityController::class, 'exportOrders'])->name('export.orders');
    Route::get('/export/financials', [DataPortabilityController::class, 'exportFinancials'])->name('export.financials');
    Route::get('/export/books', [DataPortabilityController::class, 'exportBooks'])->name('export.books');
    Route::post('/import/books', [DataPortabilityController::class, 'importBooks'])->name('import.books');

    Route::get('/import/books/template', [DataPortabilityController::class, 'downloadTemplate'])->name('import.template');
    Route::post('/backup/run', [DataPortabilityController::class, 'triggerBackup'])->name('backup.run');
});

require __DIR__.'/auth.php';