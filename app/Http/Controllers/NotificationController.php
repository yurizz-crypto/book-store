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
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back();
    }

    public function click($id)
    {
        // 1. Find the notification for the logged-in user
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // 2. Mark it as read
        $notification->markAsRead();

        // 3. NEW CODE: Check if this notification has a 'url' saved in its data
        if (isset($notification->data['url'])) {
            // Redirect them to the file download
            return redirect()->away($notification->data['url']);
        }

        // 4. If there is no URL (like a standard system alert), just reload the page
        return back(); 
    }
}