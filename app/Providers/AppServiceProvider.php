<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Listeners\LogAuthenticationEvents;
use Illuminate\Support\Facades\Event;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\BackupHasFailed;   

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. AUDIT LOGGING: Listen for Authentication Events
        Event::listen(Login::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Logout::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Failed::class, [LogAuthenticationEvents::class, 'handle']);

        Event::listen([BackupWasSuccessful::class, BackupHasFailed::class], function ($event) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            
            $isSuccess = $event instanceof BackupWasSuccessful;
            $title = $isSuccess ? 'System Backup Successful' : 'System Backup FAILED';
            
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\UserActionNotification('System Backup', [
                    'event' => $title,
                    'details' => 'Disaster recovery archive has been generated and stored locally.',
                    'url' => '/admin/dashboard' 
                ]));
            }
        });

        // 3. TIERED RATE LIMITING: (Laboratory 6 - 15% Requirement)
        RateLimiter::for('global', function (Request $request) {
            $user = $request->user();

            // Guests (Public): 30 requests/min
            if (!$user) {
                return Limit::perMinute(30)->by($request->ip())
                    ->response(fn() => response()->view('errors.429', [
                        'message' => 'Too many requests. Please slow down or log in.'
                    ], 429));
            }

            // Admins: 1000 requests/min
            if ($user->role === 'admin') {
                return Limit::perMinute(1000)->by($user->id);
            }

            // Premium Customers: 300 requests/min
            if ($user->role === 'premium') {
                return Limit::perMinute(300)->by($user->id);
            }

            // Standard Customers: 60 requests/min
            return Limit::perMinute(60)->by($user->id)
                ->response(fn() => response()->view('errors.429', [
                    'message' => 'Take a breath! You are browsing books too fast. Upgrade to Premium for higher limits!'
                ], 429));
        });
    }
}