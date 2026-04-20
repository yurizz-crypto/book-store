<?php

namespace App\Observers;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\UserActionNotification;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Only trigger if the 'status' column was modified
        if ($order->wasChanged('status')) {
            
            // Send the email
            $order->user->notify(new OrderStatusUpdated($order));

            // Send the in-app database notification
            $order->user->notify(new UserActionNotification('Order Status Updated', [
                'order_id' => $order->id,
                'new_status' => $order->status
            ]));
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
