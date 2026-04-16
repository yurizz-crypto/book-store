<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;
use App\Notifications\CriticalSecurityAlert;
use Illuminate\Support\Facades\Notification;

class LogAuthenticationEvents
{
    public function handle(Login|Logout|Failed $event): void
    {
        $eventName = match (true) {
            $event instanceof Login => 'login',
            $event instanceof Logout => 'logout',
            $event instanceof Failed => 'failed_login',
            default => 'unknown',
        };

        if ($event instanceof Failed) {
            $admins = User::where('role', 'admin')->get();
            
            $details = [
                'event' => 'Failed Login Attempt',
                'target' => $event->credentials['email'] ?? 'Unknown',
                'ip' => request()->ip(),
            ];

            Notification::send($admins, new CriticalSecurityAlert($details));
        }

        $user = $event->user;

        if ($user) {
            Audit::create([
                'auditable_type' => get_class($user),
                'auditable_id'   => $user->id,
                'event'          => $eventName,
                'user_id'        => $user->id,
                'user_type'      => get_class($user),
                'url'            => request()->fullUrl(),
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
                'new_values'     => ['email' => $user->email],
            ]);
        }
    }
}