<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        
        return back();
    }

    public function markAllAsRead()
    {
        // PERFORMANCE FIX: This executes a single SQL query instead of 
        // fetching all notifications into memory as a Collection.
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back();
    }

    public function click(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        $notification->markAsRead(); 

        // Clean code: Use early returns and ternary operators for cleaner routing
        $url = $notification->data['details']['url'] ?? null;

        return $url ? redirect($url) : back();
    }
}