<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\CriticalSecurityAlert;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue; // CRITICAL: Import this
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter; // CRITICAL: Import this
use OwenIt\Auditing\Models\Audit;

// CRITICAL: Implement ShouldQueue to prevent server freezing and timing attacks
class LogAuthenticationEvents implements ShouldQueue 
{
    public function handle(Login|Logout|Failed $event): void
    {
        $eventName = match (true) {
            $event instanceof Login => 'login',
            $event instanceof Logout => 'logout',
            $event instanceof Failed => 'failed_login',
            default => 'unknown',
        };

        // 1. SMART SECURITY ALERTS: Prevent the "Spam Bomb"
        if ($event instanceof Failed) {
            $ip = request()->ip();
            $email = $event->credentials['email'] ?? 'Unknown';

            // Only notify admins if the same IP fails 5 times.
            if (RateLimiter::tooManyAttempts('admin_login_alert:' . $ip, 5)) {
                $admins = User::where('role', 'admin')->get();
                
                Notification::send($admins, new CriticalSecurityAlert([
                    'event'  => 'Repeated Failed Login Attempts (Brute Force Warning)',
                    'target' => $email,
                    'ip'     => $ip,
                ]));

                // Clear the limiter so we don't send 100 emails for the next 100 attempts
                RateLimiter::clear('admin_login_alert:' . $ip);
            } else {
                // Register the failed attempt for 1 hour
                RateLimiter::hit('admin_login_alert:' . $ip, 3600); 
            }
        }

        // 2. BULLETPROOF AUDITING: Safely handle non-existent users
        // If a user fails login with a fake email, $event->user is null. 
        // We still want to log that attempt!
        
        $userId = $event->user?->id; // Uses safe null-safe operator
        $userClass = $event->user ? get_class($event->user) : User::class;
        
        // Grab the attempted email from the form input, fallback to the user object
        $attemptedEmail = $event->credentials['email'] ?? ($event->user?->email ?? 'Unknown');

        Audit::create([
            'auditable_type' => $userClass,
            'auditable_id'   => $userId ?? 0, // Fallback to 0 if user doesn't exist
            'event'          => $eventName,
            'user_id'        => $userId,
            'user_type'      => $userId ? $userClass : null,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'new_values'     => ['attempted_email' => $attemptedEmail],
        ]);
    }
}