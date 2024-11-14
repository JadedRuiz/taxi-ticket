<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateClient
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clientId;
    public $updateData;

    
    public function __construct($clientId, $updateData)
    {
        $this->clientId = $clientId;
        $this->updateData = $updateData;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('client.'.$this->clientId);
    }
}
