<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActualizarViajes implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $viajes;

    
    public function __construct(array $viajes)
    {
        $this->viajes = $viajes;
    }

    public function broadcastOn()
    {
        return new Channel('table-event');
    }
}
