<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $downloadUrl;

    public function __construct($userId, $downloadUrl)
    {
        $this->userId = $userId;
        $this->downloadUrl = $downloadUrl;
    }

    // Broadcast to a private channel specifically for this user
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }
}