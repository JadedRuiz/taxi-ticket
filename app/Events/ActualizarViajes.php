<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActualizarViajes
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cajaId;
    public $viajes;

    
    public function __construct(int $cajaId, array $viajes)
    {
        $this->cajaId = $cajaId;
        $this->updateData = $updateData;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('client.'.$this->cajaId);
    }

    public function broadcastAs()
    {
        return 'actualizar.viajes';
    }
}
