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

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Audit Logging Events
        Event::listen(Login::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Logout::class, [LogAuthenticationEvents::class, 'handle']);
        Event::listen(Failed::class, [LogAuthenticationEvents::class, 'handle']);

        // Tiered Rate Limiting Logic
        RateLimiter::for('global', function (Request $request) {
            
            // Admins get unlimited access
            if ($request->user() && $request->user()->isAdmin()) {
                return Limit::none();
            }

            // Authenticated Customers get 100 requests per minute
            if ($request->user()) {
                return Limit::perMinute(100)->by($request->user()->id)
                            ->response(function () {
                                return response()->view('errors.429', ['message' => 'Take a breath! You are browsing books too fast.'], 429);
                            });
            }

            // Unauthenticated Guests get strict limit (30 requests per min)
            return Limit::perMinute(30)->by($request->ip())
                        ->response(function () {
                            return response()->view('errors.429', ['message' => 'Too many requests. Please slow down or log in.'], 429);
                        });
        });
    }
}