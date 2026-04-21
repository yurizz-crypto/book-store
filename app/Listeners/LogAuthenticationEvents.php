<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\CriticalSecurityAlert;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use OwenIt\Auditing\Models\Audit;

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

        // SMART SECURITY ALERTS: Prevent the "Spam Bomb"
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
        
        $userId = $event->user?->id;
        $userClass = $event->user ? get_class($event->user) : User::class;
        
        $attemptedEmail = $event->credentials['email'] ?? ($event->user?->email ?? 'Unknown');

        Audit::create([
            'auditable_type' => $userClass,
            'auditable_id'   => $userId ?? 0,
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