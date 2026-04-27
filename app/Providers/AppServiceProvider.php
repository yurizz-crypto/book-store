<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationEvents;
use App\Listeners\NotifyAdminsOfBackupStatus;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Events\BackupHasFailed;   
use Spatie\Backup\Events\BackupWasSuccessful;
use App\Observers\BookObserver;
use App\Models\Book;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Observers
        Book::observe(BookObserver::class);
        Order::observe(OrderObserver::class);

        // 2. Audit Logging Events
        Event::listen(Login::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Logout::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Failed::class, [LogAuthenticationEvents::class, 'handle']);

        // 3. System Health Events (CLEAN CODE: Delegated to a dedicated Listener class)
        Event::listen(
            [BackupWasSuccessful::class, BackupHasFailed::class], 
            NotifyAdminsOfBackupStatus::class
        );

        // 4. Tiered Rate Limiting
        $this->configureRateLimiting();
    }

    /**
     * CLEAN CODE: Extracted to keep the boot method from getting cluttered.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('global', function (Request $request) {
            $user = $request->user();

            if (!$user) {
                return Limit::perMinute(30)->by($request->ip())
                    ->response(fn() => response()->view('errors.429', [
                        'message' => 'Too many requests. Please slow down or log in.'
                    ], 429));
            }

            if ($user->isAdmin()) { // Utilizing the helper method we added to User.php!
                return Limit::perMinute(1000)->by($user->id);
            }

            if ($user->role === 'premium') {
                return Limit::perMinute(300)->by($user->id);
            }

            return Limit::perMinute(60)->by($user->id)
                ->response(fn() => response()->view('errors.429', [
                    'message' => 'Take a breath! You are browsing books too fast. Upgrade to Premium for higher limits!'
                ], 429));
        });
    }
}